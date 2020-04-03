<?php

namespace Rareloop\Router;

defined( 'ABSPATH' ) || exit;

class RareMenuBox {

  private $endpoints;

  /**
   * Hook in tabs.
   */
  public function __construct(array $endpoints) {
    
    if(!is_admin()) {
      return;
    }

    $this->endpoints = $endpoints;
    //echo "<pre>";
    //var_dump($endpoints);

    // Add endpoints custom URLs in Appearance > Menus > Pages.
    add_action( 'admin_head-nav-menus.php', array( $this, 'add_nav_menu_meta_boxes' ) );

    //var_dump($this->getFirstName());

  }
  /**
   * Add custom nav meta box.
   *
   * Adapted from http://www.johnmorrisonline.com/how-to-add-a-fully-functional-custom-meta-box-to-wordpress-navigation-menus/.
   */
  public function add_nav_menu_meta_boxes() {
    $id = 'dynamic_routes_meta_box';
    $title = __( 'Dynamic Routes' );

    add_meta_box($id, $title, array($this, 'nav_menu_links'), 'nav-menus', 'side', 'low');
  }

  /**
   * Output menu links.
   */
  function nav_menu_links() {

    //var_dump($endpoints);
    $RegisteredEndpoints = json_decode(json_encode($this->endpoints), true);


    ?>
    <div id="posttype-dynamic-endpoints" class="posttypediv">
      <div id="tabs-panel-dynamic-endpoints" class="tabs-panel tabs-panel-active">
        <ul id="dynamic-endpoints-checklist" class="categorychecklist form-no-clear">
          <?php
          $i = -1;
          foreach ( $RegisteredEndpoints as $key => $value ) :
            $url = get_bloginfo('url') . '/' . $value['uri'];
            ?>
            <li>
              <label class="menu-item-title">
                <input type="checkbox" class="menu-item-checkbox" name="menu-item[<?php echo esc_attr( $i ); ?>][menu-item-object-id]" value="<?php echo esc_attr( $i ); ?>" /> <?php echo esc_html( $value['name'] ); ?>
              </label>
              <input type="hidden" class="menu-item-type" name="menu-item[<?php echo esc_attr( $i ); ?>][menu-item-type]" value="custom" />
              <input type="hidden" class="menu-item-title" name="menu-item[<?php echo esc_attr( $i ); ?>][menu-item-title]" value="<?php echo esc_attr( $value['name'] ); ?>" />
              <input type="hidden" class="menu-item-url" name="menu-item[<?php echo esc_attr( $i ); ?>][menu-item-url]" value="<?php echo esc_url( $url ); ?>" />
              <input type="hidden" class="menu-item-classes" name="menu-item[<?php echo esc_attr( $i ); ?>][menu-item-classes]" />
            </li>
            <?php
            //$i--;
          endforeach;
          ?>
        </ul>
      </div>
      <p class="button-controls">
        <span class="list-controls">
          <a href="<?php echo esc_url( admin_url( 'nav-menus.php?page-tab=all&selectall=1#posttype-dynamic-endpoints' ) ); ?>" class="select-all"><?php esc_html_e( 'Select all' ); ?></a>
        </span>
        <span class="add-to-menu">
          <button type="submit" class="button-secondary submit-add-to-menu right" value="<?php esc_attr_e( 'Add to menu' ); ?>" name="add-post-type-menu-item" id="submit-posttype-dynamic-endpoints"><?php esc_html_e( 'Add to menu' ); ?></button>
          <span class="spinner"></span>
        </span>
      </p>
    </div>
    <?php
  }
}
//return new RareMenuBox;