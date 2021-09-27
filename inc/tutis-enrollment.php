<?php
/**
 * This controls the plugin
 * @package  JA
 */


class Tutis_Enrollment {

	public function __construct() {
		$this->load_includes();
		$this->load_assets();
	}

	//Include admin and Public files
	public function load_includes() {
		include_once( plugin_dir_path( __FILE__ ) . 'reusable.php' );
		include_once( plugin_dir_path( __FILE__ ) . 'tutis-cron-jobs.php' );
		include_once( plugin_dir_path( __FILE__ ) . 'admin/tutis-post-type.php' );
		include_once( plugin_dir_path( __FILE__ ) . 'admin/tutis-settings.php' );
		include_once( plugin_dir_path( __FILE__ ) . 'admin/tutis-meta-boxes.php' );
		include_once( plugin_dir_path( __FILE__ ) . 'admin/admin-form-ajax.php' );
		include_once( plugin_dir_path( __FILE__ ) . 'public/form-ajax.php' );
		include_once( plugin_dir_path( __FILE__ ) . 'public/custom-shortcode.php' );
		include_once( plugin_dir_path( __FILE__ ) . 'public/tutis-footer.php' );
		include_once( plugin_dir_path( __FILE__ ) . 'tutis-rest.php' );
	}

	//Load Assets
	public function load_assets() {

		add_action( 'wp_enqueue_scripts', array( $this, 'register_assets' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_assets' ) );

	}

	public function register_assets() {
		//css
		wp_enqueue_style( 'tutis-enrollment-font-awesome', '//cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css', array(), '1.0' );
		wp_enqueue_style( 'tutis-enrollment-font', '//fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap', array(), '1.0' );

		wp_enqueue_style( 'tutis-date-range-css', '//cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.css', array(), '1.0' );
		wp_enqueue_style( 'tutis-drop-down-css', plugin_dir_url( __FILE__ ) . 'lib/selectstyle.min.css', array(), '1.0' );
		wp_enqueue_style( 'tutis-lity-css', plugin_dir_url( __FILE__ ) . 'lib/lity.min.css', array(), '1.0' );
		wp_enqueue_style( 'tutis-admin-ui-css', plugin_dir_url( __FILE__ ) . 'css/jquery-ui.min.css', array(), '1.0');
		wp_enqueue_style( 'tutis-enrollment-css', plugin_dir_url( __FILE__ ) . 'css/main.css', array(), '1.0' );
		if( get_option('tutis_css_code') ) {
			wp_add_inline_style( 'tutis-enrollment-css', get_option('tutis_css_code') );
		}
		
		//js
		wp_enqueue_script( 'tutis-moment-js', '//cdn.jsdelivr.net/momentjs/latest/moment.min.js', array('jquery'), '1.0', true );
		wp_enqueue_script( 'tutis-date-range-js', '//cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js', array('jquery'), '1.0', true );
		wp_enqueue_script( 'tutis-drop-down-js', plugin_dir_url( __FILE__ ) . 'lib/selectstyle.min.js', array('jquery'), '1.0', true );
		wp_enqueue_script( 'tutis-lity-js', plugin_dir_url( __FILE__ ) . 'lib/lity.min.js', array('jquery'), '1.0', true );
		wp_enqueue_script( 'tutis-admin-ui-js', plugin_dir_url( __FILE__ ) . 'js/jquery-ui.min.js', array('jquery'), '1.0', true );
		wp_enqueue_script( 'tutis-enrollment-head-js', plugin_dir_url( __FILE__ ) . 'js/main-head.js', array('jquery'), '1.0', false );
		wp_enqueue_script( 'tutis-enrollment-js', plugin_dir_url( __FILE__ ) . 'js/main.js', array('jquery'), '1.0', true );
		$temp_id = '';
		$student_count = '';
		$course_vacant_count_meta = '';
		if( isset( $_GET['temp_id'] ) ) {
			$temp_id = $_GET['temp_id'];
			$student_count = count_student_vacancies( $temp_id );
			if( isset( $_GET['course_id'] ) ) {
				$course_id = $_GET['course_id'];
				$course_vacant_count_meta = get_post_meta( $course_id, '_vacancies', true );
			}
		}
		wp_localize_script( 'tutis-enrollment-js', 'tutis_frontend_object',
	        array( 
	            'tutis_thank_url' => get_option('tutis_thank_you_url'),
	            'tutis_temp_id' => $temp_id,
	            'tutis_public_course' => get_option('tutis_public_course'),
	            'tutis_student_count' => $student_count,
	            'tutis_course_vacant_count' => $course_vacant_count_meta
	        )
	    );

	}

	public function register_admin_assets() {
		wp_enqueue_style( 'tutis-enrollment-admin-css', plugin_dir_url( __FILE__ ) . 'css/admin.css', array(), '1.0' );
		if (($_GET['page'] ?? '') == 'tutis-settings') {
			//css
			wp_enqueue_style( 'tutis-admin-confirm-css', '//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css', array(), '1.0');
			wp_enqueue_style( 'tutis-admin-ui-css', plugin_dir_url( __FILE__ ) . 'css/jquery-ui.min.css', array(), '1.0');


			//js
			//wp_enqueue_script( 'tutis-admin-ui-js', '//code.jquery.com/ui/1.12.1/jquery-ui.js', array('jquery'), '1.0', true );
			wp_enqueue_script( 'tutis-admin-ui-js', plugin_dir_url( __FILE__ ) . 'js/jquery-ui.min.js', array('jquery'), '1.0', true );
			wp_enqueue_script( 'tutis-admin-confirm-js', '//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js', array('jquery'), '1.0', true );
			wp_enqueue_script( 'tutis-admin-js', plugin_dir_url( __FILE__ ) . 'js/admin.js', array('jquery'), '1.0', true );
		}
	}

}