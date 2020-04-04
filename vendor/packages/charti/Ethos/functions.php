<?php
/**
 * Use this file like a functions.php of your theme
 *
 * We recommend to keep your code organized and split in files, and
 * you can include them here.
 */

$test = new \Core\Ethos\Taxonomies\Taxonomy('test', 'oosdsokay', 'post');
$default = array('name' => 'Naaame');
$test->set_labels($default);

