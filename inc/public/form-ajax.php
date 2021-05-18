<?php

class Form_Ajax {

    public function __construct() {
        $this->load_hooks();
    }

    public function load_hooks() {
        add_action( 'wp_ajax_wsfilter', array( $this, 'display_result' ) ); 
        add_action( 'wp_ajax_nopriv_wsfilter', array( $this, 'display_result' ) );
    }

    public function display_filter_result() {
        $program_id = $_POST['program'];
        $coursename_title = $_POST['coursename'];
        $state_id = $_POST['state'];
        $location_id = $_POST['location'];
        $start = $_POST['start'];
        $end = $_POST['end'];
        $paged = $_POST['pagi'];
        $random_id = $_POST['random_id'];
        if( get_option('tutis_course_per_page') ) {
            $per_page =  get_option('tutis_course_per_page');
        }
        else {
            $per_page = 10;
        }

        if( get_option('tutis_pre_label') ) {
            $pre_label =  get_option('tutis_pre_label');
        }
        else {
            $pre_label = 'Prerequisites';
        }


        if( get_option('tutis_vacancies_label') ) {
            $vacancies_label =  get_option('tutis_vacancies_label');
        }
        else {
            $vacancies_label = 'Vacancies';
        }

        if( get_option('tutis_program_label') ) {
            $program_label =  get_option('tutis_program_label');
        }
        else {
            $program_label = 'Program';
        }

        $args = array(
            'post_type'     => 'courses',
            'post_status'   => 'publish',
            'posts_per_page' => $per_page,
            'paged' => $paged,
            'title' => $coursename_title,
            'orderby' => array(
                '_start_date' => 'ASC',
                'title' => 'ASC'
            ), 
        );

        if( $start ) {
            $meta_query[] = array(
                'relation' => 'AND',
                array(
                    'key'   =>  '_start_date',
                    'value' => array( $start, $end ),
                    'compare' => 'BETWEEN',
                    'type' => 'DATE'
                )
            );
        }

        if( get_option('tutis_public_course') ) {
            $meta_query[] = array(
                'relation' => 'AND',
                array(
                    'key'   =>  '_public',
                    'value' => get_option('tutis_public_course'),
                    'compare' => '=',
                )
            );
        }


        if( $location_id || $program_id || $state_id ) {
            $tax_query = array('relation' => 'AND');
        }

        if( $program_id ) {
            $tax_query[] = array(
                'taxonomy' => 'program',
                'field' => 'term_id',
                'terms' => $program_id
            );
        }

        if( $state_id ) {
            $tax_query[] = array(
                'taxonomy' => 'state',
                'field' => 'term_id',
                'terms' => $state_id
            );
        }

        if( $location_id ) {
            $tax_query[] = array(
                'taxonomy' => 'location',
                'field' => 'term_id',
                'terms' => $location_id
            );
        }

        if( $location_id || $program_id || $state_id ) {
            $args['tax_query'] = $tax_query;
        }

        if( $start && $end ) {
            $args['meta_query'] = $meta_query;
        }

        $wp_query = new WP_Query( $args );
        if ( $wp_query->have_posts() ) { 
            echo '<div class="tutis-grid-table">';
            echo '<div class="heading tutis-hide-mobile">';
            echo '<strong>Course</strong>';
            echo '</div>';

            echo '<div class="heading tutis-hide-mobile">';
            echo '<strong>Start Date</strong>';
            echo '</div>';

            echo '<div class="heading tutis-hide-mobile">';
            echo '<strong>End Date</strong>';
            echo '</div>';

            echo '<div class="heading tutis-hide-mobile">';
            echo '<strong>State</strong>';
            echo '</div>';

            echo '<div class="heading tutis-hide-mobile">';
            echo '<strong>' . $vacancies_label . '</strong>';
            echo '</div>';

            echo '<div class="heading tutis-hide-mobile">';
            echo '</div>';
            while ( $wp_query->have_posts() ) {
                $wp_query->the_post();
                $pid = get_the_ID();
                $title = get_the_title();
                $course_code_value = get_post_meta( $pid, '_course_code', true );
                $start_date_value = get_post_meta( $pid, '_start_date', true );
                $end_date_value = get_post_meta( $pid, '_end_date', true );
                $start_date_value = date('d M Y', strtotime( $start_date_value ) );  
                $end_date_value= date('d M Y', strtotime( $end_date_value ) );
                $course_fee_value = get_post_meta( $pid, '_course_fee', true );
                $vacancies_value = get_post_meta( $pid, '_vacancies', true );
                $public_value = get_post_meta( $pid, '_public', true );
                $offering_code_value = get_post_meta( $pid, '_offering_code', true );
                $state = tutis_get_taxonomy_id_from_post_id( $pid, 'state','name' );
                $location = tutis_get_taxonomy_id_from_post_id( $pid, 'location','name' );
                $program = tutis_get_taxonomy_id_from_post_id( $pid, 'program','name' );

                echo '<div class="tbbody first padded rl' . $pid . '"><span class="expand" id="cl' . $pid . '"><i class="fa fa-plus-circle" aria-hidden="true"></i></span><span><strong>' . $title . '</strong></span></div>';
                echo '<div class="tbbody padded tutis-hide-mobile rl' . $pid . '"><span><strong>' . $start_date_value . '</strong></span></div>';  
                echo '<div class="tbbody padded tutis-hide-mobile rl' . $pid . '"><span><strong>' . $end_date_value . '</strong></span></div>';
                echo '<div class="tbbody padded tutis-hide-mobile rl' . $pid . '"><span><strong>' . $state . '</strong></span></div>';
                echo '<div class="tbbody padded tutis-hide-mobile rl' . $pid . '"><span><strong>' . $vacancies_value . '</strong></span></div>';
                echo '<div class="tbbody tutis-hide-mobile rl' . $pid . '"><a href="?course_id=' . get_the_ID() . '&temp_id=' . $random_id . '&np=2" class="tutis-btn">Enroll Now</a></div>';
                echo '<div class="tbbodycontent cl' . $pid . ' tbbodycontent-pad-remove">';
                  echo '<div class="tbbodycontentcol col1">';
                    echo '<p><strong class="title">' . $program_label . ':</strong> <strong>' . $program . '</strong></p>';
                    echo '<p><strong class="title">Course Fee:</strong> <strong>$' . $course_fee_value . '</strong></p>';
                  echo '</div>';
                  echo '<div class="tbbodycontentcol col2">';
                    echo '<p><strong class="title">Course Code:</strong> <strong>' . $course_code_value . '</strong></p>';
                    echo '<p><strong class="title">Offering Code:</strong> <strong>' . $offering_code_value .'</strong></p>';
                  echo '</div>';
                  echo '<div class="tbbodycontentcol col3">';
                    echo '<p><strong class="title">State:</strong> <strong>' . $state . '</strong></p>';
                    echo '<p><strong class="title">Location Name:</strong> <strong>' . $location . '</strong></p>';
                    echo '<p><a href="?course_id=' . get_the_ID() . '&temp_id=' . $random_id . '&np=2" class="tutis-btn display-mobile">Enroll Now</a></p>';
                  echo '</div>';
                echo '</div>';
                echo '<div class="tbbodycontent cl' . $pid . ' tbbodycontentpre">';
                    echo '<div class="tbbodycontentcol">';
                            echo '<p><strong class="title">' . $pre_label . ':</strong> ' . get_the_content() . '</p>';
                    echo '</div>';
                echo '</div>';
            }
            wp_reset_postdata();

            echo"<div class='tutis-pagination'>";
                $big = 999999999; // need an unlikely integer
                echo paginate_links( array(
                    'base' => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
                    'format' => '?paged=%#%',
                    'current' => $paged,
                    'total' => $wp_query->max_num_pages,
                    'prev_text' => __('<i class="fa fa-angle-left"></i>'),
                    'next_text' => __('<i class="fa fa-angle-right"></i>'),
                ) );    
            echo"</div>";
        }
        else {
            echo '<h3>No Course Found!</h3>';
        }

        die();
    }

    public function get_student_list( $temp_id ) {
        $args = array(
            'post_type'     => 'student',
            'post_status'   => 'publish',
            'posts_per_page' => -1,
        );

        if( $temp_id ) {
            $meta_query[] = array(
                'relation' => 'AND',
                array(
                    'key'   =>  '_temp_id',
                    'value' => $temp_id,
                    'compare' => '=',
                )
            );
            $args['meta_query'] = $meta_query;
        }

        $wp_query = new WP_Query( $args );

        if ( $wp_query->have_posts() ) { 
            echo '<p class="multisteps-form__title"><strong>Student List</strong></p>';
            echo '<div class="student-result-list-container">';
            while ( $wp_query->have_posts() ) {
                $wp_query->the_post();
                $title = get_the_title();
                $student_id = get_the_ID();
                echo '<div class="student-result-list student-count">';
                    echo '<div>';
                        echo '<p>' . $title . ' <a href="' . $student_id . '" class="tutis-delete-student"><i class="fa fa-times" aria-hidden="true"></i></a></p>';
                    echo '</div>';  
                echo '</div>';
            }
            echo '</div>';
        }
    }
    public function display_student_result() {
        if( isset( $_POST['temp_id'] ) || isset( $_POST['course_id'] ) ) {
            $temp_id = $_POST['temp_id'];
            $course_id = $_POST['course_id'];
            $student_firstname = $_POST['student_firstname'];
            $student_lastname = $_POST['student_lastname'];
            $student_birth = $_POST['student_birth'];
            $student_email = $_POST['student_email'];
            $student_phone = $_POST['student_phone'];
            $student_usi = $_POST['student_usi'];
            $student_args = array(
                'post_title'    => $student_firstname . ' ' . $student_lastname,
                'post_status'   => 'publish',
                'post_type'   => 'student'
            );

            $post_id = wp_insert_post( $student_args );

            if( !is_wp_error( $post_id ) ) {
                update_post_meta( $post_id, '_first_name', $student_firstname );
                update_post_meta( $post_id, '_last_name', $student_lastname );
                update_post_meta( $post_id, '_birth_date', $student_birth );
                update_post_meta( $post_id, '_email', $student_email );
                update_post_meta( $post_id, '_phone', $student_phone );
                update_post_meta( $post_id, '_usi', $student_usi );
                update_post_meta( $post_id, '_temp_id', $temp_id );
                echo '<div class="student-result-container">';
                    $this->get_student_list( $temp_id );
                echo '</div>';
            } 
            else {
              echo $post_id->get_error_message();
            }
            
        }   
        die();
    }

    public function delete_student_list() {
        wp_delete_post($_POST['student_id']);
        $this->display_student_result();
        die();
    }

    public function get_selected_course() {
        $course_id = $_POST['course_id'];

        $args = array(
            'post_type'     => 'courses',
            'post_status'   => 'publish',
            'p' => $course_id,
        );

        $wp_query = new WP_Query( $args );
        if ( $wp_query->have_posts() ) { 
            echo '<div class="tutis-grid-table">';
            echo '<div class="heading tutis-hide-mobile">';
            echo '<strong>Selected Course Details</strong>';
            echo '</div>';

            echo '<div class="heading tutis-hide-mobile">';
            echo '<strong>QTY</strong>';
            echo '</div>';

            echo '<div class="heading tutis-hide-mobile">';
            echo '<strong>Fee</strong>';
            echo '</div>';

            while ( $wp_query->have_posts() ) {
                $wp_query->the_post();
                $pid = get_the_ID();
                $title = get_the_title();
                $course_code_value = get_post_meta( $pid, '_course_code', true );
                $start_date_value = get_post_meta( $pid, '_start_date', true );
                $end_date_value = get_post_meta( $pid, '_end_date', true );
                $start_date_value = date('d M Y', strtotime( $start_date_value ) );  
                $end_date_value= date('d M Y', strtotime( $end_date_value ) );
                $course_fee_value = get_post_meta( $pid, '_course_fee', true );
                $vacancies_value = get_post_meta( $pid, '_vacancies', true );
                $offering_code_value = get_post_meta( $pid, '_offering_code', true );
                $state = tutis_get_taxonomy_id_from_post_id( $pid, 'state','name' );
                $location = tutis_get_taxonomy_id_from_post_id( $pid, 'location','name' );
                $coursetype = tutis_get_taxonomy_id_from_post_id( $pid, 'coursetype','name' );
                $program = tutis_get_taxonomy_id_from_post_id( $pid, 'program','name' );
                $qty = tutis_count_student();
                $total = $qty * $course_fee_value;

                echo '<div class="tbbody first padded rl' . $pid . '"><span class="expand" id="cl' . $pid . '"><i class="fa fa-plus-circle" aria-hidden="true"></i></span><span><strong>' . $title . '</strong></span></div>';
                echo '<div class="tbbody padded tutis-hide-mobile rl' . $pid . '"><span><strong>' . $qty . '</strong></span></div>';  
                echo '<div class="tbbody padded tutis-hide-mobile rl' . $pid . '"><span><strong>$' . $total . '</strong></span></div>';
                echo '<div class="tbbodycontent cl' . $pid . ' tbbodycontent-pad-remove">';
                  echo '<div class="tbbodycontentcol col1">';
                    echo '<p><strong class="title">Course Code:</strong> <strong>' . $course_code_value . '</strong></p>';
                    echo '<p><strong class="title">Program:</strong> <strong>' . $program . '</strong></p>';
                    echo '<p><strong class="title">Offering Code:</strong> <strong>' . $offering_code_value .'</strong></p>';
                  echo '</div>';
                  echo '<div class="tbbodycontentcol col2">';
                    echo '<p><strong class="title">Start Date:</strong> <strong>' . $start_date_value . '</strong></p>';
                    echo '<p><strong class="title">End Date:</strong> <strong>' . $end_date_value . '</strong></p>';
                    echo '<p><strong class="title">Standard Course Fee:</strong> <strong>' . $course_fee_value . '</strong></p>';
                    
                  echo '</div>';
                  echo '<div class="tbbodycontentcol col3">';
                    echo '<p><strong class="title">Delivery State:</strong> <strong>' . $state . '</strong></p>';
                    echo '<p><strong class="title">Delivery Location:</strong> <strong>' . $location . '</strong></p>';
                    echo '<p><strong class="title">Discount:</strong> <strong>$140</strong></p>';
                  echo '</div>';
                echo '</div>';
                echo '<div class="tbbodycontent cl' . $pid . ' tbbodycontentpre">';
                    echo '<div class="tbbodycontentcol">';
                            echo '<p><strong class="title">Pre-requisits:</strong> ' . get_the_content() . '</p>';
                    echo '</div>';
                echo '</div>';
            }
            wp_reset_postdata();
        }
        else {
            echo '<h3>No Course Found!</h3>';
        }
        die();
    }

    public function get_subtotal() {
        $tutis_api_url = get_option('tutis_api_url');
        $tutis_api_key = get_option('tutis_api_key');
        $temp_id = $_POST['temp_id'];
        $course_id = $_POST['course_id'];
        $company_businessname = $_POST['company_businessname'];
        $company_abn = $_POST['company_abn'];
        $company_accountnumber = $_POST['company_accountnumber'];
        $company_post_delivery = $_POST['company_post_delivery'];
        $company_building = $_POST['company_building'];
        $company_st_number = $_POST['company_st_number'];
        $company_flat_unit = $_POST['company_flat_unit'];
        $company_st_name = $_POST['company_st_name'];
        $company_city = $_POST['company_city'];
        $company_state = $_POST['company_state'];
        $company_post_code = $_POST['company_post_code'];
        $company_country = $_POST['company_country'];
        $company_firstname = $_POST['company_firstname'];
        $company_lastname = $_POST['company_lastname'];
        $company_birth = $_POST['company_birth'];
        $company_birth = date('Y-m-d', strtotime( $company_birth ) );  
        $company_email = $_POST['company_email'];
        $company_phone = $_POST['company_phone'];
        $company_phone_prefix = $_POST['company_phone_prefix'];
        $apply_discount = $_POST['apply_discount'];
        $private_student = $_POST['private_student'];
        $student_count = tutis_count_student( $temp_id );
        if( $tutis_api_url && $tutis_api_key && $temp_id && $course_id ) {
            $url = $tutis_api_url . "/api/quotes";
            $curl = curl_init( $url );
            curl_setopt($curl, CURLOPT_URL, $url );
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            $headers = array(
               "x-api-key: " . $tutis_api_key . "",
               "Content-Type: application/json",
            );
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

            if( $private_student == '1' ) {
                $data = 
                '{
                    "students": ['.
                        get_student_data( $temp_id ) .
                    '],
                    "offeringCode": "' . get_post_meta( $course_id, '_offering_code', true ) .'",
                    "discountCode": "'. $apply_discount . '",
                    "organisation": {
                        "name": "' . $company_businessname . '",
                        "abn": "' . $company_abn . '",
                        "account": "' . $company_accountnumber . '",
                        "postalDeliveryBox": "' . $company_post_delivery . '",
                        "buildingPropertyName": "' . $company_building . '",
                        "streetNumber": "' . $company_st_number . '",
                        "flatUnitDetails": "' . $company_flat_unit . '",
                        "streetName": "' . $company_st_name . '",
                        "suburbCity": "' . $company_city . '",
                        "state": "' . $company_state . '",
                        "postCode": "' . $company_post_code . '",
                        "country": "' . $company_country . '"
                    },
                    "contact": {
                        "firstName": "' . $company_firstname . '",
                        "surname": "' . $company_lastname . '",
                        "dateOfBirth": "' . $company_birth . '",
                        "emailAddress": "'. $company_email .'",
                        "phoneType": "'. $company_phone_prefix .'",
                        "phone": "'. $company_phone .'"
                    }
                }';
            }
            else {
                $data = 
                '{
                    "students": ['.
                        get_student_data( $temp_id ) .
                    '],
                    "offeringCode": "' . get_post_meta( $course_id, '_offering_code', true ) .'",
                    "discountCode": "'. $apply_discount . '"
                }';
            }

            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

            $result = curl_exec( $curl );
            $obj = json_decode( $result, true );
            $total_amount =  $obj['totalAmount'];
            $gst =  $obj['gstAmount'];
            $courseFee =  $obj['courseFee'];
            $totalDiscount =  $obj['totalDiscount'];
            $quote_id =  $obj['id'];
            
            echo '<div class="tutis-total-result">';
                echo '<div class="left">';
                    echo '<a href="#applycontent" class="apply-discount-btn" id="applybtn1" data-lity>Apply Discount</a>';
                echo '</div>';
                echo '<div class="right">';
                    echo '<span id="quoteID1">' . $quote_id . '</span>';
                    echo '<span>$ ' . $totalDiscount . '</span>';
                    echo '<span id="coursefee1">$ ' . $courseFee . '</span>';
                echo '</div>';
                echo '<div class="left">';
                    echo '<span>GST:</span>';
                echo '</div>';
                echo '<div class="right">';
                    echo '<span>$ ' . $gst . '</span>';
                echo '</div>';
                echo '<div class="left">';
                    echo '<span>Total Payable (if you pay via Credit Card):</span>';
                echo '</div>';
                echo '<div class="right">';
                    echo '<span>$ <span class="tutis-total-amount">' . $total_amount . '</span></span>';
                echo '</div>';
                echo '<div class="left last">';
                    echo '<span><strong>Net Total:</strong></span>';
                echo '</div>';
                echo '<div class="right last">';
                    echo '<span><strong>$ ' . $total_amount . '</strong></span>';
                echo '</div>';
            echo '</div>';
            //var_dump($obj);
            curl_close($curl);
            die();
        }
    }

    public function insert_final_payment_info() {
        $tutis_api_url = get_option('tutis_api_url');
        $tutis_api_key = get_option('tutis_api_key');
        $temp_id = $_POST['temp_id'];
        $course_id = $_POST['course_id'];
        $securepay_token = $_POST['securepay_token'];
        $securepay_price = $_POST['securepay_price'];
        $securepay_card_type = $_POST['securepay_card_type'];
        $quote_token = $_POST['quote_token'];
        $invoice_number = $_POST['invoice_number'];
        
        if( $tutis_api_url && $tutis_api_key && $temp_id && $course_id && $quote_token ) {
            $url = $tutis_api_url . "/api/enrolments";
            $curl = curl_init( $url );
            curl_setopt($curl, CURLOPT_URL, $url );
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

            $headers = array(
               "x-api-key: " . $tutis_api_key . "",
               "Content-Type: application/json",
            );
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

            if( $securepay_token && $securepay_card_type ) {
                $data = 
                '{
                    "payment": {
                        "cardType": "' . $securepay_card_type . '",
                        "ip": "' . tutis_get_ip() . '",
                        "token": "' . $securepay_token . '"
                    },
                    "quoteId": "' . $quote_token . '",
                    "status": "CREATED"
                }';
            }
            else {
                 $data = 
                '{
                    "invoiceNumber": "' . $invoice_number . '",
                    "quoteId": "' . $quote_token . '",
                    "status": "CREATED"
                }';
            }

            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            if ( !curl_errno( $curl ) ) {
                $result = curl_exec( $curl );
                $obj = json_decode( $result, true );
                //var_dump($obj);
            }
            curl_close($curl);
            die();
        }
    }

    public function display_result() {
        if( isset( $_POST['action'] ) ) {
            if( isset( $_POST['program'] ) ) {
                $this->display_filter_result();
            }
            else if( isset( $_POST['student_id'] ) ) {
                $this->delete_student_list();
            }
            else if( isset( $_POST['payment_pagi'] ) ) {
                $this->get_selected_course();
            }
            else if( isset( $_POST['subtotal_pagi'] ) ) {
                $this->get_subtotal();
            }
            else if( isset( $_POST['final_payment_pagi'] ) ) {
                $this->insert_final_payment_info();
            }
            
            else {
                $this->display_student_result();
            }
        }   
    }

}


new Form_Ajax;