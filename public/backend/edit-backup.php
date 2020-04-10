<?php
/**
 * Edit Posts Administration Screen.
 *
 * @package WordPress
 * @subpackage Administration
 */

/** WordPress Administration Bootstrap */
require_once __DIR__ . '/admin.php';

use Core\Bootstrap\BootstrapCore as BootstrapWrapper;

use Core\ErrorHandler\ErrorHandler as Error;

use Core\PermissionHandler\CurrentUserCant as CurrentUserCant;

//throw new CurrentUserCant('import', 'Sorry, you are not allowed to import content into this site.');

/**
 * $pagenow is set in vars.php
 * $wp_importers is sometimes set in ADMIN_DIR/includes/import.php
 * The remaining variables are imported as globals elsewhere, declared as globals here
 *
 * @global string $pagenow
 * @global array  $wp_importers
 * @global string $hook_suffix
 * @global string $plugin_page
 * @global string $typenow
 * @global string $taxnow
 */

global $pagenow, $wp_importers, $hook_suffix, $plugin_page, $typenow,
       $taxnow, $post_type, $post_type_object;

$Core_Global_Values = [

  'page_now' => $pagenow,
  
  'post_type' => $post_type,

  'wp_importers' => $wp_importers,
  
  'hook_suffix' => $hook_suffix,
  
  'plugin_page' => $plugin_page,
  
  'type_now' => $typenow,
  
  'tax_now' => $taxnow

];

$bootstrap = new BootstrapWrapper($Core_Global_Values);

// /var_dump();

//die;


/**
 *  Charti CMS
 *  Rewriting post functionality with OOP in mind
 *  @since 0.1
 */

class Core_Edit_Post_Type
{
  // Return the current Page
  public $typenow;
  
  // Returns Current Post Type based on $typenow
  public $post_type;
  
  // Returns Current Post Type Object
  public $post_type_object;
  
  // Returns list table UI
  public $wp_list_table;
  
  // Returns page number / Pagination
  public $pagenum;
  
  /* Defining the Post Type */
  public $parent_file, $submenu_file, $post_new_file;
  
  // Returns Bulk Actions
  public $doaction;

  // Load default scripts
  public $enqueue_scripts = ['inline-edit-post', 'heartbeat'];
  
  function __construct( $globals )
  {

    $this->typenow = 'page'; // to be continued

    $this->before_render__handler();
    
    // Require the WP_Posts_List_Table Class for creating the listing view
    $this->wp_list_table = _get_list_table( 'WP_Posts_List_Table' );
    
    // Pagination
    $this->pagenum = $this->wp_list_table->get_pagenum();

    $this->post_type_construct_links();

    // Enqueue Scripts
    $this->enqueue_scripts($this->enqueue_scripts);

  }

  private function before_render__handler() {

    // bail if the post type dosen't exist
    if ( ! $this->typenow  || ! $this->post_type_object() ) {
      
      new Error('Invalid Post Type');

    }
    
    /*
      Fire when accessed post type is not meant to be for public
      Or when the current user dosen't have enough permissions
    */
    $get_post_types = get_post_types(array('show_ui' => true));

    $edit_post_type_cap = $this->post_type_object()->cap->edit_posts;

    if ( ! in_array( $this->typenow, $get_post_types) || ! current_user_can( $edit_post_type_cap ) ) {

      new Error('You are not allowed to edit posts in this post type.');

    }

  }


  public function post_type_construct_links() {

    if ( 'post' !== $this->post_type ) {

      $this->parent_file   = "edit.php?post_type=$this->post_type";

      $this->submenu_file  = "edit.php?post_type=$this->post_type";

      $this->post_new_file = "post-new.php?post_type=$this->post_type";

    } else {

      $this->parent_file  = 'edit.php';

      $this->submenu_file  = 'edit.php';

      $this->post_new_file = 'post-new.php';

    }

    $this->post_type_construct__do_action();

  }

  /*
    This method will handle all bulk actions
  */
  private function post_type_construct__do_action() {
    // is there any action on edit page?
    $this->doaction = $this->wp_list_table->current_action();

    // Fires on action
    if ( $this->doaction ) {
      $this->post_type_construct__do_action_bulk();
    }

  }

  private function post_type_construct__do_action_bulk() {
    // provide a nonce during bulk proccess
    check_admin_referer( 'bulk-posts' );

    $sendback = remove_query_arg( array( 'trashed', 'untrashed', 'deleted', 'locked', 'ids' ), wp_get_referer() );
    
    if ( ! $sendback ) {
      $sendback = admin_url( $this->parent_file );
    }

    $sendback = add_query_arg( 'paged', $this->pagenum, $sendback );

    if ( strpos( $sendback, 'post.php' ) !== false ) {
      $sendback = admin_url( $post_new_file );
    }

    if ( 'delete_all' === $this->doaction ) {
      // Prepare for deletion of all posts with a specified post status (i.e. Empty Trash).
      $post_status = preg_replace( '/[^a-z0-9_-]+/i', '', $_REQUEST['post_status'] );
      
      // Validate the post status exists.
      if ( get_post_status_object( $post_status ) ) {
        $post_ids = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type=%s AND post_status = %s", $post_type, $post_status ) );
      }
      
      $this->doaction = 'delete';

    } elseif ( isset( $_REQUEST['media'] ) ) {

      $post_ids = $_REQUEST['media'];

    } elseif ( isset( $_REQUEST['ids'] ) ) {

      $post_ids = explode( ',', $_REQUEST['ids'] );

    } elseif ( ! empty( $_REQUEST['post'] ) ) {

      $post_ids = array_map( 'intval', $_REQUEST['post'] );

    }

    if ( ! isset( $post_ids ) ) {

      wp_redirect( $sendback );
      exit;

    }

    $this->do_action__bulk_switcher();

  }

  /*
    The Switcher will be triggered on action (trash, untrash, delete, edit default)
  */
  public function do_action__bulk_switcher() {
    switch ( $this->doaction ) {
      case 'trash':
        $this->do_action__bulk_case_handler__trash();
      break;

      case 'untrash':
        $this->do_action__bulk_case_handler__untrash();
      break;

      case 'delete':
        $this->do_action__bulk_case_handler__delete();
      break;

      case 'edit':
      die;
        $this->do_action__bulk_case_handler__edit();
      break;

      default:
        $this->do_action__bulk_case_handler__default();
      break;
    }
  }

  /**
   * Do action - Bulk Trash Handler
   */
  protected function do_action__bulk_case_handler__trash() {
    $trashed = 0;
    $locked  = 0;

    foreach ( (array) $post_ids as $post_id ) {

      if ( ! current_user_can( 'delete_post', $post_id ) ) {
        new Error(__( 'Sorry, you are not allowed to move this item to the Trash.' ));
      }

      if ( wp_check_post_lock( $post_id ) ) {
        $locked++;
        continue;
      }

      if ( ! wp_trash_post( $post_id ) ) {
        new Error(__( 'Error in moving to Trash.' ));
      }

      $trashed++;
    }

    $sendback = add_query_arg(
      array(
        'trashed' => $trashed,
        'ids'     => join( ',', $post_ids ),
        'locked'  => $locked,
      ),
      $sendback
    );
  }

  /**
   * Do action - Bulk Untrash Handler
   */
  protected function do_action__bulk_case_handler__untrash() {
    
    $untrashed = 0;

    foreach ( (array) $post_ids as $post_id ) {

      if ( ! current_user_can( 'delete_post', $post_id ) ) {
        new Error(__( 'Sorry, you are not allowed to restore this item from the Trash.' ));
      }

      if ( ! wp_untrash_post( $post_id ) ) {
        new Error( __( 'Error in restoring from Trash.' ) );
      }

      $untrashed++;
    }

    $sendback = add_query_arg( 'untrashed', $untrashed, $sendback );
  
  }

  /**
   * Do action - Bulk Edit Handler
   */
  protected function do_action__bulk_case_handler__edit() {

      if ( isset( $_REQUEST['bulk_edit'] ) ) {

        $done = bulk_edit_posts( $_REQUEST );

        if ( is_array( $done ) ) {
          $done['updated'] = count( $done['updated'] );
          $done['skipped'] = count( $done['skipped'] );
          $done['locked']  = count( $done['locked'] );
          $sendback        = add_query_arg( $done, $sendback );
        }
      }

  }

  /**
   * Enqueue scripts & styles
   */
  public function enqueue_scripts( $scripts ) {
    
    foreach ($scripts as $script_line) {
    
      wp_enqueue_script( $script_line );  
    
    }

  }

  /**
   * This two methods are coming together
   * @global string       $post_type
   * @global WP_Post_Type $post_type_object
   */
  function post_type_object() {
    $this->post_type_object = get_post_type_object( $this->post_type() );

    return $this->post_type_object;
  
  }

  function post_type() {

    $this->post_type = $this->typenow;

    return $this->post_type;
  
  }

}

new Core_Edit_Post_Type($Core_Global_Values);



if ( 'attachment' === $typenow ) {
  if ( wp_redirect( admin_url( 'upload.php' ) ) ) {
    exit;
  }
}

/**
 * @global string       $post_type
 * @global WP_Post_Type $post_type_object
 */
global $post_type, $post_type_object;

//var_dump($post_type); die;

$post_type        = $typenow;
$post_type_object = get_post_type_object( $post_type );



$wp_list_table = _get_list_table( 'WP_Posts_List_Table' );
$pagenum       = $wp_list_table->get_pagenum();



if ( 'post' !== $post_type ) {
  $parent_file   = "edit.php?post_type=$post_type";
  $submenu_file  = "edit.php?post_type=$post_type";
  $post_new_file = "post-new.php?post_type=$post_type";
} else {
  $parent_file   = 'edit.php';
  $submenu_file  = 'edit.php';
  $post_new_file = 'post-new.php';
}

$doaction = $wp_list_table->current_action();

//var_dump($wp_list_table);

if ( $doaction ) {
  check_admin_referer( 'bulk-posts' );

  $sendback = remove_query_arg( array( 'trashed', 'untrashed', 'deleted', 'locked', 'ids' ), wp_get_referer() );
  if ( ! $sendback ) {
    $sendback = admin_url( $parent_file );
  }
  $sendback = add_query_arg( 'paged', $pagenum, $sendback );
  if ( strpos( $sendback, 'post.php' ) !== false ) {
    $sendback = admin_url( $post_new_file );
  }

  if ( 'delete_all' === $doaction ) {
    // Prepare for deletion of all posts with a specified post status (i.e. Empty Trash).
    $post_status = preg_replace( '/[^a-z0-9_-]+/i', '', $_REQUEST['post_status'] );
    // Validate the post status exists.
    if ( get_post_status_object( $post_status ) ) {
      $post_ids = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_type=%s AND post_status = %s", $post_type, $post_status ) );
    }
    $doaction = 'delete';
  } elseif ( isset( $_REQUEST['media'] ) ) {
    $post_ids = $_REQUEST['media'];
  } elseif ( isset( $_REQUEST['ids'] ) ) {
    $post_ids = explode( ',', $_REQUEST['ids'] );
  } elseif ( ! empty( $_REQUEST['post'] ) ) {
    $post_ids = array_map( 'intval', $_REQUEST['post'] );
  }

  if ( ! isset( $post_ids ) ) {
    wp_redirect( $sendback );
    exit;
  }

  switch ( $doaction ) {
    case 'trash':
      $trashed = 0;
      $locked  = 0;

      foreach ( (array) $post_ids as $post_id ) {

        //var_dump($post_id);
        die;
        if ( ! current_user_can( 'delete_post', $post_id ) ) {
          wp_die( __( 'Sorry, you are not allowed to move this item to the Trash.' ) );
        }

        if ( wp_check_post_lock( $post_id ) ) {
          $locked++;
          continue;
        }

        if ( ! wp_trash_post( $post_id ) ) {
          wp_die( __( 'Error in moving to Trash.' ) );
        }

        $trashed++;
      }

      $sendback = add_query_arg(
        array(
          'trashed' => $trashed,
          'ids'     => join( ',', $post_ids ),
          'locked'  => $locked,
        ),
        $sendback
      );
      break;
    case 'untrash':
      $untrashed = 0;
      foreach ( (array) $post_ids as $post_id ) {
        if ( ! current_user_can( 'delete_post', $post_id ) ) {
          wp_die( __( 'Sorry, you are not allowed to restore this item from the Trash.' ) );
        }

        if ( ! wp_untrash_post( $post_id ) ) {
          wp_die( __( 'Error in restoring from Trash.' ) );
        }

        $untrashed++;
      }

      $sendback = add_query_arg( 'untrashed', $untrashed, $sendback );
      break;
    case 'delete':
      $deleted = 0;


//      var_dump($deleted);

      foreach ( (array) $post_ids as $post_id ) {
        $post_del = get_post( $post_id );

        if ( ! current_user_can( 'delete_post', $post_id ) ) {
          wp_die( __( 'Sorry, you are not allowed to delete this item.' ) );
        }

        if ( 'attachment' === $post_del->post_type ) {
          if ( ! wp_delete_attachment( $post_id ) ) {
            wp_die( __( 'Error in deleting.' ) );
          }
        } else {
          if ( ! wp_delete_post( $post_id ) ) {
            wp_die( __( 'Error in deleting.' ) );
          }
        }
        $deleted++;
      }
      $sendback = add_query_arg( 'deleted', $deleted, $sendback );
      break;
    case 'edit':
      if ( isset( $_REQUEST['bulk_edit'] ) ) {
        $done = bulk_edit_posts( $_REQUEST );

        if ( is_array( $done ) ) {
          $done['updated'] = count( $done['updated'] );
          $done['skipped'] = count( $done['skipped'] );
          $done['locked']  = count( $done['locked'] );
          $sendback        = add_query_arg( $done, $sendback );
        }
      }
      break;
    default:
      $screen = get_current_screen()->id;

      /**
       * Fires when a custom bulk action should be handled.
       *
       * The redirect link should be modified with success or failure feedback
       * from the action to be used to display feedback to the user.
       *
       * The dynamic portion of the hook name, `$screen`, refers to the current screen ID.
       *
       * @since 4.7.0
       *
       * @param string $sendback The redirect URL.
       * @param string $doaction The action being taken.
       * @param array  $items    The items to take the action on. Accepts an array of IDs of posts,
       *                         comments, terms, links, plugins, attachments, or users.
       */
      $sendback = apply_filters( "handle_bulk_actions-{$screen}", $sendback, $doaction, $post_ids ); // phpcs:ignore WordPress.NamingConventions.ValidHookName.UseUnderscores
      break;
  }

  $sendback = remove_query_arg( array( 'action', 'action2', 'tags_input', 'post_author', 'comment_status', 'ping_status', '_status', 'post', 'bulk_edit', 'post_view' ), $sendback );

  wp_redirect( $sendback );
  exit();
} elseif ( ! empty( $_REQUEST['_wp_http_referer'] ) ) {
  wp_redirect( remove_query_arg( array( '_wp_http_referer', '_wpnonce' ), wp_unslash( $_SERVER['REQUEST_URI'] ) ) );
  exit;
}

$wp_list_table->prepare_items();


if ( 'wp_block' === $post_type ) {
  wp_enqueue_script( 'wp-list-reusable-blocks' );
  wp_enqueue_style( 'wp-list-reusable-blocks' );
}

$title = $post_type_object->labels->name;


get_current_screen()->set_screen_reader_content(
  array(
    'heading_views'      => $post_type_object->labels->filter_items_list,
    'heading_pagination' => $post_type_object->labels->items_list_navigation,
    'heading_list'       => $post_type_object->labels->items_list,
  )
);

add_screen_option(
  'per_page',
  array(
    'default' => 20,
    'option'  => 'edit_' . $post_type . '_per_page',
  )
);

$bulk_counts = array(
  'updated'   => isset( $_REQUEST['updated'] ) ? absint( $_REQUEST['updated'] ) : 0,
  'locked'    => isset( $_REQUEST['locked'] ) ? absint( $_REQUEST['locked'] ) : 0,
  'deleted'   => isset( $_REQUEST['deleted'] ) ? absint( $_REQUEST['deleted'] ) : 0,
  'trashed'   => isset( $_REQUEST['trashed'] ) ? absint( $_REQUEST['trashed'] ) : 0,
  'untrashed' => isset( $_REQUEST['untrashed'] ) ? absint( $_REQUEST['untrashed'] ) : 0,
);

$bulk_messages             = array();
$bulk_messages['post']     = array(
  /* translators: %s: Number of posts. */
  'updated'   => _n( '%s post updated.', '%s posts updated.', $bulk_counts['updated'] ),
  'locked'    => ( 1 == $bulk_counts['locked'] ) ? __( '1 post not updated, somebody is editing it.' ) :
          /* translators: %s: Number of posts. */
          _n( '%s post not updated, somebody is editing it.', '%s posts not updated, somebody is editing them.', $bulk_counts['locked'] ),
  /* translators: %s: Number of posts. */
  'deleted'   => _n( '%s post permanently deleted.', '%s posts permanently deleted.', $bulk_counts['deleted'] ),
  /* translators: %s: Number of posts. */
  'trashed'   => _n( '%s post moved to the Trash.', '%s posts moved to the Trash.', $bulk_counts['trashed'] ),
  /* translators: %s: Number of posts. */
  'untrashed' => _n( '%s post restored from the Trash.', '%s posts restored from the Trash.', $bulk_counts['untrashed'] ),
);
$bulk_messages['page']     = array(
  /* translators: %s: Number of pages. */
  'updated'   => _n( '%s page updated.', '%s pages updated.', $bulk_counts['updated'] ),
  'locked'    => ( 1 == $bulk_counts['locked'] ) ? __( '1 page not updated, somebody is editing it.' ) :
          /* translators: %s: Number of pages. */
          _n( '%s page not updated, somebody is editing it.', '%s pages not updated, somebody is editing them.', $bulk_counts['locked'] ),
  /* translators: %s: Number of pages. */
  'deleted'   => _n( '%s page permanently deleted.', '%s pages permanently deleted.', $bulk_counts['deleted'] ),
  /* translators: %s: Number of pages. */
  'trashed'   => _n( '%s page moved to the Trash.', '%s pages moved to the Trash.', $bulk_counts['trashed'] ),
  /* translators: %s: Number of pages. */
  'untrashed' => _n( '%s page restored from the Trash.', '%s pages restored from the Trash.', $bulk_counts['untrashed'] ),
);
$bulk_messages['wp_block'] = array(
  /* translators: %s: Number of blocks. */
  'updated'   => _n( '%s block updated.', '%s blocks updated.', $bulk_counts['updated'] ),
  'locked'    => ( 1 == $bulk_counts['locked'] ) ? __( '1 block not updated, somebody is editing it.' ) :
          /* translators: %s: Number of blocks. */
          _n( '%s block not updated, somebody is editing it.', '%s blocks not updated, somebody is editing them.', $bulk_counts['locked'] ),
  /* translators: %s: Number of blocks. */
  'deleted'   => _n( '%s block permanently deleted.', '%s blocks permanently deleted.', $bulk_counts['deleted'] ),
  /* translators: %s: Number of blocks. */
  'trashed'   => _n( '%s block moved to the Trash.', '%s blocks moved to the Trash.', $bulk_counts['trashed'] ),
  /* translators: %s: Number of blocks. */
  'untrashed' => _n( '%s block restored from the Trash.', '%s blocks restored from the Trash.', $bulk_counts['untrashed'] ),
);

/**
 * Filters the bulk action updated messages.
 *
 * By default, custom post types use the messages for the 'post' post type.
 *
 * @since 3.7.0
 *
 * @param array[] $bulk_messages Arrays of messages, each keyed by the corresponding post type. Messages are
 *                               keyed with 'updated', 'locked', 'deleted', 'trashed', and 'untrashed'.
 * @param int[]   $bulk_counts   Array of item counts for each message, used to build internationalized strings.
 */
$bulk_messages = apply_filters( 'bulk_post_updated_messages', $bulk_messages, $bulk_counts );
$bulk_counts   = array_filter( $bulk_counts );

require_once ABSPATH . ADMIN_DIR . '/admin-header.php';
?>
<div class="wrap">
<h1 class="wp-heading-inline">
<?php
echo esc_html( $post_type_object->labels->name );
?>
</h1>

<?php
if ( current_user_can( $post_type_object->cap->create_posts ) ) {
  echo ' <a href="' . esc_url( admin_url( $post_new_file ) ) . '" class="page-title-action">' . esc_html( $post_type_object->labels->add_new ) . '</a>';
}

if ( isset( $_REQUEST['s'] ) && strlen( $_REQUEST['s'] ) ) {
  /* translators: %s: Search query. */
  printf( ' <span class="subtitle">' . __( 'Search results for &#8220;%s&#8221;' ) . '</span>', get_search_query() );
}
?>

<hr class="wp-header-end">

<?php
// If we have a bulk message to issue:
$messages = array();
foreach ( $bulk_counts as $message => $count ) {
  if ( isset( $bulk_messages[ $post_type ][ $message ] ) ) {
    $messages[] = sprintf( $bulk_messages[ $post_type ][ $message ], number_format_i18n( $count ) );
  } elseif ( isset( $bulk_messages['post'][ $message ] ) ) {
    $messages[] = sprintf( $bulk_messages['post'][ $message ], number_format_i18n( $count ) );
  }

  if ( 'trashed' === $message && isset( $_REQUEST['ids'] ) ) {
    $ids        = preg_replace( '/[^0-9,]/', '', $_REQUEST['ids'] );
    $messages[] = '<a href="' . esc_url( wp_nonce_url( "edit.php?post_type=$post_type&doaction=undo&action=untrash&ids=$ids", 'bulk-posts' ) ) . '">' . __( 'Undo' ) . '</a>';
  }
}

if ( $messages ) {
  echo '<div id="message" class="updated notice is-dismissible"><p>' . join( ' ', $messages ) . '</p></div>';
}
unset( $messages );

$_SERVER['REQUEST_URI'] = remove_query_arg( array( 'locked', 'skipped', 'updated', 'deleted', 'trashed', 'untrashed' ), $_SERVER['REQUEST_URI'] );
?>

<?php $wp_list_table->views(); ?>

<form id="posts-filter" method="get">

  <?php $wp_list_table->search_box( $post_type_object->labels->search_items, 'post' ); ?>

  <input type="hidden" name="post_status" class="post_status_page" value="<?php echo ! empty( $_REQUEST['post_status'] ) ? esc_attr( $_REQUEST['post_status'] ) : 'all'; ?>" />
  <input type="hidden" name="post_type" class="post_type_page" value="<?php echo $post_type; ?>" />

  <?php if ( ! empty( $_REQUEST['author'] ) ) { ?>
  <input type="hidden" name="author" value="<?php echo esc_attr( $_REQUEST['author'] ); ?>" />
  <?php } ?>

  <?php if ( ! empty( $_REQUEST['show_sticky'] ) ) { ?>
  <input type="hidden" name="show_sticky" value="1" />
  <?php } ?>

  <?php $wp_list_table->display(); ?>

</form>

<?php
if ( $wp_list_table->has_items() ) {
  $wp_list_table->inline_edit();
}
?>

<div id="ajax-response"></div>
<br class="clear" />
</div>

<?php
require_once ABSPATH . ADMIN_DIR . '/admin-footer.php';
