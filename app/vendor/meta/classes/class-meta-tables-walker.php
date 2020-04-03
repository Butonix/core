<?php

function ChartiFluent() {
  $charti_meta_tables = new charti_meta_tables();
  return $charti_meta_tables;
}

function insert_meta_row($args, $meta_key, $meta_value, $meta_table, $db_prefix) {
  $row = ChartiFluent()->insert_meta_row($args, $meta_key, $meta_value, $meta_table, $db_prefix);
  return (array) $row;
}
// get_meta_row( int $meta_key_id, int $entry_id, string $meta_table, string $db_prefix )
function get_meta_row($meta_key_id, $entry_id = null, $meta_table, $db_prefix = '') {
  $row = ChartiFluent()->get_meta_row($meta_key_id, $entry_id, $meta_table, $db_prefix);
  return $row;
}

// update_post_meta( int $post_id, string $meta_key, mixed $meta_value, mixed $prev_value = '' )
function update_meta_row($meta_key_id, $meta_table, $meta_value) {
  $data = ChartiFluent()->update_meta_row($meta_key_id, $meta_table, $meta_value);
  if ($data) {
    return (array) $data;
  }
}

// update_post_meta( int $post_id, string $meta_key, mixed $meta_value, mixed $prev_value = '' )
function delete_that_meta($meta_key_id, $meta_table, $db_prefix) {
  $data = ChartiFluent()->delete_meta_row($meta_key_id, $meta_table, $db_prefix);
  return (array) $data;
}

function get_settings_field( $option_id, $option_name ) {
  $data = ChartiFluent()->get_settings_field($option_id, $option_name);

  if ( ! $data) {
    return false;
  }

  if ( is_object($data) || is_array($data) ) {

    return json_decode(json_encode($data[0]), true);

  } else {

    return json_decode(json_encode($data), true);

    //return $data;

  }

}

function update_settings_field( $option_id = null, $option_name, $option_value = null ) {
  $data = ChartiFluent()->update_settings_field($option_id, $option_name, $option_value);

  if ( ! $data) {
    return false;
  }

  if ( is_object($data) || is_array($data) ) {

    return json_decode(json_encode($data[0]), true);

  } else {

    return json_decode(json_encode($data), true);

  }

}


// alternative to get_posts
// function get_entries($args) {
//   $data = ChartiFluent()->get_entries_handler($meta_key_id, $meta_table, $meta_value);
//   return (array) $data;
// }


