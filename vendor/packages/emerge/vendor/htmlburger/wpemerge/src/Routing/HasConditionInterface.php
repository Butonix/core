<?php
/**
 * @package   WPEmerge
 * @author    Atanas Angelov <atanas.angelov.dev@gmail.com>
 * @copyright 2018 Atanas Angelov
 * @license   https://www.gnu.org/licenses/gpl-2.0.html GPL-2.0
 * @link      https://wpemerge.com/
 */

namespace WPEmerge\Routing;

use WPEmerge\Routing\Conditions\ConditionInterface;

/**
 * Represent an object which has a route condition.
 */
interface HasConditionInterface {
	/**
	 * Get condition.
	 *
	 * @return ConditionInterface
	 */
	public function getCondition();

	/**
	 * set condition.
	 *
	 * @param  ConditionInterface $condition
	 * @return void
	 */
	public function setCondition( ConditionInterface $condition );
}
