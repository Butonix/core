<?php

// Loading PackageLoader
include __DIR__.'/real_autoload.php';

// Loading 'project' package
$loader = new PackageLoader\PackageLoader();

$loader->load(__DIR__."/packages");

