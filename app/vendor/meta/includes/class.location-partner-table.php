<?php
/**
 * Copyright: 2015 Daniel Iser
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

// Exit if accessed directly
if ( ! defined( 'ABSPATH' ) ) exit;

class SuperCustomFields extends ChartiPowerMeta {
	public $post_types = array( 'page', 'post' );
	public $name;
	public $version = '1.0.0';
	public $primary_key;
	public $foreign_key;
	public $auto_join = true;


	public function __construct() {
		//get_that_meta($id, $table_name)
		$this->get_meta_table( 431, 'woo_client_meta' );

		parent::__construct();
	}

	// send the $ID and $table name to the parent
	public function get_meta_table() {

		//$this->foreign_key, $object_id;

		global $wpdb;
		//return $wpdb->get_row( "SELECT * FROM $this->table_name WHERE $column = '$row_id' LIMIT 1;" );

		return $wpdb->get_row( "SELECT * FROM $this->table_name WHERE $column = '$row_id' LIMIT 1;" );

		// $this->name(); // table name
		// $this->primary_key();
		// $this->foreign_key(); // 
	}

	public function name() {
		$this->name = 'wo_client_meta';
	}

	public function primary_key() {
		$this->primary_key = 'id';
	}

	public function foreign_key() {
		$this->foreign_key = 'post_id';
	}


	/**
	 * Get columns and formats
	 *
	 * @access  public
	 * @since   1.0.0
	 */
	// public function get_columns() {
	// 	return array(
	// 		'id'      => '%d',
	// 		'post_id' => '%d',
	// 		'city'    => '%s',
	// 		'state'   => '%s',
	// 		'zipcode' => '%s',
	// 		'lat'     => '%f',
	// 		'long'    => '%f',
	// 	);
	// }

	/**
	 * Get default column values
	 *
	 * @access  public
	 * @since   1.0.0
	 */
//	public function get_column_defaults() {
	// 	return array(
	// 		'post_id' => 0,
	// 		'city'    => '',
	// 		'state'   => '',
	// 		'zipcode' => '',
	// 		'lat'     => '',
	// 		'long'    => '',
	// 	);
	// }

	/**
	 * Example function to search for posts by partner table data.
	 * Search for posts by zipcode.
	 *
	 * @access  public
	 * @since   1.0.0
	 */
	// public function get_posts_by_zipcode( $zipcode ) {
	// 	global $wpdb;

	// 	$rows = $wpdb->get_results(
	// 		$wpdb->prepare( "SELECT * FROM $this->table_name WHERE `zipcode` = %s", $zipcode ),
	// 		ARRAY_A
	// 	);

	// 	$posts = array();
	// 	foreach ( $rows as $row ) {
	// 		$posts[] = $this->join_post_with_data( $row['post_id'], $row );
	// 	}

	// 	return $posts;
	// }

	public function get_the_meta($id, $table_name) {

	}
}

/**
 * Instantiate Location_Partner_Table.
 *
 * @access  public
 * @since   1.0.0
 */
// /global $Location_Partner_Table;
new SuperCustomFields();

