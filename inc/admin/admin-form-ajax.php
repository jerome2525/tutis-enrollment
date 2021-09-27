<?php

class Admin_Form_Ajax {

    public function __construct() {
        $this->load_hooks();
    }

    public function load_hooks() {
        add_action( 'wp_ajax_adminwsfilter', array( $this, 'display_result' ) ); 
        add_action( 'wp_ajax_nopriv_adminwsfilter', array( $this, 'display_result' ) );
    }

    public function get_insert_all_offerings_from_api( $courseCode, $startDate, $endDate, $prerequisits, $fee, $vacancies, $offeringCode, $deliveryLocation, $offeringId, $public ) {
        if( $offeringId && $offeringCode ) {
            $courses_args = array(
                'post_title'    => $offeringCode,
                'post_content'  => $prerequisits,
                'post_status'   => 'publish',
                'post_type'   => 'offerings',
            );
             
            // Insert the post into the database.
            $post_id = wp_insert_post( $courses_args );
            if( !is_wp_error( $post_id ) ) {            
                add_post_meta( $post_id, '_course_code', $courseCode, true );
                add_post_meta( $post_id, '_start_date', $startDate, true );
                add_post_meta( $post_id, '_end_date', $endDate, true );
                add_post_meta( $post_id, '_course_fee', $fee, true );
                add_post_meta( $post_id, '_vacancies', $vacancies, true );
                add_post_meta( $post_id, '_offering_code', $offeringCode, true );
                add_post_meta( $post_id, '_offering_id', $offeringId, true );
                add_post_meta( $post_id, '_public', $public, true );
                add_post_meta( $post_id, '_location_code', $deliveryLocation, true );
            }
            else {
                //there was an error in the post insertion, 
                echo $post_id->get_error_message();
            }
        }
    }

    public function insert_offerings() {
        $tutis_api_url = get_option('tutis_api_url');
        $tutis_api_key = get_option('tutis_api_key');
        $tutis_api_size = get_option('tutis_api_size');
        $tutis_pagi_count = get_option('tutis_pagi_count');
        if( $tutis_api_url && $tutis_api_key && $tutis_api_size ) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $tutis_api_url . '/api/offerings/all?page=' . $tutis_pagi_count . '&size=' . $tutis_api_size );
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

            $headers = array();
            $headers[] = 'Accept: application/json';
            $headers[] = 'x-api-key:' . $tutis_api_key . '';
            curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
        
            if ( curl_errno( $ch ) ) {
                echo 'Error:' . curl_error( $ch );
            }
            else {
                $result = curl_exec( $ch );
                $obj = json_decode( $result, true );
                $totalPages = $obj['totalPages'];
                $api_total_pages = update_option('tutis_total_pages', $totalPages );
                if( isset( $obj['content'] ) ) {
                    foreach( $obj['content'] as $key => $entry ) { 
                        $courseCode = $entry['courseCode'];
                        $startDate = $entry['startDate'];
                        $endDate = $entry['endDate'];
                        $prerequisits = $entry['description'];
                        $fee = $entry['cost'];
                        $vacancies = $entry['vacancies'];
                        $offeringCode = $entry['offeringCode'];
                        $deliveryLocation = $entry['deliveryLocation'];
                        $offeringId = $entry['offeringId'];
                        $public = $entry['public'];
                        if( empty( $prerequisits ) ) {
                            $prerequisits = 'no prerequisite';
                        }

                        if( $startDate && $endDate ) {
                            $this->get_insert_all_offerings_from_api( $courseCode, $startDate, $endDate, $prerequisits, $fee, $vacancies, $offeringCode, $deliveryLocation, $offeringId, $public );
                        }
                    }

                    $current_time = current_time( 'mysql' );
                    $last_updated = date('d M Y H:i:s', strtotime( $current_time ) ); 
                    update_option('tutis_last_sync', $last_updated );
                    update_option('tutis_pagi_count', get_option('tutis_pagi_count') + 1 );
                    $next_updated = date('d M Y H:i:s', strtotime('+1 hour', strtotime( $current_time ) ) );
                    update_option('tutis_next_sync', $next_updated ); 
                    $auto_run_last_updated = date('Y-m-d', strtotime( $current_time ) ); 
                    update_option('tutis_auto_next_sync', $auto_run_last_updated ); 

                    echo "<div class='updated'><p>Proceeding to the next part...</p></div>";
                }
                else {
                    echo '<p>Invalid Key or URL</p>';
                }
                //var_dump($obj);
            }
            
            curl_close($ch);
        }
    }

    public function reset_filter_result() {
        update_option('tutis_last_sync','');
        update_option('tutis_next_sync','');
        if( tutis_count_post_type( 'offerings' ) > 0 ) {
            update_option('offering_delete_counter', 1 );
            tutis_delete_all_offerings();
            echo "<div class='updated'><p>Please wait for a few sec to start deleting some offerings...</p></div>"; 
        }
        else {
            tutis_delete_all_post_type('courses');
            tutis_delete_all_post_type('state_country');
            tutis_delete_all_tax('program');
            tutis_delete_all_tax('coursetype');
            tutis_delete_all_tax('location');
            tutis_delete_all_tax('state');
            update_option('tutis_pagi_count', 0 );
            update_option('tutis_total_pages', tutis_get_pagination_total_number( 'totalPages' ) );
            echo "<div class='updated'><p>Please wait for a few sec... to start downloading the offerings.</p></div>";  
            update_option('offering_delete_counter', 0 );
        }
    }

    public function update_filter_result() {
        tutis_update_program_from_api();
        tutis_update_state_country_from_api();
        tutis_auto_update_offerings();
        echo "<div class='updated'><p>Offerings has been Updated!</p></div>";
    }

    public function generate_rest_token() {
        $pass = md5(microtime(true).random_int(0,99) );
        $current_user = wp_get_current_user();
        $username = $current_user->user_login;
        $token = base64_encode( $username . ':' . $pass );
        update_option('tutis_rest_password', $pass );
        update_option('tutis_rest_token', $token );
        echo '<p>Supported Notifications: <strong>offering, course, location, all </strong> </p>';
        echo '<p>Token: <strong>' . get_option('tutis_rest_token') . '</strong></p>';
        echo '<p>URL: <strong>' . get_home_url() . '/wp-json/tutis_rest/v1/update/?service=offering</strong> </p>';
        echo '<h3>Sample CURL:</h3>';
        echo '<code>'; 
        ?>
        curl -XGET -H 'token: <?php echo get_option('tutis_rest_token'); ?>' -H "Content-type: application/json" '<?php echo get_home_url(); ?>/wp-json/tutis_rest/v1/update/?service=offering'
        <?php 
        echo'</code>';
    }

    public function update_rest_call() {
        update_option('tutis_offering_update', 'not-active');
        update_option('tutis_program_update', 'not-active');
        update_option('tutis_state_update', 'not-active');
        update_option('tutis_setting_update', 'not-active');
        update_option('tutis_all_update', 'not-active');
    }

    public function display_result() {
        if( isset( $_POST['action'] ) ) {
            if( isset( $_POST['sync_input'] ) ) {
                tutis_generate_settings_payment_api();
                $this->insert_offerings();
                if( get_option('tutis_total_pages') == get_option('tutis_pagi_count') ) {
                    update_option( 'tutis_sync_adons', 4 );
                }
                if( get_option( 'tutis_sync_adons' ) == 4 ) {
                    tutis_insert_program_from_api();
                    update_option( 'tutis_sync_adons', 3 );
                }
                else if( get_option( 'tutis_sync_adons' ) == 3 ) {
                    tutis_insert_program_to_course();
                    update_option( 'tutis_sync_adons', 2 );
                }
                else if( get_option( 'tutis_sync_adons' ) == 2 ) {
                    tutis_insert_state_country_from_api();
                    update_option( 'tutis_sync_adons', 1 );
                }
                else if( get_option( 'tutis_sync_adons' ) == 1 ) {
                    $this->update_rest_call();
                    delete_option( 'tutis_sync_adons');
                }
            }
            else if( isset( $_POST['reset_input'] ) ) {
                $this->reset_filter_result();
            }

            else if( isset( $_POST['update_input'] ) ) {
                $this->update_filter_result();
            }

            else if( isset( $_POST['rest_input'] ) ) {
                $this->generate_rest_token();
            }
            die();
        }   
    }

}


$tutis_admin_form_ajax = new Admin_Form_Ajax();