<?php

namespace ChartiPowerMetaTables;
/**
 * Retrieve DB data in The Fancy Way
 * Alternatively you can use WpFluent core module or pure MySQL queries.
 * @package @WpFluent
 * @since 0.1
 */

require_once __DIR__ . '/src/class-meta-tables.php';
require_once __DIR__ . '/src/class-meta-tables-walker.php';

// the output for devs
$arg_test = [
  'grant_types' => 'dasdsaasd'
];

$args = array(
  'post_type' => 'genres'
);
