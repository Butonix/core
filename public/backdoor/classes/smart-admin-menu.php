<?php 

/**
  * Charti CMS
  *
  * @since 0.1
  *
**/


class DashboardMenu {

  private $endpoints;

  /**
   * Hook in tabs.
   */
  public function __construct() {
    
    if(!is_admin()) {
      return;
    }

    $endpoints = array(
      // Dashboard Root Links
      array(
        'icon' => '',
        'uri' => admin_url('index.php', $scheme = 'admin' ),
        'name' => 'Dashboard',
        'children' =>
          array(
            array(
              'uri' => admin_url( 'index.php', $scheme = 'admin' ),
              'name' => 'Home'
            ),
            array(
              'uri' => admin_url( 'update-core.php', $scheme = 'admin' ),
              'name' => 'Updates'
            )
          )
      ),

      array(
        'uri' => admin_url('edit.php', $scheme = 'admin' ),
        'name' => 'Posts',
        'children' => false
      ),
      array(
        'uri' => admin_url('edit.php', $scheme = 'admin' ),
        'name' => 'Posts',
        'children' => false
      )
    );
      

      // print all children
      echo '<ul>';
      foreach ($endpoints as $key => $endpoint) {
          //echo $key . ' : ' . $endpoint . "<br>";
          if( is_array($endpoint) ) {

            //var_dump($endpoint);
              echo '<li>';
              echo '<a href="'.$endpoint['uri'].'">'.$endpoint['name'].'</a>';

                // check for subpages
                if( $endpoint['children'] ) {
                  echo '<ul>';
                  foreach( $endpoint['children'] as $key => $point ) {
                    echo '<li>';
                    echo $point['uri'];
                    echo '</li>';
                  }
                  echo '</ul>';
                }

              echo '</li>';
              // foreach($endpoint as $key => $point) {
              //   //echo $key . $endpoint['uri'] . '<br>';
              // }
          }
      }
      echo '</ul>';

    // echo '<pre>';
    // var_dump($iterator);

    $this->endpoints = $endpoints;

    // Add endpoints custom URLs in Appearance > Menus > Pages.
    add_action( 'admin_head-nav-menus.php', array( $this, 'add_nav_menu_meta_boxes' ) );

  }
  /**
   * Add custom nav meta box.
   *
   * Adapted from http://www.johnmorrisonline.com/how-to-add-a-fully-functional-custom-meta-box-to-wordpress-navigation-menus/.
   */
  public function add_nav_menu_meta_boxes() {
    $id = 'admin_dashboard_menu';
    $title = __( 'Dashboard Menu' );

    add_meta_box($id, $title, array($this, 'nav_menu_links'), 'nav-menus', 'side', 'high');
  }

  /**
   * Output menu links.
   */
  function nav_menu_links() {

    //var_dump($endpoints);
    $RegisteredEndpoints = $this->endpoints;

    ?>
    <div id="posttype-dashboard-admin-menu" class="posttypediv">
      <div id="tabs-panel-dashboard-admin-menu" class="tabs-panel tabs-panel-active">
        <ul id="dashboard-admin-menu-endpoints-checklist" class="categorychecklist form-no-clear">
          <?php
          $i = -1;
          foreach ( $RegisteredEndpoints as $key => $value ) :
            $url = get_bloginfo('url') . '/' . $value['uri'];
            echo '<pre>';
            var_dump($value);
            ?>
            <li>
              <label class="menu-item-title">
                <input type="checkbox" class="menu-item-checkbox" name="menu-item[<?php echo esc_attr( $i ); ?>][menu-item-object-id]" value="<?php echo esc_attr( $i ); ?>" /> <?php echo esc_html( $value['name'] ); ?>
              </label>
              <input type="hidden" class="menu-item-type" name="menu-item[<?php echo esc_attr( $i ); ?>][menu-item-type]" value="custom" />
              <input type="hidden" class="menu-item-title" name="menu-item[<?php echo esc_attr( $i ); ?>][menu-item-title]" value="<?php echo esc_attr( $value['name'] ); ?>" />
              <input type="hidden" class="menu-item-url" name="menu-item[<?php echo esc_attr( $i ); ?>][menu-item-url]" value="<?php echo esc_url( $url ); ?>" />
              <input type="hidden" class="menu-item-classes" name="menu-item[<?php echo esc_attr( $i ); ?>][menu-item-classes]" />
              <?php foreach ($value as $test) { ?>

                <?php echo $test; ?>
              <?php } ?>
            </li>
            <?php
            //$i--;
          endforeach;
          ?>
        </ul>
      </div>
      <p class="button-controls">
        <span class="list-controls">
          <a href="<?php echo esc_url( admin_url( 'nav-menus.php?page-tab=all&selectall=1#posttype-dashboard-admin-menu' ) ); ?>" class="select-all"><?php esc_html_e( 'Select all' ); ?></a>
        </span>
        <span class="add-to-menu">
          <button type="submit" class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e( 'Add to menu' ); ?>" name="add-post-type-menu-item" id="submit-posttype-dashboard-admin-menu"><?php esc_html_e( 'Add to menu' ); ?></button>
          <span class="spinner"></span>
        </span>
      </p>
    </div>
    <?php
  }

}

new DashboardMenu();

// Register the nav menu 
// $locations = array(
//   'admin_menu'  => __( 'Dashboard'),
// );

// register_nav_menus( $locations );