<?php
/**
 * @package   WPEmerge
 * @author    Atanas Angelov <atanas.angelov.dev@gmail.com>
 * @copyright 2018 Atanas Angelov
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0
 * @link      https://wpemerge.com/
 */

namespace WPEmerge\Csrf;

use Closure;
use WPEmerge\Facades\Csrf as CsrfService;
use WPEmerge\Requests\RequestInterface;

/**
 * Store current request data and clear old request data
 */
class CsrfMiddleware {
	/**
	 * {@inheritDoc}
	 * @throws InvalidCsrfTokenException
	 */
	public function handle( RequestInterface $request, Closure $next ) {
		if ( ! $request->isReadVerb() ) {
			$token = CsrfService::getTokenFromRequest( $request );
			if ( ! CsrfService::isValidToken( $token ) ) {
				throw new InvalidCsrfTokenException();
			}
		}

		CsrfService::generateToken();

		return $next( $request );
	}
}
