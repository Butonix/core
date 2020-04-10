<?php
/**
 * @package   WPEmerge
 * @author    Atanas Angelov <atanas.angelov.dev@gmail.com>
 * @copyright 2018 Atanas Angelov
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0
 * @link      https://wpemerge.com/
 */

namespace WPEmerge\Routing;

use WPEmerge\Helpers\Handler;
use WPEmerge\Middleware\HasMiddlewareTrait;
use WPEmerge\Requests\RequestInterface;
use WPEmerge\Routing\Conditions\ConditionInterface;
use WPEmerge\Support\Arr;

/**
 * Represent a route
 */
class Route implements RouteInterface, HasQueryFilterInterface {
	use HasMiddlewareTrait;
	use HasQueryFilterTrait;

	/**
	 * Allowed methods.
	 *
	 * @var string[]
	 */
	protected $methods = [];

	/**
	 * Route handler.
	 *
	 * @var Handler
	 */
	protected $handler = null;

	/**
	 * Constructor.
	 *
	 * @codeCoverageIgnore
	 * @param  array<string>      $methods
	 * @param  ConditionInterface $condition
	 * @param  Handler            $handler
	 */
	public function __construct( $methods, $condition, Handler $handler ) {
		$this->methods = $methods;
		$this->setCondition( $condition );
		$this->handler = $handler;
	}

	/**
	 * Get allowed methods.
	 *
	 * @codeCoverageIgnore
	 * @return array<string>
	 */
	public function getMethods() {
		return $this->methods;
	}

	/**
	 * Get handler.
	 *
	 * @codeCoverageIgnore
	 * @return Handler
	 */
	public function getHandler() {
		return $this->handler;
	}

	/**
	 * {@inheritDoc}
	 */
	public function isSatisfied( RequestInterface $request ) {
		if ( ! in_array( $request->getMethod(), $this->methods ) ) {
			return false;
		}

		return $this->condition->isSatisfied( $request );
	}

	/**
	 * {@inheritDoc}
	 */
	public function getArguments( RequestInterface $request ) {
		return $this->getCondition()->getArguments( $request );
	}

	/**
	 * {@inheritDoc}
	 */
	public function decorate( $attributes ) {
		$middleware = Arr::get( $attributes, 'middleware', [] );
		$query = Arr::get( $attributes, 'query', null );

		$this->middleware( $middleware );

		if ( $query !== null) {
			$this->setQueryFilter( $query );
		}
	}

	/**
	 * {@inheritDoc}
	 */
	public function handle( RequestInterface $request, $arguments = [] ) {
		$arguments = array_merge(
			[$request],
			$arguments,
			array_values( $this->condition->getArguments( $request ) )
		);

		return call_user_func_array( [$this->getHandler(), 'execute'], $arguments );
	}
}
