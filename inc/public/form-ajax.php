<?php
class Form_Ajax {

    public function __construct() {
        $this->load_hooks();
    }

    public function load_hooks() {
        add_action( 'wp_ajax_wsfilter', array( $this, 'display_result' ) ); 
        add_action( 'wp_ajax_nopriv_wsfilter', array( $this, 'display_result' ) );
    }

    public function update_start_date_timezone_based( $course_code_offering, $location_id, $program_id, $state_id, $start, $end ) {
        $args = array(
            'post_type'     => 'offerings',
            'post_status'   => 'publish',
            'posts_per_page' => -1,
        );


        if( $course_code_offering ) {
            $meta_query[] = array(
                'relation' => 'AND',
                array(
                    'key'   =>  '_course_code',
                    'value' => $course_code_offering,
                    'compare' => '=',
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

        $meta_query[] = array(
            'relation' => 'AND',
            array(
                'key'   =>  '_vacancies',
                'value' => 0,
                'compare' => '!=',
            )
        );

        if( $start && $end ) {
            $args['meta_query'] = $meta_query;
        }



        $wp_query2 = new WP_Query( $args );
        if ( $wp_query2->have_posts() ) { 
            while ( $wp_query2->have_posts() ) {
                $wp_query2->the_post();
                $pid = get_the_ID();
                $old_start_time = get_post_meta( $pid, '_start_date', true );
                $old_course_code = get_post_meta( $pid, '_course_code', true );
                $old_start = date('Y-m-d', strtotime( $old_start_time ) );
                update_post_meta( $pid, '_start_date_current_timezone', $old_start );
            }
        }
    }

    public function display_filter_result() {
        $program_id = $_POST['program'];
        $course_code_offering = $_POST['course_code_offering'];
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
            'post_type'     => 'offerings',
            'post_status'   => 'publish',
            'posts_per_page' => $per_page,
            'paged' => $paged,
            'meta_key'            => '_rounded_time',
            'orderby'             => 'meta_value',
            'order'               => 'ASC',
        );

        if( $course_code_offering ) {
            $meta_query[] = array(
                'relation' => 'AND',
                array(
                    'key'   =>  '_course_code',
                    'value' => $course_code_offering,
                    'compare' => '=',
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
        
        if( $start ) {
            $meta_query[] = array(
                'relation' => 'AND',
                array(
                    'key'   =>  '_start_date_current_timezone',
                    'value' => array( $start, $end ),
                    'compare' => 'BETWEEN',
                    'type' => 'DATE'
                )
            );
        }

        $meta_query[] = array(
            'relation' => 'AND',
            array(
                'key'   =>  '_vacancies',
                'value' => 0,
                'compare' => '!=',
            )
        );

        if( $start && $end ) {
            $args['meta_query'] = $meta_query;
        }


        $this->update_start_date_timezone_based( $course_code_offering, $location_id, $program_id, $state_id, $start, $end );
    
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
                $course_code_value = get_post_meta( $pid, '_course_code', true );
                $start_date_value = get_post_meta( $pid, '_start_date', true );
                $end_date_value = get_post_meta( $pid, '_end_date', true );
                $start_date_value = date('d M Y g:iA', strtotime( $start_date_value ) );  
                $start_date_valuenew = date('d M Y', strtotime( $start_date_value ) ); 
                $end_date_value= date('d M Y g:iA', strtotime( $end_date_value ) );
                $course_fee_value = round( get_post_meta( $pid, '_course_fee', true ) );
                $vacancies_value = get_post_meta( $pid, '_vacancies', true );
                $public_value = get_post_meta( $pid, '_public', true );
                $offering_code_value = get_post_meta( $pid, '_offering_code', true );
                $state = tutis_get_taxonomy_id_from_post_id( $pid, 'state','name' );
                $location = tutis_get_taxonomy_id_from_post_id( $pid, 'location','name' );
                $program = tutis_get_taxonomy_id_from_post_id( $pid, 'program','name' );
                $content = apply_filters( 'the_content', get_the_content() );
                $title = 'No Course assigned!';
                $country_name = tutis_get_country_name_from_state( $pid );
                $offering_id_value = get_post_meta( $pid, '_offering_id', true );
                if( empty( $state ) && empty( $location ) ) {
                    $state = 'Remote';
                }
                if( $course_code_value ) {
                    $title = tutis_get_course_name( $course_code_value );
                }

                $start_date_value_meta = date('Y-m-d', strtotime( $start_date_value ) );

                echo '<div class="tbbody first padded rl' . $pid . '"><span class="expand" id="cl' . $pid . '"><i class="fa fa-plus-circle" aria-hidden="true"></i></span><span><strong>' . $title . '</strong></span></div>';
                echo '<div class="tbbody padded tutis-hide-mobile rl' . $pid . '"><span><strong>' . $start_date_value . '</strong></span></div>';  
                echo '<div class="tbbody padded tutis-hide-mobile rl' . $pid . '"><span><strong>' . $end_date_value . '</strong></span></div>';
                echo '<div class="tbbody padded tutis-hide-mobile rl' . $pid . '"><span><strong>' . $state . '</strong></span></div>';
                echo '<div class="tbbody padded tutis-hide-mobile rl' . $pid . '"><span><strong>' . $vacancies_value . '</strong></span></div>';
                echo '<div class="tbbody tutis-hide-mobile rl' . $pid . '"><a href="?course_id=' . $pid . '&temp_id=' . $random_id . '&np=2" class="tutis-btn">Enroll Now</a></div>';
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
                    if( !empty( $state ) && !empty( $location ) ) {
                        echo '<p><strong class="title">Delivery State:</strong> <strong>' . $state . '</strong></p>';
                        echo '<p><strong class="title">Delivery Location:</strong> <strong>' . $location . '</strong></p>';
                        echo '<p><strong class="title">Delivery Country:</strong> <strong>' . $country_name . '</strong></p>';
                    }
                    else {
                        echo '<p><strong class="title">Location:</strong> <strong>Remote</strong></p>';
                    }
                    echo '<p><a href="?course_id=' . $pid . '&temp_id=' . $random_id . '&np=2" class="tutis-btn display-mobile">Enroll Now</a></p>';
                  echo '</div>';
                echo '</div>';
                echo '<div class="tbbodycontent cl' . $pid . ' tbbodycontentpre">';
                    echo '<div class="tbbodycontentcol">';
                            echo '<p><strong class="title">' . $pre_label . ':</strong> ' . $content . '</p>';
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
            echo '<h3>No Offering Found!</h3>';
        }

    }

    public function display_student_result() {
        $temp_id = $_POST['temp_id'];
        $course_id = $_POST['course_id'];
        $student_firstname = $_POST['student_firstname'];
        $student_lastname = $_POST['student_lastname'];
        $student_birth = $_POST['student_birth'];
        $student_email = $_POST['student_email'];
        $student_phone = $_POST['student_phone'];
        $student_usi = $_POST['student_usi'];
        if( $temp_id && $course_id ) {
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
                    get_student_list_default( $temp_id );
                echo '</div>';
            } 
            else {
              echo $post_id->get_error_message();
            }
            
        }   
    }

    public function delete_student_list() {
        if( $_POST['student_id'] ) {
            wp_delete_post($_POST['student_id']);
        }
        $this->display_student_result();
    }

    public function get_selected_course() {
        $course_id = $_POST['course_id'];
        if( $course_id ) {
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
                    $start_date_value = date('d M Y g:iA', strtotime( $start_date_value ) );  
                    $end_date_value= date('d M Y g:iA', strtotime( $end_date_value ) );
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
        }
    }

    public function hide_paybutton_during_error() {
        ?>
        <style type="text/css">
            #paylaterbtn1, 
            .tutis-btn.activate-payment-btn,
            .tutis-grid-table,
            .tutis-review-invoice-student-list,
            .multisteps-form__title,
            .multisteps-form__p {
                display: none !important;
            }
        </style>
        <?php
    }

    public function remove_dirty_string( $string ){
        $string = str_replace(array('[\', \']'), '', $string);
        $string = preg_replace('/\[.*\]/U', '', $string);
        $string = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', ' ', $string);
        $string = htmlentities($string, ENT_COMPAT, 'utf-8');
        $string = preg_replace('/&([a-z])(acute|uml|circ|grave|ring|cedil|slash|tilde|caron|lig|quot|rsquo);/i', '\\1', $string );
        $string = preg_replace(array('/[^a-z0-9]/i', '/[-]+/') , ' ', $string);
        return strtolower(trim($string, ' '));
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
        $company_birth_post = $_POST['company_birth'];
        $company_birth = str_replace('/', '-', $company_birth_post );  
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
            curl_setopt( $curl, CURLOPT_HTTPHEADER, $headers );

            if( $private_student == '1' ) {
                if( get_option('tutis_hide_abn') == 1 || empty( $company_abn ) ) {
                    $abn_field = '"abn": null,';
                }
                else {
                    $abn_field = '"abn": "' . $company_abn . '",';
                }

                if( get_option('tutis_hide_account') == 1 || empty( $company_accountnumber ) ) {
                    $account_field = '"account": null,';
                }
                else {
                    $account_field = '"account": "' . $company_accountnumber . '",';
                }

                if( get_option('tutis_hide_post_box') == 1 || empty( $company_post_delivery ) ) {
                    $postalDeliveryBox_field = '"postalDeliveryBox": null,';
                }
                else {
                    $postalDeliveryBox_field = '"postalDeliveryBox": "' . $company_post_delivery . '",';
                }

                if( get_option('tutis_hide_building') == 1 || empty( $company_building ) ) {
                    $buildingPropertyName_field = '"buildingPropertyName": null,';
                }
                else {
                    $buildingPropertyName_field = '"buildingPropertyName": "' . $company_building . '",';
                }

                if( get_option('tutis_hide_flat') == 1 || empty( $company_flat_unit ) ) {
                    $flatUnitDetails_field = '"flatUnitDetails": null,';
                }
                else {
                    $flatUnitDetails_field = '"flatUnitDetails": "' . $company_flat_unit . '",';
                }

                if( get_option('tutis_hide_country') == 1 || empty( $company_country ) ) {
                    $country_field = '"country": null';
                }
                else {
                    $country_field = '"country": "' . $company_country . '"';
                }

                $data = 
                '{
                    "students": ['.
                        get_student_data( $temp_id ) .
                    '],
                    "offeringCode": "' . get_post_meta( $course_id, '_offering_code', true ) .'",
                    "discountCode": "'. $apply_discount . '",
                    "organisation": {
                        "name": "' . $company_businessname . '",
                        ' . $abn_field . '
                        ' . $account_field . '
                        ' . $postalDeliveryBox_field . '
                        ' . $buildingPropertyName_field . '
                        "streetNumber": "' . $company_st_number . '",
                        ' . $flatUnitDetails_field . '
                        "streetName": "' . $company_st_name . '",
                        "suburbCity": "' . $company_city . '",
                        "state": "' . $company_state . '",
                        "postCode": "' . $company_post_code . '",
                        ' . $country_field . '
                    },
                    "contact": {
                        "firstName": "' . $company_firstname . '",
                        "surname": "' . $company_lastname . '",
                        "dateOfBirth": "' . $company_birth . '",
                        "emailAddress": "'. $company_email .'",
                        "contactType": "'. $company_phone_prefix .'",
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

            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
            if ( !curl_errno( $curl ) ) {
                $result = curl_exec( $curl );
                $http_code = curl_getinfo( $curl, CURLINFO_HTTP_CODE);
                $obj = json_decode( $result, true );
                $total_amount =  $obj['totalAmount'];
                if( $total_amount < 1 ) {
                    $total_amount = 0;
                }
                $gst =  $obj['gstAmount'];
                $courseFee =  $obj['courseFee'];
                $totalDiscount =  $obj['totalDiscount'];
                $quote_id =  $obj['id'];
                update_option( $temp_id, $total_amount );
                if( empty( $quote_id ) ) {
                    $quote_id = md5(microtime(true).random_int(0,99) );
                }
                if( empty( $obj ) ) {
                    $obj = 'no data';
                }
                $this->insert_enrollies_quote( $quote_id, $data, $http_code, $obj, 'quotes' );
                if( $http_code >= 200 && $http_code < 300 ) {
                    echo '<div class="tutis-total-result">';
                    echo get_option( $_GET['temp_id'] );
                    echo '<div class="left">';
                        echo '<a href="#applycontent" class="apply-discount-btn" id="applybtn1" data-lity>Apply Discount</a>';
                    echo '</div>';
                    echo '<div class="right">';
                        echo '<span id="quoteID1">' . $quote_id . '</span>';
                        echo '<span>$ ' . abs( $totalDiscount ) . '</span>';
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
                }
                else if( $http_code >= 500 && $http_code < 511 ) {
                    ?>
                    <div class="tutis-alert error quote-alert">
                        <p><strong>Sorry our system is currently unavailable, please try again later.</strong></p>
                    </div>
                    <?php
                     $this->hide_paybutton_during_error();
                }
                else {
                    ?>
                    <div class="tutis-alert error quote-alert">
                        <p><strong>An error has occured; we were unable to generate a quote for you.</strong></p>
                        <p><strong>Please check the data you entered:</strong></p>
                        <?php
                        if( $obj['errors'] ) {
                            foreach ( $obj['errors'] as $key => $error_value ) {
                                echo '<p>Error Field: ' . $this->remove_dirty_string( $error_value['field'] ) . '</p>';
                                echo '<p>Error message: '. $error_value['defaultMessage'] . '</p>';
                            }
                        }
                        else {
                            if( $obj['message'] ) {
                                echo '<p>'. $obj['message'] . '</p>';
                            }
                        }
                        ?>
                    </div>
                    <?php
                    $this->hide_paybutton_during_error();
                }
                
            }
            //var_dump($obj);
            curl_close($curl);
        }
    }

    public function auto_click_js_form() {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function($){
                setTimeout( function() {
                    $('#reviewbtn1').click();
                    $('.activate-payment-btn').click();
                }, 2000);
            });
        </script>
        <?php
    }

    function delete_enrolled_student( $temp_id ) {
        if( $temp_id ) {
            $args = array(
                'post_type'     => 'student',
                'post_status'   => 'publish',
                'posts_per_page' => -1,
                'meta_key'     => '_temp_id',
                'meta_value'   => $temp_id, 
                'meta_compare' => '=',
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
    }

    function insert_enrollies_quote( $order_id, $body_sent, $response_code, $response_body, $ptype ) {
        if( $order_id && $body_sent && $response_body && $response_code && $ptype ) {
            $enrollies_args = array(
                'post_title'    => $order_id,
                'post_status'   => 'publish',
                'post_type'   => $ptype
            );

            $post_id = wp_insert_post( $enrollies_args );

            if( !is_wp_error( $post_id ) ) {
                update_post_meta( $post_id, '_tutis_json_body_sent', $body_sent );
                update_post_meta( $post_id, '_tutis_response_code', $response_code );
                update_post_meta( $post_id, '_tutis_response_body', $response_body );
            } 
            else {
              echo $post_id->get_error_message();
            }
        }   
    }

    function insert_get_enrolment_data( $type ) {
        $tutis_api_url = get_option('tutis_api_url');
        $tutis_api_key = get_option('tutis_api_key');
        $temp_id = $_POST['temp_id'];
        $course_id = $_POST['course_id'];
        $securepay_token = $_POST['securepay_token'];
        $securepay_price = $_POST['securepay_price'];
        $securepay_card_type = $_POST['securepay_card_type'];
        $quote_token = $_POST['quote_token'];
        $invoice_number = $_POST['invoice_number'];
        $payment_type = $_POST['payment_type'];
        if( empty( $securepay_card_type ) ) {
            $securepay_card_type = 'UNKNOWN';
        }
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
            curl_setopt( $curl, CURLOPT_HTTPHEADER, $headers );

            if( !empty( $invoice_number ) ) {
                $data = 
                '{
                    "payment": null,
                    "quoteId": "' . $quote_token . '",
                    "reference": "' . $invoice_number . '"
                }';
            }
            else {
                $data = 
                '{
                    "payment": {
                        "type": "' . $payment_type . '",
                        "ip": "' . tutis_get_ip() . '",
                        "cardType": "' . $securepay_card_type . '",
                        "token": "' . $securepay_token . '",
                        "orderId": "' . $temp_id . '"
                    },
                    "quoteId": "' . $quote_token . '"
                }';
            }

            curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
            if ( !curl_errno( $curl ) ) {
                $result = curl_exec( $curl );
                $http_code = curl_getinfo( $curl, CURLINFO_HTTP_CODE);
                $obj = json_decode( $result, true );
                if( empty( $obj ) ) {
                    $obj = 'no data';
                }
                $order_id =  $obj['id'];
                $reference =  $obj['reference'];
                //var_dump($obj);
                update_option( $temp_id, $http_code  );
                $this->insert_enrollies_quote( $temp_id, $data, $http_code, $obj, 'enrollies' );
                $this->delete_enrolled_student( $temp_id );
                if( $type == 'reference' ) {
                    return $reference;
                }
                else {

                    return $order_id;
                }
            }
            curl_close($curl);
        }
    }

    function get_secure_pay_auths_data() {
        $tutis_api_url = get_option('tutis_api_url');
        $tutis_api_key = get_option('tutis_api_key');
        $temp_id = $_POST['temp_id'];
        $course_id = $_POST['course_id'];
        $securepay_token = $_POST['securepay_token'];
        $securepay_price = $_POST['securepay_price'];
        $securepay_card_type = $_POST['securepay_card_type'];
        $quote_token = $_POST['quote_token'];
        $tutis_secure_pay_client_secret = get_option('tutis_secure_pay_client_secret'); 
        $tutis_secure_pay_client_id = get_option('tutis_secure_pay_client_id');
        $tutis_secure_pay_merchant_code = get_option('tutis_secure_pay_merchant_code');
        $tutis_sandbox = get_option('tutis_sandbox');
        $tutis_ip = tutis_get_ip();
        $auth_token = tutis_get_secure_pay_auths_token( $tutis_api_url, $tutis_api_key, $tutis_secure_pay_client_secret, $tutis_secure_pay_client_id, $tutis_secure_pay_merchant_code, $temp_id );
        
        if( $tutis_api_url && $tutis_api_key && $tutis_secure_pay_client_secret && $tutis_secure_pay_client_id && $tutis_secure_pay_merchant_code && $auth_token ) {
            $url = get_option('tutis_secure_payment_url') . "/preauths";

            $curl = curl_init( $url );
            curl_setopt( $curl, CURLOPT_URL, $url );
            curl_setopt( $curl, CURLOPT_POST, true);
            curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true);

            $headers = array(
               "Content-Type: application/json",
               "Authorization: Bearer $auth_token",
            );

            curl_setopt( $curl, CURLOPT_HTTPHEADER, $headers );

            $data = array('amount' => $securepay_price, 'preAuthType' => 'INITIAL_AUTH', 'merchantCode' => $tutis_secure_pay_merchant_code, 'token' => $securepay_token, 'ip' => $tutis_ip, 'orderId' => $temp_id );

            $postdata = json_encode( $data );

            curl_setopt( $curl, CURLOPT_POSTFIELDS, $postdata );

            curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, 2 );
            curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, true );
            if ( !curl_errno( $curl ) ) {
                $result = curl_exec( $curl );
                $obj = json_decode( $result, true );
                $status =  $obj['status'];
                $createdAt =  $obj['createdAt'];
                $amount =  $obj['amount'];
                $bankTransactionId =  $obj['bankTransactionId'];
                $gatewayResponseCode =  $obj['gatewayResponseCode'];
                $gatewayResponseMessage =  $obj['gatewayResponseMessage'];
                $token =  $obj['token'];
                $orderId =  $obj['orderId'];
                if( $status ) {
                    $success_class = '';
                    if( $status !== 'failed' ) {
                        $success_class = 'success';
                        if( $orderId ) {
                            $this->insert_get_enrolment_data( 'orderid' );
                        }
                    }
                    else {
                        $this->auto_click_js_form();
                        tutis_insert_securepay_error( $obj, 'Auth' );
                        update_option( $temp_id, 'secure_cents_error');
                        update_option( $temp_id .'_response_message', $gatewayResponseMessage );
                    }
                
                ?>
                <div class="tutis-alert <?php echo $success_class; ?>">
                    <span class="closebtn" onclick="this.parentElement.style.display='none';">&times;</span>
                    <strong class="status"><?php echo $status; ?></strong><?php if( $gatewayResponseMessage ) { ?><strong>:</strong> <?php echo $gatewayResponseMessage; } ?>
                    <?php if( get_option( $temp_id .'_error_ref_id') ) { ?>
                        <strong class='error-ref-id'>Reference ID: <?php echo get_option( $temp_id .'_error_ref_id'); ?></strong></p>
                    <?php } ?>    
                </div>
                <?php
                }
                else {
                    update_option( $temp_id, 'secure_cents_error');
                    tutis_insert_securepay_error( $obj, 'Auth' );
                    update_option( $temp_id .'_response_message', $gatewayResponseMessage );
                    update_option( $temp_id .'_error_type', 'Auth' );
                }
                //var_dump( $obj );
            }
            curl_close( $curl );
        }
    }

    function display_result() {
        if( isset( $_POST['action'] ) ) {
            set_timezone_based_browser();
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
                if( get_option( $_POST['temp_id'] ) < 1 ) {
                    ?>
                    <style type="text/css">
                        .employer-btn.activate-payment-btn {
                            display: none !important;
                        }
                        button.#zero-btn1 {
                            display: inline-block !important;
                        }
                    </style>
                    <?php
                }
                else {
                    ?>
                     <style type="text/css">
                        .employer-btn.activate-payment-btn {
                            display: inline-block !important;
                        }
                        #zero-btn1 {
                            display: none !important;
                        }
                    </style>
                    <?php
                }
            }
            else if( isset( $_POST['final_payment_pagi'] ) ) {
                if( $_POST['invoice_number'] ) {
                    $this->insert_get_enrolment_data( 'reference' );
                }
                else {
                    if( $_POST['securepay_price'] < 1 ) {
                        $this->insert_get_enrolment_data( 'orderid' );
                    }
                    else {
                        $this->get_secure_pay_auths_data();
                    }
                }
            }

            else if( isset( $_POST['failed_securepay_pagi'] ) ) {
                if( $_POST['secure_error_code'] ) {
                    tutis_insert_securepay_error( $_POST['secure_error_code'], 'JS Iframe' );
                }
            }
            
            else {
                $this->display_student_result();
            }
            die();
        }   
    }

}


$tutis_form_ajax = new Form_Ajax();