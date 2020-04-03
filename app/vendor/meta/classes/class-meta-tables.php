<?php
/**
  *   Charti CMS
  *   
  *   ADD, UPDATE, POST & DELETE any data
  *  
  *
  *   @param get_that_meta()
  *   @param update_that_meta()
  *
  *   @package Charti CMS - The little Brother of Wordpress haha
  *   @package WpFluent - MU Plugin
  *   @see https://charti.dev/docs/meta-tables
  *   @see https://github.com/adreastrian/wp-fluent
  *   * Available Events *
  *   - before-select
  *   - after-select
  *   - before-insert
  *   - after-insert
  *   - before-update
  *   - after-update
  *   - before-delete
  *   - after-delete
  *
  *   @since 0.1
  *
  *
  *
**/

class charti_meta_tables {

  public $meta_key_id = false,
         $entry_id = false,
         $meta_table,
         $db_prefix = false,
         $query;


  public function __construct(){
    // Initialize database connection
    $this->Fluent();
  }


  function error_handler() {
    // to be extended
  }

  /**
   *
   *  Similar functionality with insert_post_meta() native from Wordpress
   *  insert_meta_row() gives the freedom to retrieve meta keys data from a any table 
   *  @since 0.1
   */

  function insert_meta_row($args, $meta_key, $meta_value, $meta_table, $db_prefix) {

    if( $args ) {
      
      $data = $args;

    } else {
      
      $data = array(
        // 'post_id' => '',
        'meta_key' => $meta_key,
        'meta_value' => $meta_value
      );

    }
    
      $db_query = DB::table($meta_table)->insert($data);

      return $db_query;
  }


  /**
   *
   *  Similar functionality with get_post_meta() native from Wordpress
   *  get_meta_table() gives the freedom to retrieve meta keys data from a any table 
   *  @since 0.1
   */
  function get_meta_row($meta_key_id, $entry_id = null, $meta_table, $db_prefix) {
    
    if ( empty($meta_key_id) && empty($entry_id) ) {
      if( ! is_numeric( $meta_key_id ) || ! is_numeric( $entry_id ) ) {
        return false;
      }
    }

    if($entry_id) {
      $db_query = DB::table($meta_table)->where('post_id', '=', $entry_id);
    }
    
    if($meta_key_id) {
      $db_query = DB::table($meta_table)->where('meta_id', '=', $meta_key_id);
    }

    $row = $db_query->get();

    return $row;
  }

  /**
   *
   *  Similar functionality with remove_post_meta() native from Wordpress
   *  remove_meta_row() gives the freedom to delete meta keys data from a any table 
   *  @since 0.1
   */
  function delete_meta_row($meta_key_id, $meta_table, $db_prefix) {

    if(empty($meta_key_id) || ! is_numeric( $meta_key_id )) {
      throw new ExceptionError('Provide a valid meta_key_id');
    }

    $find_key = DB::table($meta_table)->find($meta_key_id, 'meta_id');
    
    if($find_key) {
      
      $db_query = DB::table($meta_table)->where('meta_id', '=', $meta_key_id)->delete();

      return true;

    } else {

      return false;

    }
  }


  // temp
  function update_meta_row($meta_key_id, $meta_table, $meta_value) {
    $query = DB::table($meta_table)->where('meta_id', $meta_key_id)->update($meta_value);
    return $query;
  }
  // Get settings field row
  // todocharti merge $option_id and $option_name in one paramter
  function get_settings_field( $option_id = null, $option_name = null  ) {

    if(! is_numeric( $option_id )) {
      // to be extended
      //$this->throw_errors('option_id');
    
    }
    
    if ( ! $option_name && $option_id ){
      $query = DB::table('wp_settings')->where('option_id', '=', $option_id);
    }

    if ( $option_name &&  ! $option_id ) {
      $query = DB::table('wp_settings')->where('option_name', '=', $option_name); 
    }

    return $query->get();

  }

  function throw_erros($var) {
    throw new ExceptionError('Provide a valid' . $var );
  }

  // Update settings field row
  // todocharti merge $option_id and $option_name in one paramter
  function update_settings_field( $option_id, $option_name, $option_value  ) {

    $data_value = array('option_value' => $option_value);

    if ( $option_id ){
      $query = DB::table('wp_settings')->where('option_id', '=', $option_id)->update($data_value);
    }

    if ( $option_name ) {
      $query = DB::table('wp_settings')->where('option_name', '=', $option_name)->update($data_value); 
    }

    return $query; 
  }

  /**
   * @return \Fluent\QueryBuilder\QueryBuilderHandler
   */
  function Fluent() {
    static $Fluent;

    if (! $Fluent) {
        global $wpdb;

        $connection = new WpFluent\Connection($wpdb, ['prefix' => ''], 'DB');

        $Fluent = new \WpFluent\QueryBuilder\QueryBuilderHandler($connection);
    }

    return $Fluent;
  }

  /**
   *  Connect to the database
   *  @since 0.1 
   */
  // private function database_connection() {
  //   global $wpdb;
    
  //   $prefix = ($this->db_prefix) ? $wpdb->prefix : '' ;

  //   new \WpFluent\Connection($wpdb, ['prefix' => $prefix], 'DB');

  // }

}

