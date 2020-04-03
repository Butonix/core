<?php
/**
  *
  * CHARTI
  *
  * Some false functions here so WP Themes not cry
  * 
  * @since 0.1
  *
**/

function pings_open() {
  return false;
}

function comments_open( $post_id = null ) {
 
    $_post = get_post( $post_id );
 
    $post_id = $_post ? $_post->ID : 0;
    $open    = ( 'open' == $_post->comment_status );
 
    return apply_filters( 'comments_open', $open, $post_id );
}

function get_comments_number() {
  return false;
}

function get_comments() {
  return false;
}

function comments_popup_link() {
  return false;
}

function comments_template() {
  return false;
}