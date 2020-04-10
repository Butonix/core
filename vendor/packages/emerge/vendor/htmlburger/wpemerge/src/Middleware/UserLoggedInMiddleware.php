<?php
/**
 * @package   WPEmerge
 * @author    Atanas Angelov <atanas.angelov.dev@gmail.com>
 * @copyright 2018 Atanas Angelov
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0
 * @link      https://wpemerge.com/
 */

namespace WPEmerge\Middleware;

use Closure;
use WPEmerge;
use WPEmerge\Requests\RequestInterface;

/**
 * Redirect non-logged in users to a specific URL.
 */
class UserLoggedInMiddleware {
	/**
	 * {@inheritDoc}
	 */
	public function handle( RequestInterface $request, Closure $next, $url = '' ) {
		if ( is_user_logged_in() ) {
			return $next( $request );
		}

		if ( empty( $url ) ) {
			$url = wp_login_url( $request->getUrl() );
		}

		$url = apply_filters( 'wpemerge.middleware.user.logged_in.redirect_url', $url, $request );

		return WPEmerge\redirect()->to( $url );
	}
}
