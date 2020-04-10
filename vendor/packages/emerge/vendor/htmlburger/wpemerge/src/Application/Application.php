<?php
/**
 * @package   WPEmerge
 * @author    Atanas Angelov <atanas.angelov.dev@gmail.com>
 * @copyright 2018 Atanas Angelov
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0
 * @link      https://wpemerge.com/
 */

namespace WPEmerge\Application;

use Closure;
use Pimple\Container;
use WPEmerge\Controllers\ControllersServiceProvider;
use WPEmerge\Csrf\CsrfServiceProvider;
use WPEmerge\Exceptions\ConfigurationException;
use WPEmerge\Exceptions\ExceptionsServiceProvider;
use WPEmerge\Facades\Response;
use WPEmerge\Facades\Route;
use WPEmerge\Flash\FlashServiceProvider;
use WPEmerge\Input\OldInputServiceProvider;
use WPEmerge\Kernels\KernelsServiceProvider;
use WPEmerge\Requests\Request;
use WPEmerge\Requests\RequestsServiceProvider;
use WPEmerge\Responses\ResponsesServiceProvider;
use WPEmerge\Routing\RoutingServiceProvider;
use WPEmerge\ServiceProviders\ServiceProviderInterface;
use WPEmerge\Support\AliasLoader;
use WPEmerge\Support\Arr;
use WPEmerge\View\ViewServiceProvider;

/**
 * Main communication channel with the application.
 */
class Application {
	/**
	 * IoC container.
	 *
	 * @var Container
	 */
	protected $container = null;

	/**
	 * Flag whether to intercept and render configuration exceptions.
	 *
	 * @var boolean
	 */
	protected $render_configuration_exceptions = true;

	/**
	 * Flag whether the application has been bootstrapped.
	 *
	 * @var boolean
	 */
	protected $bootstrapped = false;

	/**
	 * Array of application service providers.
	 *
	 * @var string[]
	 */
	protected $service_providers = [
		KernelsServiceProvider::class,
		ExceptionsServiceProvider::class,
		RequestsServiceProvider::class,
		ResponsesServiceProvider::class,
		RoutingServiceProvider::class,
		ViewServiceProvider::class,
		ControllersServiceProvider::class,
		CsrfServiceProvider::class,
		FlashServiceProvider::class,
		OldInputServiceProvider::class,
	];

	/**
	 * Constructor.
	 *
	 * @param Container $container
	 * @param boolean   $render_configuration_exceptions
	 */
	public function __construct( Container $container, $render_configuration_exceptions = true ) {
		$this->container = $container;
		$this->render_configuration_exceptions = $render_configuration_exceptions;

		$config = isset( $container[ WPEMERGE_CONFIG_KEY ] ) ? $container[ WPEMERGE_CONFIG_KEY ] : [];
		$config = array_merge( [
			'providers' => [],
		], $config );
		$container[ WPEMERGE_CONFIG_KEY ] = $config;
	}

	/**
	 * Get whether WordPress is in debug mode.
	 *
	 * @return boolean
	 */
	public function debugging() {
		$debugging = ( defined( 'WP_DEBUG' ) && WP_DEBUG );
		$debugging = apply_filters( 'wpemerge.debug', $debugging );
		return $debugging;
	}

	/**
	 * Get whether the application has been bootstrapped.
	 *
	 * @return boolean
	 */
	public function isBootstrapped() {
		return $this->bootstrapped;
	}

	/**
	 * Throw an exception if the application has not been bootstrapped.
	 *
	 * @return void
	 */
	protected function verifyBootstrap() {
		if ( ! $this->isBootstrapped() ) {
			throw new ConfigurationException( static::class . ' must be bootstrapped first.' );
		}
	}

	/**
	 * Get the IoC container instance.
	 *
	 * @return Container
	 */
	public function getContainer() {
		return $this->container;
	}

	/**
	 * Bootstrap the application.
	 * WordPress' 'after_setup_theme' action is a good place to call this.
	 *
	 * @param  array   $config
	 * @param  boolean $run
	 * @return void
	 */
	public function bootstrap( $config = [], $run = true ) {
		if ( $this->isBootstrapped() ) {
			throw new ConfigurationException( static::class . ' already bootstrapped.' );
		}

		$this->bootstrapped = true;

		$container = $this->getContainer();
		$this->loadConfig( $container, $config );
		$this->loadServiceProviders( $container );

		$this->renderConfigurationExceptions( function () use ( $config, $run ) {
			$this->loadRoutes(
				Arr::get( $config, 'routes.web', '' ),
				Arr::get( $config, 'routes.admin', '' ),
				Arr::get( $config, 'routes.ajax', '' )
			);

			if ( $run ) {
				$kernel = $this->resolve( WPEMERGE_WORDPRESS_HTTP_KERNEL_KEY );
				$kernel->bootstrap();
			}
		} );
	}

	/**
	 * Load config into the service container.
	 *
	 * @codeCoverageIgnore
	 * @param  Container $container
	 * @param  array     $config
	 * @return void
	 */
	protected function loadConfig( Container $container, $config ) {
		$container[ WPEMERGE_CONFIG_KEY ] = array_merge(
			$container[ WPEMERGE_CONFIG_KEY ],
			$config
		);
	}

	/**
	 * Register and bootstrap all service providers.
	 *
	 * @codeCoverageIgnore
	 * @param  Container $container
	 * @return void
	 */
	protected function loadServiceProviders( Container $container ) {
		$container[ WPEMERGE_SERVICE_PROVIDERS_KEY ] = array_merge(
			$this->service_providers,
			$container[ WPEMERGE_CONFIG_KEY ]['providers']
		);

		$service_providers = array_map( function ( $service_provider ) {
			if ( ! is_subclass_of( $service_provider, ServiceProviderInterface::class ) ) {
				throw new ConfigurationException(
					'The following class does not implement ' .
					'ServiceProviderInterface: ' . $service_provider
				);
			}

			return new $service_provider();
		}, $container[ WPEMERGE_SERVICE_PROVIDERS_KEY ] );

		$this->registerServiceProviders( $service_providers, $container );
		$this->bootstrapServiceProviders( $service_providers, $container );
	}

	/**
	 * Register all service providers.
	 *
	 * @param  array<\WPEmerge\ServiceProviders\ServiceProviderInterface> $service_providers
	 * @param  Container                                                  $container
	 * @return void
	 */
	protected function registerServiceProviders( $service_providers, Container $container ) {
		foreach ( $service_providers as $provider ) {
			$provider->register( $container );
		}
	}

	/**
	 * Bootstrap all service providers.
	 *
	 * @param  array<\WPEmerge\ServiceProviders\ServiceProviderInterface> $service_providers
	 * @param  Container                                                  $container
	 * @return void
	 */
	protected function bootstrapServiceProviders( $service_providers, Container $container ) {
		foreach ( $service_providers as $provider ) {
			$provider->bootstrap( $container );
		}
	}

	/**
	 * Load route definition files depending on the current request.
	 *
	 * @codeCoverageIgnore
	 * @param  string $web
	 * @param  string $admin
	 * @param  string $ajax
	 * @return void
	 */
	protected function loadRoutes( $web = '', $admin = '', $ajax = '' ) {
		if ( defined( 'DOING_AJAX' ) && DOING_AJAX ) {
			$this->loadRoutesFile( $ajax, [
				'namespace' => '\\App\\Controllers\\Ajax\\',
				'middleware' => ['ajax'],
			] );
			return;
		}

		if ( is_admin() ) {
			$this->loadRoutesFile( $admin, [
				'namespace' => '\\App\\Controllers\\Admin\\',
				'middleware' => ['admin'],
			] );
			return;
		}

		$this->loadRoutesFile( $web, [
			'namespace' => '\\App\\Controllers\\Web\\',
			'handler' => '\\WPEmerge\\Controllers\\WordPressController@handle',
			'middleware' => ['web'],
		] );
	}

	/**
	 * Load a route definition file, applying attributes to all routes defined within.
	 *
	 * @codeCoverageIgnore
	 * @param  string               $file
	 * @param  array<string, mixed> $attributes
	 * @return void
	 */
	protected function loadRoutesFile( $file, $attributes ) {
		if ( empty( $file ) ) {
			return;
		}

		Route::attributes( $attributes )->group( $file );
	}

	/**
	 * Register a facade class.
	 *
	 * @param  string $alias
	 * @param  string $facade_class
	 * @return void
	 */
	public function alias( $alias, $facade_class ) {
		AliasLoader::getInstance()->alias( $alias, $facade_class );
	}

	/**
	 * Resolve a dependency from the IoC container.
	 *
	 * @param  string     $key
	 * @return mixed|null
	 */
	public function resolve( $key ) {
		$this->verifyBootstrap();

		if ( ! isset( $this->getContainer()[ $key ] ) ) {
			return null;
		}

		return $this->getContainer()[ $key ];
	}

	/**
	 * Create and return a class instance.
	 *
	 * @throws ClassNotFoundException
	 * @param  string $class
	 * @return object
	 */
	public function instantiate( $class ) {
		$this->verifyBootstrap();

		$instance = $this->resolve( $class );

		if ( $instance === null ) {
			if ( ! class_exists( $class ) ) {
				throw new ClassNotFoundException( 'Class not found: ' . $class );
			}

			$instance = new $class();
		}

		return $instance;
	}

	/**
	 * Catch any configuration exceptions and short-circuit to an error page.
	 *
	 * @codeCoverageIgnore
	 * @param  Closure $action
	 * @return void
	 */
	protected function renderConfigurationExceptions( Closure $action ) {
		try {
			$action();
		} catch ( ConfigurationException $exception ) {
			if ( ! $this->render_configuration_exceptions ) {
				throw $exception;
			}

			$request = Request::fromGlobals();
			$handler = $this->resolve( WPEMERGE_EXCEPTIONS_CONFIGURATION_ERROR_HANDLER_KEY );

			add_filter( 'wpemerge.pretty_errors.apply_admin_styles', '__return_false' );
			Response::respond( $handler->getResponse( $request, $exception ) );
			wp_die();
		}
	}
}
