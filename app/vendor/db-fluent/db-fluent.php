<?php

// Plugin Name: DB Fluent Queries

defined('ABSPATH') or die;

// Autoload plugin.
require __DIR__ . '/autoload.php';

if (! function_exists('wpFluent')) {
    /**
     * @return \WpFluent\QueryBuilder\QueryBuilderHandler
     */
    function Fluent() {
        static $wpFluent;

        if (! $wpFluent) {
            global $wpdb;

            $connection = new WpFluent\Connection($wpdb, ['prefix' => $wpdb->prefix]);

            $wpFluent = new \WpFluent\QueryBuilder\QueryBuilderHandler($connection);
        }

        return $wpFluent;
    }
}
