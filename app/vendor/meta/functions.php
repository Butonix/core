<?php
/**
 * Retrieve DB data in The Fancy Way
 * Alternatively you can use WpFluent core module or pure MySQL queries.
 * @package @WpFluent
 * @since 0.1
 */

require_once __DIR__ . '/classes/class-meta-tables.php';
require_once __DIR__ . '/classes/class-meta-tables-walker.php';

// the output for devs
$arg_test = [
  'grant_types' => 'dasdsaasd'
];

//update_that_meta('701', 'wo_client_meta', $arg_test);

// $test = get_that_meta(701, 'wo_client_meta', false);
// echo '<pre>';
// var_dump($test);
// die;

// echo '<br><br>';
// $test2 = get_that_meta(702, 'wo_client_meta', false);
// var_dump($test2);
$args = array(
  'post_type' => 'genres'
);

//get_entries($args);

//$data = get_posts($args);

//var_dump($data);



// $test = delete_that_meta(703, 'wo_client_meta', false);
// var_dump($test);