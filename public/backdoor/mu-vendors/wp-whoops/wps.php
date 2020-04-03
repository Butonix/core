<?php
/*
Author: Andrey "Rarst" Savchenko
Version: 
Author URI: http://www.rarst.net/
License: MIT

Copyright (c) 2013 Andrey "Rarst" Savchenko
*/

if ( file_exists( __DIR__ . '/vendor/autoload.php' ) ) {
	require __DIR__ . '/vendor/autoload.php';
}

if ( isset( $_GET['wps_disable'] ) ) {
	return;
}

$wps = new \Rarst\wps\Plugin();
$wps->run();
