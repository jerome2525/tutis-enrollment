<?php
class Tutis_Post_Type {

  public function __construct() {
    $this->register_post_type();
  }

  public function tutis_create_post_type() {
    $course_labels = array(
        'name'                => _x( 'Courses', 'Post Type General Name', 'tutis' ),
        'singular_name'       => _x( 'Course', 'Post Type Singular Name', 'tutis' ),
        'menu_name'           => __( 'Courses', 'tutis' ),
        'parent_item_colon'   => __( 'Parent Courses', 'tutis' ),
        'all_items'           => __( 'All Courses', 'tutis' ),
        'view_item'           => __( 'View Courses', 'tutis' ),
        'add_new_item'        => __( 'Add New Courses', 'tutis' ),
        'add_new'             => __( 'Add New', 'tutis' ),
        'edit_item'           => __( 'Edit Courses', 'tutis' ),
        'update_item'         => __( 'Update Courses', 'tutis' ),
        'search_items'        => __( 'Search Courses', 'tutis' ),
        'not_found'           => __( 'Not Found', 'tutis' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'tutis' ),
    );
     
    $course_args = array(
        'label'               => __( 'Courses', 'tutis' ),
        'description'         => __( 'Courses', 'tutis' ),
        'labels'              => $course_labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
        'show_in_rest' => true,
        'menu_icon'           => 'dashicons-welcome-learn-more'
 
    );
     
    // Registering your Custom Post Type
    register_post_type( 'courses', $course_args );

    $enrollment_labels = array(
        'name'                => _x( 'Enrollee', 'Post Type General Name', 'tutis' ),
        'singular_name'       => _x( 'Enrollee', 'Post Type Singular Name', 'tutis' ),
        'menu_name'           => __( 'Enrollies', 'tutis' ),
        'parent_item_colon'   => __( 'Parent Enrollies', 'tutis' ),
        'all_items'           => __( 'All Enrollies', 'tutis' ),
        'view_item'           => __( 'View Enrollies', 'tutis' ),
        'add_new_item'        => __( 'Add New Enrollies', 'tutis' ),
        'add_new'             => __( 'Add New', 'tutis' ),
        'edit_item'           => __( 'Edit Enrollies', 'tutis' ),
        'update_item'         => __( 'Update Enrollies', 'tutis' ),
        'search_items'        => __( 'Search Enrollies', 'tutis' ),
        'not_found'           => __( 'Not Found', 'tutis' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'tutis' ),
    );
     
    $enrollment_args = array(
        'label'               => __( 'Enrollies', 'tutis' ),
        'description'         => __( 'Enrollies', 'tutis' ),
        'labels'              => $enrollment_labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
        'show_in_rest' => true,
        'menu_icon'           => 'dashicons-edit'
 
    );
     
    // Registering your Custom Post Type
    register_post_type( 'enrollies', $enrollment_args );

    if( get_option('tutis_secure_pay_client_id') && get_option('tutis_secure_pay_merchant_code') && get_option('tutis_secure_js_url') ) { 
      $securepay_labels = array(
          'name'                => _x( 'Securepay error', 'Post Type General Name', 'tutis' ),
          'singular_name'       => _x( 'Securepay error', 'Post Type Singular Name', 'tutis' ),
          'menu_name'           => __( 'Securepay errors', 'tutis' ),
          'parent_item_colon'   => __( 'Parent Securepay error', 'tutis' ),
          'all_items'           => __( 'All Securepay error', 'tutis' ),
          'view_item'           => __( 'View Securepay errors', 'tutis' ),
          'add_new_item'        => __( 'Add New Securepay error', 'tutis' ),
          'add_new'             => __( 'Add New', 'tutis' ),
          'edit_item'           => __( 'Edit Securepay error', 'tutis' ),
          'update_item'         => __( 'Update Securepay error', 'tutis' ),
          'search_items'        => __( 'Search Securepay error', 'tutis' ),
          'not_found'           => __( 'Not Found', 'tutis' ),
          'not_found_in_trash'  => __( 'Not found in Trash', 'tutis' ),
      );
       
      $securepay_args = array(
          'label'               => __( 'Securepay errors', 'tutis' ),
          'description'         => __( 'Securepay errors', 'tutis' ),
          'labels'              => $securepay_labels,
          // Features this CPT supports in Post Editor
          'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
          'hierarchical'        => false,
          'public'              => true,
          'show_ui'             => true,
          'show_in_menu'        => true,
          'show_in_nav_menus'   => true,
          'show_in_admin_bar'   => true,
          'menu_position'       => 5,
          'can_export'          => true,
          'has_archive'         => true,
          'exclude_from_search' => false,
          'publicly_queryable'  => true,
          'capability_type'     => 'post',
          'show_in_rest' => true,
          'menu_icon'           => 'dashicons-no-alt'
   
      );
    }
     
    // Registering your Custom Post Type
    register_post_type( 'securepay-error', $securepay_args );

    $quote_labels = array(
        'name'                => _x( 'Quote', 'Post Type General Name', 'tutis' ),
        'singular_name'       => _x( 'Quote', 'Post Type Singular Name', 'tutis' ),
        'menu_name'           => __( 'Quotes', 'tutis' ),
        'parent_item_colon'   => __( 'Parent Quotes', 'tutis' ),
        'all_items'           => __( 'All Quotes', 'tutis' ),
        'view_item'           => __( 'View Quotes', 'tutis' ),
        'add_new_item'        => __( 'Add New Quotes', 'tutis' ),
        'add_new'             => __( 'Add New', 'tutis' ),
        'edit_item'           => __( 'Edit Quotes', 'tutis' ),
        'update_item'         => __( 'Update Quotes', 'tutis' ),
        'search_items'        => __( 'Search Quotes', 'tutis' ),
        'not_found'           => __( 'Not Found', 'tutis' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'tutis' ),
    );
     
    $quote_args = array(
        'label'               => __( 'Quotes', 'tutis' ),
        'description'         => __( 'Quotes', 'tutis' ),
        'labels'              => $quote_labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
        'show_in_rest' => true,
        'menu_icon'           => 'dashicons-calculator'
 
    );
     
    // Registering your Custom Post Type
    register_post_type( 'quotes', $quote_args );



    $student_labels = array(
        'name'                => _x( 'Student', 'Post Type General Name', 'tutis' ),
        'singular_name'       => _x( 'Student', 'Post Type Singular Name', 'tutis' ),
        'menu_name'           => __( 'Student', 'tutis' ),
        'parent_item_colon'   => __( 'Parent Student', 'tutis' ),
        'all_items'           => __( 'All Student', 'tutis' ),
        'view_item'           => __( 'View Student', 'tutis' ),
        'add_new_item'        => __( 'Add New Student', 'tutis' ),
        'add_new'             => __( 'Add New', 'tutis' ),
        'edit_item'           => __( 'Edit Student', 'tutis' ),
        'update_item'         => __( 'Update Student', 'tutis' ),
        'search_items'        => __( 'Search Student', 'tutis' ),
        'not_found'           => __( 'Not Found', 'tutis' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'tutis' ),
    );
     
    $student_args = array(
        'label'               => __( 'Student', 'tutis' ),
        'description'         => __( 'Student', 'tutis' ),
        'labels'              => $student_labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => false,
        'show_in_menu'        => false,
        'show_in_nav_menus'   => false,
        'show_in_admin_bar'   => false,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => true,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
        'show_in_rest' => true,
        'menu_icon'           => 'dashicons-admin-users'
 
    );
     
    // Registering your Custom Post Type
    register_post_type( 'student', $student_args );

    $offerings_labels = array(
      'name'                => _x( 'Offerings', 'Post Type General Name', 'tutis' ),
      'singular_name'       => _x( 'Offering', 'Post Type Singular Name', 'tutis' ),
      'menu_name'           => __( 'Offerings', 'tutis' ),
      'parent_item_colon'   => __( 'Parent Offerings', 'tutis' ),
      'all_items'           => __( 'All Offerings', 'tutis' ),
      'view_item'           => __( 'View Offerings', 'tutis' ),
      'add_new_item'        => __( 'Add New Offerings', 'tutis' ),
      'add_new'             => __( 'Add New', 'tutis' ),
      'edit_item'           => __( 'Edit Offering', 'tutis' ),
      'update_item'         => __( 'Update Offering', 'tutis' ),
      'search_items'        => __( 'Search Offerings', 'tutis' ),
      'not_found'           => __( 'Not Found', 'tutis' ),
      'not_found_in_trash'  => __( 'Not found in Trash', 'tutis' ),
    );
       
    $offerings_args = array(
        'label'               => __( 'Offerings', 'tutis' ),
        'description'         => __( 'Offerings', 'tutis' ),
        'labels'              => $offerings_labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields'),
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
        'show_in_rest' => true,
        'menu_icon'           => 'dashicons-edit-page'

    );
       
    // Registering your Custom Post Type
    register_post_type( 'offerings', $offerings_args );
 
  }

  
 
  public function tutis_create_taxonomy() {
    $state_labels = array(
      'name' => _x( 'States', 'taxonomy general name' ),
      'singular_name' => _x( 'State', 'taxonomy singular name' ),
      'search_items' =>  __( 'Search States' ),
      'all_items' => __( 'All States' ),
      'parent_item' => __( 'Parent State' ),
      'parent_item_colon' => __( 'Parent State:' ),
      'edit_item' => __( 'Edit State' ), 
      'update_item' => __( 'Update State' ),
      'add_new_item' => __( 'Add New State' ),
      'new_item_name' => __( 'New State Name' ),
      'menu_name' => __( 'States' ),
    );    

    register_taxonomy('state',array('offerings'), array(
      'hierarchical' => true,
      'labels' => $state_labels,
      'show_ui' => true,
      'show_in_rest' => true,
      'show_admin_column' => true,
      'query_var' => true,
      'rewrite' => array( 'slug' => 'state' ),
    ));

    $location_labels = array(
      'name' => _x( 'Locations', 'taxonomy general name' ),
      'singular_name' => _x( 'Location', 'taxonomy singular name' ),
      'search_items' =>  __( 'Search Location' ),
      'all_items' => __( 'All Locations' ),
      'parent_item' => __( 'Parent Location' ),
      'parent_item_colon' => __( 'Parent Location:' ),
      'edit_item' => __( 'Edit Location' ), 
      'update_item' => __( 'Update Location' ),
      'add_new_item' => __( 'Add New Location' ),
      'new_item_name' => __( 'New Location Name' ),
      'menu_name' => __( 'Locations' ),
    );    

    register_taxonomy('location',array('offerings'), array(
      'hierarchical' => true,
      'labels' => $location_labels,
      'show_ui' => true,
      'show_in_rest' => true,
      'show_admin_column' => true,
      'query_var' => true,
      'rewrite' => array( 'slug' => 'location' ),
    ));

    $program_type_labels = array(
      'name' => _x( 'Program', 'taxonomy general name' ),
      'singular_name' => _x( 'Program', 'taxonomy singular name' ),
      'search_items' =>  __( 'Search Program' ),
      'all_items' => __( 'All Program' ),
      'parent_item' => __( 'Parent Program' ),
      'parent_item_colon' => __( 'Parent Program:' ),
      'edit_item' => __( 'Edit Program' ), 
      'update_item' => __( 'Update Program' ),
      'add_new_item' => __( 'Add New Program' ),
      'new_item_name' => __( 'New Program Name' ),
      'menu_name' => __( 'Program' ),
    );   

    register_taxonomy('program',array('offerings'), array(
      'hierarchical' => true,
      'labels' => $program_type_labels,
      'show_ui' => true,
      'show_in_rest' => true,
      'show_admin_column' => true,
      'query_var' => true,
      'rewrite' => array( 'slug' => 'program' ),
    ));
  }

  public function courses_add_id_column( $columns ) {
    $columns['course_code_id'] = 'Course Code';
    return $columns;
  }

  public function offering_add_id_column( $columns ) {
    $columns['course_code_id'] = 'Course Code';
    $columns['course_public'] = 'Public';
    return $columns;
  }

  public function offering_column_content( $column, $id ) {
    if( 'course_code_id' == $column ) {
      echo get_post_meta( $id, '_course_code', true );
    }
    if( 'course_public' == $column ) {
      echo get_post_meta( $id, '_public', true );
    }
  }

  public function enrollies_add_id_column( $columns ) {
    $columns['response_code'] = 'Response Code';
    return $columns;
  }

  public function enrollies_column_content( $column, $id ) {
    if( 'response_code' == $column ) {
      echo get_post_meta( $id, '_tutis_response_code', true );
    }
  }

  public function courses_column_content( $column, $id ) {
    if( 'course_code_id' == $column ) {
      echo get_post_meta( $id, '_course_code', true );
    }
  }

  public function secure_add_id_column( $columns ) {
    $columns['error_code'] = 'Error Code';
    $columns['error_from'] = 'Error From';
    return $columns;
  }

  public function secure_column_content( $column, $id ) {
    if( 'error_code' == $column ) {
      if( !is_array( get_post_meta( $id, '_tutis_securepay_error_code', true ) ) ) {
        echo get_post_meta( $id, '_tutis_securepay_error_code', true );
      }
      else {  
        echo json_encode( get_post_meta( $id, '_tutis_securepay_error_code', true ) );
      }
    }
    if( 'error_from' == $column ) {
        echo get_post_meta( $id, '_tutis_securepay_from', true );
    }
  }

  

  public function state_add_col( $columns ) {
    $columns['tax_id'] = __( 'ID' );
    return $columns;
  }

  public function state_tax_id( $value, $name, $id ) {    
    return 'tax_id' === $name ? $id : $value;
  }
 
  public function program_content_tax_columns( $string, $columns, $term_id ) {
    switch ( $columns ) {
    // in this example, we had saved some term meta as "genre-characterization"
      case 'programcode' :
        echo esc_html( get_term_meta( $term_id, 'tutis_program_code', true ) );
      case 'locationcode' :
        echo esc_html( get_term_meta( $term_id, 'tutis_location_code', true ) );  

      case 'statecode' :
        echo esc_html( get_term_meta( $term_id, 'tutis_state_code', true ) );   
      break;
    }
  }
 
  public function add_program_content_tax_columns( $columns ) {
      $columns['programcode'] = __( 'Program Code' );
      return $columns;
  }
 
  public function add_location_content_tax_columns( $columns ) {
      $columns['locationcode'] = __( 'Location Code' );
      return $columns;
  }

  public function add_state_content_tax_columns( $columns ) {
      $columns['statecode'] = __( 'state Code' );
      return $columns;
  }

  public function register_post_type() {
    add_action( 'init', array( $this, 'tutis_create_post_type' ) );
    add_action( 'init', array( $this, 'tutis_create_taxonomy' ), 0 );
    add_filter( 'manage_offerings_posts_columns', array( $this, 'offering_add_id_column'), 10 );
    add_action( 'manage_offerings_posts_custom_column', array( $this, 'offering_column_content' ) , 10, 2 );
    add_filter( 'manage_enrollies_posts_columns', array( $this, 'enrollies_add_id_column'), 10 );
    add_action( 'manage_enrollies_posts_custom_column', array( $this, 'enrollies_column_content' ) , 10, 2 );

    add_filter( 'manage_securepay-error_posts_columns', array( $this, 'secure_add_id_column'), 10 );
    add_action( 'manage_securepay-error_posts_custom_column', array( $this, 'secure_column_content' ) , 10, 2 );

    add_filter( 'manage_quotes_posts_columns', array( $this, 'enrollies_add_id_column'), 10 );
    add_action( 'manage_quotes_posts_custom_column', array( $this, 'enrollies_column_content' ) , 10, 2 );
    add_filter( 'manage_courses_posts_columns', array( $this, 'courses_add_id_column'), 10 );
    add_action( 'manage_courses_posts_custom_column', array( $this, 'courses_column_content' ) , 10, 2 );

    add_filter( 'manage_edit-state_columns',          array( $this, 'add_state_content_tax_columns' ) );
    add_filter( 'manage_state_custom_column',         array( $this, 'program_content_tax_columns' ) , 10, 3 );

    add_filter( 'manage_location_custom_column',         array( $this, 'program_content_tax_columns' ) , 10, 3 );
    add_filter( 'manage_edit-location_columns',          array( $this, 'add_location_content_tax_columns' ) );

    add_action( 'manage_program_custom_column', array( $this, 'program_content_tax_columns' ), 10, 3 );
    add_filter( 'manage_edit-program_columns', array( $this, 'add_program_content_tax_columns') );
  }

}

$tutis_post_type = new Tutis_Post_Type();
?>