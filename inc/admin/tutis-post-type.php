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
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
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

    register_taxonomy('state',array('courses'), array(
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

    register_taxonomy('location',array('courses'), array(
      'hierarchical' => true,
      'labels' => $location_labels,
      'show_ui' => true,
      'show_in_rest' => true,
      'show_admin_column' => true,
      'query_var' => true,
      'rewrite' => array( 'slug' => 'location' ),
    ));

    $course_type_labels = array(
      'name' => _x( 'Course Type', 'taxonomy general name' ),
      'singular_name' => _x( 'Course Type', 'taxonomy singular name' ),
      'search_items' =>  __( 'Search Course Type' ),
      'all_items' => __( 'All Course Type' ),
      'parent_item' => __( 'Parent Course Type' ),
      'parent_item_colon' => __( 'Parent Course Type:' ),
      'edit_item' => __( 'Edit Course Type' ), 
      'update_item' => __( 'Update Course Type' ),
      'add_new_item' => __( 'Add New Course Type' ),
      'new_item_name' => __( 'New Course Type Name' ),
      'menu_name' => __( 'Course Type' ),
    );    

    register_taxonomy('coursetype',array('courses'), array(
      'hierarchical' => true,
      'labels' => $course_type_labels,
      'show_ui' => true,
      'show_in_rest' => true,
      'show_admin_column' => true,
      'query_var' => true,
      'rewrite' => array( 'slug' => 'coursetype' ),
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

    register_taxonomy('program',array('courses'), array(
      'hierarchical' => true,
      'labels' => $program_type_labels,
      'show_ui' => true,
      'show_in_rest' => true,
      'show_admin_column' => true,
      'query_var' => true,
      'rewrite' => array( 'slug' => 'program' ),
    ));
  }

  public function register_post_type() {
    add_action( 'init', array( $this, 'tutis_create_post_type' ) );
    add_action( 'init', array( $this, 'tutis_create_taxonomy' ), 0 );
  }

}

new Tutis_Post_Type;
?>