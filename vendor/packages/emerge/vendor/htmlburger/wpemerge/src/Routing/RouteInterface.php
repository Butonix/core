<?php
/**
 * @package   WPEmerge
 * @author    Atanas Angelov <atanas.angelov.dev@gmail.com>
 * @copyright 2018 Atanas Angelov
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0
 * @link      https://wpemerge.com/
 */

namespace WPEmerge\Routing;

use WPEmerge\Middleware\HasMiddlewareInterface;
use WPEmerge\Requests\RequestInterface;

/**
 * Interface that routes must implement
 */
interface RouteInterface extends HasConditionInterface, HasMiddlewareInterface {
	/**
	 * Get whether the route is satisfied.
	 *
	 * @param  RequestInterface $request
	 * @return boolean
	 */
	public function isSatisfied( RequestInterface $request );

	/**
	 * Get arguments.
	 *
	 * @param  RequestInterface $request
	 * @return array
	 */
	public function getArguments( RequestInterface $request );

	/**
	 * Decorate route.
	 *
	 * @param  array<string, mixed> $attributes
	 * @return void
	 */
	public function decorate( $attributes );

	/**
	 * Get a response for the given request.
	 *
	 * @param  RequestInterface                    $request
	 * @param  array                               $arguments
	 * @return \Psr\Http\Message\ResponseInterface
	 */
	public function handle( RequestInterface $request, $arguments = [] );
}
