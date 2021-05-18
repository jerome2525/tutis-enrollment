<?php

class Admin_Form_Ajax {

    public function __construct() {
        $this->load_hooks();
    }

    public function load_hooks() {
        add_action( 'wp_ajax_adminwsfilter', array( $this, 'display_result' ) ); 
        add_action( 'wp_ajax_nopriv_adminwsfilter', array( $this, 'display_result' ) );
    }

    public function delete_all_courses() {
        $args = array(
            'post_type'     => 'courses',
            'post_status'   => 'publish',
            'posts_per_page' => -1,
        );

        $wp_query = new WP_Query( $args );
        if ( $wp_query->have_posts() ) { 
            while ( $wp_query->have_posts() ) {
                $wp_query->the_post();
                $pid = get_the_ID();
                wp_delete_post( $pid, true ); 
            }
        }
    }

    public function get_add_all_course_from_api( $courseName, $courseCode, $startDate, $endDate, $prerequisits, $fee, $vacancies, $offeringCode, $deliveryState, $deliveryLocation, $courseType, $offeringId, $programName, $public ) {

        $courses_args = array(
            'post_title'    => $courseName,
            'post_content'  => $prerequisits,
            'post_status'   => 'publish',
            'post_type'   => 'courses',
        );
         
        // Insert the post into the database.
        $post_id = wp_insert_post( $courses_args );
        if( !is_wp_error( $post_id ) ) {
            $state_id = wp_set_object_terms( $post_id, $deliveryState, 'state', true );
            $location_id = wp_set_object_terms( $post_id, $deliveryLocation, 'location', true );
            update_term_meta( $location_id[0], 'tutis_location_parent', $state_id[0] );
            $course_type = wp_set_object_terms( $post_id, $courseType, 'coursetype', true );
            $program_name = wp_set_object_terms( $post_id, $programName, 'program', true );
            add_post_meta( $post_id, '_course_code', $courseCode, true );
            add_post_meta( $post_id, '_start_date', $startDate, true );
            add_post_meta( $post_id, '_end_date', $endDate, true );
            add_post_meta( $post_id, '_course_fee', $fee, true );
            add_post_meta( $post_id, '_vacancies', $vacancies, true );
            add_post_meta( $post_id, '_offering_code', $offeringCode, true );
            add_post_meta( $post_id, '_offering_id', $offeringId, true );
            add_post_meta( $post_id, '_public', $public, true );
        }
        else {
            //there was an error in the post insertion, 
            echo $post_id->get_error_message();
        }
    }

    public function display_filter_result() {
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
                    if( $tutis_pagi_count == 0 ) {
                        $this->delete_all_courses();
                    }
                    foreach( $obj['content'] as $key => $entry ) { 
                        $courseName = $entry['courseName'];
                        $courseCode = $entry['courseCode'];
                        $startDate = $entry['startDate'];
                        $endDate = $entry['endDate'];
                        $prerequisits = $entry['description'];
                        $fee = $entry['cost'];
                        $vacancies = $entry['vacancies'];
                        $offeringCode = $entry['offeringCode'];
                        $deliveryState = $entry['deliveryState'];
                        $deliveryLocation = $entry['deliveryLocation'];
                        $courseType = $entry['courseType'];
                        $programName = $entry['programName'];
                        $offeringId = $entry['offeringId'];
                        $public = $entry['public'];
                        if( empty( $prerequisits ) ) {
                            $prerequisits = 'no prerequisite';
                        }

                        if( $startDate && $endDate && $vacancies ) {
                            $this->get_add_all_course_from_api( $courseName, $courseCode, $startDate, $endDate, $prerequisits, $fee, $vacancies, $offeringCode, $deliveryState, $deliveryLocation, $courseType, $offeringId, $programName, $public );
                        }
                    }

                    $current_time = current_time( 'mysql' );
                    $last_updated = date('d M Y H:i:s', strtotime( $current_time ) ); 
                    update_option('tutis_last_sync', $last_updated );
                    update_option('tutis_pagi_count', get_option('tutis_pagi_count') + 1 );

                    if( get_option('tutis_auto_sync') == 1 ) {
                        $next_updated = date('d M Y H:i:s', strtotime('+1 hour', strtotime( $current_time ) ) );
                        update_option('tutis_next_sync', $next_updated ); 
                    }
                    echo "<div class='updated'><p>Proceeding to the next part...</p></div>";
                }
                else {
                    echo '<p>Invalid Key or URL</p>';
                }
                //var_dump($obj);
            }
            
            curl_close($ch);
        }

        die();
    }

    public function generate_securepay_api_details() {
        $tutis_api_url = get_option('tutis_api_url');
        $tutis_api_key = get_option('tutis_api_key');
        if( $tutis_api_url && $tutis_api_key ) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $tutis_api_url . '/api/settings/securePay' );
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
                $accessTokenUri = $obj['accessTokenUri'];
                $merchantCode = $obj['merchantCode'];
                $clientId = $obj['clientId'];
                $clientSecret = $obj['clientSecret'];
                update_option('tutis_secure_pay_client_id', $clientId );
                update_option('tutis_secure_pay_merchant_code', $merchantCode );
                //var_dump($obj);
            }
            
            curl_close($ch);
        }

        die();
    }

    public function reset_filter_result() {
        update_option('tutis_last_sync','');
        update_option('tutis_next_sync','');
        $this->delete_all_courses();
        update_option('tutis_pagi_count', 0 );
        update_option('tutis_total_pages', tutis_get_pagination_total_number( 'totalPages' ) );
        echo "<div class='updated'><p>Please wait for a few sec... to start downloading the offerings.</p></div>";
        die();
    }

    public function update_filter_result() {
        tutis_default_sync_course_api();
        echo "<div class='updated'><p>Offerings has been Updated!</p></div>";
        die();
    }

    public function display_result() {
        if( isset( $_POST['action'] ) ) {
            if( isset( $_POST['sync_input'] ) ) {
                $this->display_filter_result();
                $this->generate_securepay_api_details();
            }
            else if( isset( $_POST['reset_input'] ) ) {
                $this->reset_filter_result();
            }

            else if( isset( $_POST['update_input'] ) ) {
                $this->update_filter_result();
            }
        }   
    }

}


new Admin_Form_Ajax;