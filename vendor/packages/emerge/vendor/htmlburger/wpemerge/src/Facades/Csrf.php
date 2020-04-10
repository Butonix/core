<?php
/**
 * @package   WPEmerge
 * @author    Atanas Angelov <atanas.angelov.dev@gmail.com>
 * @copyright 2018 Atanas Angelov
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0
 * @link      https://wpemerge.com/
 */

namespace WPEmerge\Facades;

use WPEmerge\Support\Facade;

/**
 * Provide access to the CSRF service.
 *
 * @codeCoverageIgnore
 * @see \WPEmerge\Csrf\Csrf
 *
 * @method static string getToken()
 * @method static string getTokenFromRequest( \WPEmerge\Requests\RequestInterface $request )
 * @method static string generateToken( int|string $action = -1 )
 * @method static boolean isValidToken( string $token, int|string $action = -1 )
 * @method static string url( string $url )
 * @method static void field()
 */
class Csrf extends Facade {
	protected static function getFacadeAccessor() {
		return WPEMERGE_CSRF_KEY;
	}
}
