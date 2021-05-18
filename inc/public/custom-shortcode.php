<?php
class Tutis_Custom_Shortcode {

  public function __construct() {
    $this->register_shortcodes();
  }

  public function tutis_enrollment_form( $atts ) {
    $atts = shortcode_atts(
      array(
        'program' => '',
        'course_name' => '',
        'state' => '',
        'location' => '',
        'date_range' => '',
      ), 

    $atts, 'tutis_enrollment_form' );

    $program = $atts['program'];
    $course_name = $atts['course_name'];
    $state = $atts['state'];
    $location = $atts['location'];
    $date_range = $atts['date_range'];

    $auto_class = '';
    if( $program || $course_name || $state || $location || $location ) {
      $auto_class = 'autosubmit1';
    }

    $attribute_range = '';
    if( $date_range ) {
      $attribute_range = 'daterange="' . $date_range . '"';
    }

    if( isset( $_GET['temp_id'] ) ) {
      $random_id = $_GET['temp_id'];
    }
    else {
      $random_id = md5(microtime(true).mt_Rand());
    }
    $obj_id = get_queried_object_id();

    ob_start();
      if(isset( $_GET['np'] ) ) {
        if(isset( $_GET['course_id'] ) && isset( $_GET['temp_id'] ) && $_GET['np'] == '2' ) {
          include( plugin_dir_path( __FILE__ ) . 'templates/multi-step.php');
        }
      }
      else { 
        include( plugin_dir_path( __FILE__ ) . 'templates/form.php');
      }
    return ob_get_clean();
  }

  public function register_shortcodes() {
    add_shortcode( 'tutis_enrollment_form', array( $this, 'tutis_enrollment_form' ) );
  }

}

new Tutis_Custom_Shortcode;
?>