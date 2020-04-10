<?php
/**
 * @package   WPEmerge
 * @author    Atanas Angelov <atanas.angelov.dev@gmail.com>
 * @copyright 2018 Atanas Angelov
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0
 * @link      https://wpemerge.com/
 */

namespace WPEmerge\View;

use Closure;
use WPEmerge\Facades\ViewEngine;
use WPEmerge\Helpers\Handler;
use WPEmerge\Helpers\MixedType;

/**
 * Provide general view-related functionality.
 */
class ViewService {
	/**
	 * Global variables.
	 *
	 * @var array
	 */
	protected $globals = [];

	/**
	 * View composers.
	 *
	 * @var array
	 */
	protected $composers = [];

	/**
	 * Get global variables.
	 *
	 * @return array
	 */
	public function getGlobals() {
		return $this->globals;
	}

	/**
	 * Set a global variable.
	 *
	 * @param  string $key
	 * @param  mixed  $value
	 * @return void
	 */
	public function addGlobal( $key, $value ) {
		$this->globals[ $key ] = $value;
	}

	/**
	 * Set an array of global variables.
	 *
	 * @param  array $globals
	 * @return void
	 */
	public function addGlobals( $globals ) {
		foreach ( $globals as $key => $value ) {
			$this->addGlobal( $key, $value );
		}
	}

	/**
	 * Get view composer.
	 *
	 * @param  string         $view
	 * @return array<Handler>
	 */
	public function getComposersForView( $view ) {
		$view = ViewEngine::canonical( $view );

		$composers = [];

		foreach ( $this->composers as $composer ) {
			if ( in_array( $view, $composer['views'], true ) ) {
				$composers[] = $composer['composer'];
			}
		}

		return $composers;
	}

	/**
	 * Add view composer.
	 *
	 * @param  string|array<string> $views
	 * @param  string|Closure       $composer
	 * @return void
	 */
	public function addComposer( $views, $composer ) {
		$views = array_map( function ( $view ) {
			return ViewEngine::canonical( $view );
		}, MixedType::toArray( $views ) );

		$handler = new Handler( $composer, 'compose', '\\App\\ViewComposers\\' );

		$this->composers[] = [
			'views' => $views,
			'composer' => $handler,
		];
	}

	/**
	 * Get the composed context for a view.
	 * Passes all arguments to the composer.
	 *
	 * @param  ViewInterface $view
	 * @return void
	 */
	public function compose( ViewInterface $view ) {
		$composers = $this->getComposersForView( $view->getName() );

		foreach ( $composers as $composer ) {
			$composer->execute( $view );
		}
	}

	/**
	 * Create a view instance.
	 *
	 * @param  string|array<string> $views
	 * @return ViewInterface
	 */
	public function make( $views ) {
		$views = MixedType::toArray( $views );
		return ViewEngine::make( $views );
	}

	/**
	 * Trigger core hooks for a partial, if any.
	 *
	 * @codeCoverageIgnore
	 * @param  string $name
	 * @return void
	 */
	public function triggerPartialHooks( $name ) {
		$core_partial = '/^(header|sidebar|footer)(?:-(.*?))?(\.|$)/i';
		if ( preg_match( $core_partial, $name, $matches ) ) {
			do_action( "get_{$matches[1]}", $matches[2] );
		}
	}
}
