<?php

class Field {

  public $saved_fields;
  
  public $field_value;
  
  private $field_stored_value = array();

  public $set_group_name;


  public function init($QueryFieldsResults) {

    // Look for database stored values
    if ( $QueryFieldsResults ) {
      $this->get_name_field($QueryFieldsResults);
    }
  }

  public function get_name_field($data_value) {
    $this->field_value = $data_value;
  }

  function Input($type, $name, $input_name, $label = null, $helper = null) {

    $this->type( $type, $name, $input_name, $label, $helper);

  }

  function type($type, $name, $input_name, $label, $helper, $data = null) {
    $this->Render_HTML_Label($name);
    $this->Render_HTML_Field($type, $name, $input_name, $label, $data);
    $this->Render_HELPER_Label($helper);

  }

  function Render_HTML_Field($type, $name, $input_name, $field_data_value) {
    // here are some input types available for <input> tag
    $input_types = array('text', 'password', 'email');

    $get_settings_field = get_settings_field('', $input_name);
    
    $__field_value = $get_settings_field['option_value'];

    if( in_array($type, $input_types) ) {
      /*
        Hasing the password fields with a native PHP function instead of wp ?
        @see https://www.php.net/manual/en/function.password-hash.php#124138
        @see https://developer.wordpress.org/reference/functions/wp_hash_password/
      */
      if( $type == 'password') {
        
        //$__pwd_hashed = wp_has_password($__pwd_peppered);


        echo '<input group="" value="'. $__field_value .'" name="' . $input_name . '" class="form-control" type="' . $type . '">';

      } else {

        echo '<input group="" value="'. $__field_value .'" name="' . $input_name . '" class="form-control" type="' . $type . '">';
      }

    } elseif( $type == 'textarea' ) {

      echo '<textarea value="'. $__field_value .'" name="' . $input_name . '" class="form-control"></textarea>';

    }

  }

  private function Render_HTML_Label($label){
    
    echo '<label>' . $label . '</label>';

  }

  private function Render_HELPER_Label($helper) {
    if( $helper ):
    
      echo '<small class="d-block mt-1 text-muted">
      <i class="gg-info float-left d-inline-block" style="--ggs:0.6"></i><span>' . $helper . '</span></small>' ;

    endif;
  }


   // there was an idea to autogenerate the from names.. to be implemented

  // private function fieldNAMEGenerator($label, $length = 7)
  // {
  //   $characters   = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  //   $randomString = '';

  //   for ( $i = 0; $i < $length; $i ++ ) {
  //     $randomString .= $characters[ rand( 0, strlen( $characters ) - 1 ) ];
  //   }

  //   $field_name = str_replace(' ', '_', strtolower($label)) . '_' . $randomString;
    
  //   return $field_name;
  // }
}
