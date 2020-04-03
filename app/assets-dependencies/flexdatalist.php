<?php


function flexdatalist() {

  if (get_raw()->screen_page() == 'edit.php') {

    $scripts = array(
        "datalist.min" => "flexdatalist/jquery.flexdatalist.min.js",
        "datalist.function" => "flexdatalist/front.js"
    );

    foreach ( $scripts as $id => $script ):
        wp_enqueue_script( 'flex-'.$id, '/' . ADMIN_ASSETS .'/vendor/'. $script, true );
    endforeach;

    $styles = array(
        "datalist.min" => "flexdatalist/jquery.flexdatalist.min.css",
    );

    foreach ( $styles as $id => $style ):
        wp_enqueue_style( 'flex-'.$id, '/' . ADMIN_ASSETS .'/vendor/'. $style, true );
    endforeach;
  }
}