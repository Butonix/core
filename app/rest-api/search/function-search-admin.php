<?php
/**
 *  Charti CMS
 *  
 *  Replace the native Wordpress search in admin dashboard with a faster solution
 *  Register a private endpoint that will be accesible just for background actions
 *  So we can call the endpoint whenever we search for a post/page/entry
 *
 *  @since 0.1
 *
 *  @param array $data Options for the function.
 *  @return string|null Post title for the latest,  * or null if none.
 */

// https://www.tychesoftwares.com/creating-custom-api-endpoints-in-the-wordpress-rest-api/
function searchable_posts( $data ) {
    // get the posts
  $posts_list = get_posts(
    array(
      'post_type' => 'post',
      'cateogry' => 0,
      'posts_per_page' => -1
    )
  );
 
  if ( empty( $posts_list ) ) {
    return null;
  }

  $post_data = array();

  foreach ($posts_list as $key => $posts) {
    $post_url = get_bloginfo('url') . '/' . ADMIN_DIR . '/post.php?post=' . $posts->ID . '&action=edit';
    $post_data;
    $post_data[$key][ 'id' ] = $posts->ID;
    $post_data[$key][ 'title' ] = $posts->post_title;
    $post_data[$key][ 'url' ] = $post_url;
    //$post_data[ $post_id ][ 'content' ] = $post_content;
  }

  wp_reset_postdata();
 
  return $post_data;
}

function register_searchable_posts() {
  register_rest_route(
    'searchable', 'posts', array(
      'methods' => 'GET',
      'callback' => 'searchable_posts',
    )
  );
}

function register_searchable_taxonomies() {
  register_rest_route(
    'searchable', 'posts', array(
      'methods' => 'GET',
      'callback' => 'searchable_taxonomies',
    )
  );
}

if(is_user_logged_in()) {
  add_action( 'rest_api_init', 'register_searchable_posts');
  //add_action( 'rest_api_init', 'register_searchable_taxonomies');
}
