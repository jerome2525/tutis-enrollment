<?php
function tutis_select_field_option_tax( $type, $label, $defaul_id ) {
	$taxonomies = get_terms( array(
		'taxonomy' => $type,
		'hide_empty' => true
	) );

	if ( !empty( $taxonomies ) ) {
		$output = '';
		$output .= '<option value="">'. esc_html( $label ) .'</option>';
		foreach( $taxonomies as $category ) {
			if ( ! in_array( $category->slug, array( 'uncategorized' ) ) ) {
				$location_parent = '';
				$location_parent = get_term_meta( $category->term_id , 'tutis_location_parent', true );
				if( $defaul_id == $category->term_id ) {
					$output .= '<option parent_tax="'. $location_parent .'" value="'. $category->term_id .'" selected>'. $category->name .'</option>';
				}
				else {
	        		$output .= '<option parent_tax="'. $location_parent .'" value="'. $category->term_id .'">'. $category->name .'</option>';
	        	}
			}
		}
		return $output;
	}
}

function tutis_select_field_option_post_type( $type, $label, $course_name_id ) {
	if( $type ) {
		$args = array(
	        'post_type'     => $type,
	        'post_status'   => 'publish',
	        'posts_per_page' => -1
	    );

		$list = array();

	    $wp_query = new WP_Query( $args );
	    if ( $wp_query->have_posts() ) { 
	    	$output = '';
	 		$output .= '<option value="">'. $label .'</option>';
	      	while ( $wp_query->have_posts() ) {
				$wp_query->the_post();
				if( !in_array( get_the_title(), $list ) ) {
					$pid = get_the_ID();
					$title = get_the_title();
					$program_term_obj_list = get_the_terms( $pid, 'program' );
					$program_term_id = join(', ', wp_list_pluck( $program_term_obj_list, 'term_id') );
					//$program_term_id ='';
					if( $course_name_id == $pid ) {
						$output .= '<option value="' . $title . '" data-programid="' . $program_term_id . '" selected>'. $title .'</option>';
					}
					else {
						$output .= '<option value="' . $title . '" data-programid="' . $program_term_id . '">'. $title .'</option>';
					}
				}
				$list[] = get_the_title();
			}
			wp_reset_postdata();
			return $output;
	    }
	}	
}

function tutis_get_taxonomy_id_from_post_id( $pid, $taxname, $type ) {
	$terms = get_the_terms( $pid, $taxname );
	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
        foreach ( $terms as $term ) {
            $term_name = $term->name;
            $term_ID = $term->term_id;
            $term_slug = $term->slug;
            if( $type == 'name') {
            	return $term_name;
            }
            elseif( $type == 'slug') {
            	return $term_slug;
            }
            else {
				return $term_ID;
            }
        }
    }
}

function get_student_list_default( $temp_id ) {
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

function tutis_count_student( $temp_id ) {
    $course_id = $_GET['course_id'];
    if( $temp_id == null ) {
    	$temp_id = $_POST['temp_id'];
	}

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
    	return $wp_query->found_posts;
    }
}   

function get_selected_course_default() {
    $course_id = $_GET['course_id'];
    $temp_id = $_GET['temp_id'];
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
            $qty = tutis_count_student( $temp_id );
            $total = $qty * $course_fee_value;

            echo '<div class="tbbody first padded rl' . $pid . '"><span class="expand" id="cl' . $pid . '"><i class="fa fa-plus-circle" aria-hidden="true"></i></span><span><strong>' . $title . '</strong></span></div>';
            echo '<div class="tbbody padded tutis-hide-mobile rl' . $pid . '"><span><strong id="tbqty1">' . $qty . '</strong></span></div>';  
            echo '<div class="tbbody padded tutis-hide-mobile rl' . $pid . '"><span><strong id="tbtotal1">$' . $total . '</strong></span></div>';
            echo '<div class="tbbodycontent cl' . $pid . ' tbbodycontent-pad-remove">';
              echo '<div class="tbbodycontentcol col1">';
                echo '<p><strong class="title">Course Code:</strong> <strong>' . $course_code_value . '</strong></p>';
                echo '<p><strong class="title">' . $program_label . ':</strong> <strong>' . $program . '</strong></p>';
                echo '<p><strong class="title">Offering Code:</strong> <strong>' . $offering_code_value .'</strong></p>';
              echo '</div>';
              echo '<div class="tbbodycontentcol col2">';
                echo '<p><strong class="title">Start Date:</strong> <strong>' . $start_date_value . '</strong></p>';
                echo '<p><strong class="title">End Date:</strong> <strong>' . $end_date_value . '</strong></p>';
                
              echo '</div>';
              echo '<div class="tbbodycontentcol col3">';
                echo '<p><strong class="title">Delivery State:</strong> <strong>' . $state . '</strong></p>';
                echo '<p><strong class="title">Delivery Location:</strong> <strong>' . $location . '</strong></p>';
              echo '</div>';
            echo '</div>';
            echo '<div class="tbbodycontent cl' . $pid . ' tbbodycontentpre">';
                echo '<div class="tbbodycontentcol">';
                        echo '<p><strong class="title">' . $pre_label . ':</strong> ' . get_the_content() . '</p>';
                echo '</div>';
            echo '</div>';
		}
		echo '</div>';
        wp_reset_postdata();
    }
    else {
        echo '<h3>No Course Found!</h3>';
    }
} 

function tutis_delete_all_courses() {
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

function tutis_compare_delete_offering_id( $offeringId ) {
    $args = array(
        'post_type'     => 'courses',
        'post_status'   => 'publish',
        'posts_per_page' => -1,
        'meta_key'     => '_offering_id',
    	'meta_value'   => $offeringId, 
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

function tutis_offering_id_to_id( $offeringId ) {
    $args = array(
        'post_type'     => 'courses',
        'post_status'   => 'publish',
        'posts_per_page' => -1,
        'meta_key'     => '_offering_id',
    	'meta_value'   => $offeringId, 
    	'meta_compare' => '=',
    );

    $wp_query = new WP_Query( $args );
    if ( $wp_query->have_posts() ) { 
        while ( $wp_query->have_posts() ) {
            $wp_query->the_post();
            return get_the_ID();
        }
    }
}

function tutis_get_add_all_course_from_api( $courseName, $courseCode, $startDate, $endDate, $prerequisits, $fee, $vacancies, $offeringCode, $deliveryState, $deliveryLocation, $courseType, $offeringId, $programName, $public ) {

    $courses_args = array(
        'post_title'    => $courseName,
        'post_content'  => $prerequisits,
        'post_status'   => 'publish',
        'post_type'   => 'courses',
    );
     
    // Insert the post into the database.
    $post_id = wp_insert_post( $courses_args );
    if( !is_wp_error( $post_id ) ) {
        wp_set_object_terms( $post_id, $deliveryState, 'state', true );
        wp_set_object_terms( $post_id, $deliveryLocation, 'location', true );
        wp_set_object_terms( $post_id, $courseType, 'coursetype', true );
        wp_set_object_terms( $post_id, $programName, 'program', true );
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

function tutis_get_update_all_course_from_api( $courseName, $courseCode, $startDate, $endDate, $prerequisits, $fee, $vacancies, $offeringCode, $deliveryState, $deliveryLocation, $courseType, $offeringId, $pid, $programName, $public ) {

    $courses_args = array(
    	'ID'           =>  $pid,
        'post_title'    => $courseName,
        'post_content'  => $prerequisits,
        'post_status'   => 'publish',
        'post_type'   => 'courses',
    );
     
    // Insert the post into the database.
    $post_id = wp_update_post( $courses_args );
    if( !is_wp_error( $post_id ) ) {
    	wp_delete_object_term_relationships( $post_id, 'state' );
    	wp_delete_object_term_relationships( $post_id, 'location' );
    	wp_delete_object_term_relationships( $post_id, 'coursetype' );
        wp_set_object_terms( $post_id, $deliveryState, 'state', true );
        wp_set_object_terms( $post_id, $deliveryLocation, 'location', true );
        wp_set_object_terms( $post_id, $courseType, 'coursetype', true );
        wp_set_object_terms( $post_id, $programName, 'program', true );
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

function tutis_default_sync_course_api() {
    $tutis_api_url = get_option('tutis_api_url');
    $tutis_api_key = get_option('tutis_api_key');
    $tutis_api_size = get_option('tutis_api_size');
    if( $tutis_api_url && $tutis_api_key && $tutis_api_size ) {
        $ch = curl_init();
        $current_time = current_time( 'mysql' );
 		$last_updated = date('Y-m-d', strtotime( $current_time ) ); 
        curl_setopt($ch, CURLOPT_URL, $tutis_api_url . '/api/offerings/recent?lastDate=' . $last_updated . 'T00:00:00.000%2B00:00&size=500');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'x-api-key:' . $tutis_api_key . '';
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
        if ( !curl_errno( $ch ) ) {
            $result = curl_exec( $ch );
            $obj = json_decode( $result, true );
            if( isset( $obj['content'] ) ) {
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
                    $offeringId = $entry['offeringId'];
                    $programName = $entry['programName'];
                    $public = $entry['public'];
                    if( empty( $prerequisits ) ) {
                        $prerequisits = 'no prerequisite';
                    }

                    if( $startDate && $endDate && $vacancies ) {
                        if( tutis_offering_id_to_id( $offeringId ) ) {
                        	tutis_get_update_all_course_from_api( $courseName, $courseCode, $startDate, $endDate, $prerequisits, $fee, $vacancies, $offeringCode, $deliveryState, $deliveryLocation, $courseType, $offeringId, tutis_offering_id_to_id( $offeringId ), $programName, $public );
                        }
                        else {
                        	tutis_get_add_all_course_from_api( $courseName, $courseCode, $startDate, $endDate, $prerequisits, $fee, $vacancies, $offeringCode, $deliveryState, $deliveryLocation, $courseType, $offeringId, $programName, $public );
                        }

                    }
                }

                $current_time = current_time( 'mysql' );
                $last_updated = date('d M Y H:i:s', strtotime( $current_time ) ); 
                update_option('tutis_last_sync', $last_updated );

                if( get_option('tutis_auto_sync') == 1 ) {
                    $next_updated = date('d M Y H:i:s', strtotime('+1 hour', strtotime( $current_time ) ) );
                    update_option('tutis_next_sync', $next_updated ); 
                }
            }
            //var_dump($obj);
        }
        
        curl_close($ch);
    }
}

function tutis_get_pagination_total_number( $return_object ) {
	$tutis_api_url = get_option('tutis_api_url');
    $tutis_api_key = get_option('tutis_api_key');
    $tutis_api_size = get_option('tutis_api_size');
    if( $tutis_api_url && $tutis_api_key && $tutis_api_size ) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $tutis_api_url . '/api/offerings/all?page=0&size=' . $tutis_api_size );
        //curl_setopt($ch, CURLOPT_URL, 'http://msa-enrolment.mengz.ml/api/offerings/recent?lastDate=2020-03-10T00:00:00.000%2B00:00');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

        $headers = array();
        $headers[] = 'Accept: application/json';
        $headers[] = 'x-api-key:' . $tutis_api_key . '';
        curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );
    
        if ( curl_errno( $ch ) ) {
            return 'Error:' . curl_error( $ch );
        }
        else {
            $result = curl_exec( $ch );
            $obj = json_decode( $result, true );
            if( isset( $obj[$return_object] ) ) {
                return $obj[$return_object];
            }
        }
        
        curl_close($ch);
    }

}	

add_shortcode('tester_all','tester_all');
function tester_all() {
	ob_start();
	// Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, 'http://msa-enrolment.mengz.ml/api/offerings/all?page=0&size=300');
		//curl_setopt($ch, CURLOPT_URL, 'http://msa-enrolment.mengz.ml/api/offerings/recent?lastDate=2020-03-10T00:00:00.000%2B00:00');
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


		$headers = array();
		$headers[] = 'Accept: application/json';
		$headers[] = 'x-api-key: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJkZXBsb3ltZW50IjoiZnNhLXVhdCJ9.yhbslpM7FObiiaA_LcFmsIDwyHLGnR4lg-UeN5KxKlk';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);
		$obj = json_decode($result, true);
		$obj['totalPages'];
		if ( !curl_errno( $ch ) ) {
			foreach( $obj['content'] as $key => $entry) { 
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
	            $offeringId = $entry['offeringId'];
	            $programName = $entry['programName'];
	            if( empty( $prerequisits ) ) {
	                $prerequisits = 'no prerequisite';
	            }
	            if( $startDate && $endDate && $vacancies ) {
					echo '<p>offeringId: '. $offeringId . '</p>';
					echo '<p>Coursename: '. $courseName . '</p>';
					echo '<p>Program: ' . $programName . '</p>';
					echo '<p>startDate: ' . $startDate . '</p>';
				}
			}
		}
	
		var_dump($obj);
		curl_close($ch);
	return ob_get_clean();
}

add_shortcode('tester_recent','tester_recent');
function tester_recent() {
	ob_start();
	// Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
		$ch = curl_init();
		$current_time = current_time( 'mysql' );
 		$last_updated = date('Y-m-d', strtotime( $current_time ) ); 
		//curl_setopt($ch, CURLOPT_URL, 'http://msa-enrolment.mengz.ml/api/offerings/recent?lastDate='.$last_updated.'T00:00:00.000%2B00:00&page=0&size=500');
		curl_setopt($ch, CURLOPT_URL, 'http://msa-enrolment.mengz.ml/api/offerings/recent?lastDate=' . $last_updated . 'T00:00:00.000%2B00:00&size=500');
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


		$headers = array();
		$headers[] = 'Accept: application/json';
		$headers[] = 'x-api-key: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJkZXBsb3ltZW50IjoiZnNhLXVhdCJ9.yhbslpM7FObiiaA_LcFmsIDwyHLGnR4lg-UeN5KxKlk';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);
		$obj = json_decode($result, true);
		foreach( $obj['content'] as $key => $entry) { 
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
            $offeringId = $entry['offeringId'];
            if( empty( $prerequisits ) ) {
                $prerequisits = 'no prerequisite';
            }
            if( $startDate && $endDate && $vacancies ) {
				//echo '<p>offeringId: '. $offeringId . '</p>';
				//echo '<p>Coursename: '. $courseName . '</p>';
				//echo '<div>Description: ' . $prerequisits . '</div>';
			}
		}
	
		if (curl_errno($ch)) {
			echo 'Error:' . curl_error($ch);
		}
		
		//var_dump($obj);
		curl_close($ch);
	return ob_get_clean();
}

function tutis_get_subtotalxx() {
	$tutis_api_url = get_option('tutis_api_url');
    $tutis_api_key = get_option('tutis_api_key');
    if( $tutis_api_url && $tutis_api_key ) {
        $curl = curl_init( $tutis_api_url . '/api/quotes' );
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_POST, true);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		$headers = array(
		   "x-api-key: '" . $tutis_api_key . "'",
		   "Content-Type: application/json",
		);
		curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

$data = <<<DATA
{
    "students": [
        {
            "firstName": "firstName1",
            "surname": "surname1",
            "dateOfBirth": "2001-01-01",
            "emailAddress": "emailAddress1@tutis.com.au",
            "mobilePhone": "mobilePhone1",
            "usi": "usi1"
        },
        {
            "firstName": "firstName2",
            "surname": "surname2",
            "dateOfBirth": "2002-02-02",
            "emailAddress": "emailAddress2@tutis.com.au",
            "mobilePhone": "mobilePhone2",
            "usi": "usi2"
        },
        {
            "firstName": "firstName3",
            "surname": "surname3",
            "dateOfBirth": "2003-03-03",
            "emailAddress": "emailAddress3@tutis.com.au",
            "mobilePhone": "mobilePhone3",
            "usi": "usi3"
        }
    ],
    "offeringCode": "296495",
    "discountCode": "PLATINUM2021",
    "organisation": {
        "name": "name1",
        "abn": "abn1",
        "account": "account1",
        "postalDeliveryBox": "postalDeliveryBox1",
        "buildingPropertyName": "buildingPropertyName1",
        "streetNumber": "streetNumber1",
        "flatUnitDetails": "flatUnitDetails1",
        "streetName": "streetName1",
        "suburbCity": "suburbCity1",
        "state": "QLD",
        "postCode": "postCode1",
        "country": "AUS"
    },
    "contact": {
        "firstName": "firstName1",
        "surname": "surname1",
        "dateOfBirth": "2001-01-01",
        "emailAddress": "emailAddress1@tutis.com.au",
        "phoneType": "MOBILE",
        "phone": "phone1"
    }
}
DATA;

		curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

		//for debug only!
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

		$resp = curl_exec($curl);
		curl_close($curl);
		var_dump($resp);
    }

}	

function get_student_data( $temp_id ) {
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
    	$val = '';
        while ( $wp_query->have_posts() ) {
            $wp_query->the_post();
            $title = get_the_title();
            $student_id = get_the_ID();
            $first_name = get_post_meta( $student_id, '_first_name', true );
      		$last_name = get_post_meta( $student_id, '_last_name', true );
     		$birth_date = get_post_meta( $student_id, '_birth_date', true );
      		$email = get_post_meta( $student_id, '_email', true );
      		$phone = get_post_meta( $student_id, '_phone', true );
     		$usi = get_post_meta( $student_id, '_usi', true );
     		$birth_date = date('Y-m-d', strtotime( $birth_date ) );  
     		$val .= '{
	            "firstName": "' . $first_name . '",
	            "surname": "' . $last_name . '",
	            "dateOfBirth": "' . $birth_date . '",
	            "emailAddress": "' . $email . '",
	            "mobilePhone": "' . $phone . '",
	            "usi": "' . $usi . '"
	        }';
	        if ( ( $wp_query->current_post + 1 ) != ( $wp_query->post_count ) ) {
	        	$val .= ',';
	        }

        }
        return $val;
    }
}

function tutis_get_ip() {

	foreach (array('HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_FORWARDED', 'HTTP_X_CLUSTER_CLIENT_IP', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED', 'REMOTE_ADDR') as $key) {
		if (array_key_exists($key, $_SERVER) === true) {
			foreach (explode(',', $_SERVER[$key]) as $ip) {
				if (filter_var($ip, FILTER_VALIDATE_IP) !== false) {
					return $ip;
				}
			}
		}

  	}

}

function generate_securepay_api_details() {
    $tutis_api_url = get_option('tutis_api_url');
    $tutis_api_key = get_option('tutis_api_key');
    if( isset( $_GET['sapi'] ) ) {
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
	        }
	        
	        curl_close($ch);
	    }
	    else {
	    	echo '<p>Please Update first the Tutis api key and url!</p>';
	    }
	}
}

add_shortcode('tester_quote','tester_quote');
function tester_quote() {
	ob_start();
	
	$url = "http://msa-enrolment.mengz.ml/api/quotes";

	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

	$headers = array(
	   "x-api-key: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJkZXBsb3ltZW50IjoiZnNhLXVhdCJ9.yhbslpM7FObiiaA_LcFmsIDwyHLGnR4lg-UeN5KxKlk",
	   "Content-Type: application/json",
	);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

	$data = 
	'{
	    "students": ['.
	        get_student_data( '12c12fb3a38b1e90995e5ba20e029342' ) .
	    '],
	    "offeringCode": "296607",
	    "discountCode": "PLATINUM2021",
	    "organisation": {
	        "name": "name1",
	        "abn": "abn1",
	        "account": "account1",
	        "postalDeliveryBox": "postalDeliveryBox1",
	        "buildingPropertyName": "buildingPropertyName1",
	        "streetNumber": "streetNumber1",
	        "flatUnitDetails": "flatUnitDetails1",
	        "streetName": "streetName1",
	        "suburbCity": "suburbCity1",
	        "state": "QLD",
	        "postCode": "postCode1",
	        "country": "AUS"
	    },
	    "contact": {
	        "firstName": "firstName1",
	        "surname": "surname1",
	        "dateOfBirth": "2001-01-01",
	        "emailAddress": "emailAddress1@tutis.com.au",
	        "phoneType": "HOME",
	        "phone": "phone1"
	    }
	}';

	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

	//for debug only!
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

	$result = curl_exec( $curl );
    $obj = json_decode( $result, true );
	echo $obj['totalAmount'];
	curl_close($curl);
	var_dump($obj);
	return ob_get_clean();
}

function tester_quotexx() {
	ob_start();
	
	$url = "http://msa-enrolment.mengz.ml/api/quotes";

	$curl = curl_init($url);
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

	$headers = array(
	   "x-api-key: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJkZXBsb3ltZW50IjoiZnNhLXVhdCJ9.yhbslpM7FObiiaA_LcFmsIDwyHLGnR4lg-UeN5KxKlk",
	   "Content-Type: application/json",
	);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

	$data = 
	'{
	    "students": [
	        {
	            "firstName": "firstName1",
	            "surname": "surname1",
	            "dateOfBirth": "2001-01-01",
	            "emailAddress": "emailAddress1@tutis.com.au",
	            "mobilePhone": "mobilePhone1",
	            "usi": "usi1"
	        },
	        {
	            "firstName": "firstName2",
	            "surname": "surname2",
	            "dateOfBirth": "2002-02-02",
	            "emailAddress": "emailAddress2@tutis.com.au",
	            "mobilePhone": "mobilePhone2",
	            "usi": "usi2"
	        },
	        {
	            "firstName": "firstName3",
	            "surname": "surname3",
	            "dateOfBirth": "2003-03-03",
	            "emailAddress": "emailAddress3@tutis.com.au",
	            "mobilePhone": "mobilePhone3",
	            "usi": "usi3"
	        }
	    ],
	    "offeringCode": "296608",
	    "discountCode": "PLATINUM2021",
	    "organisation": {
	        "name": "name1",
	        "abn": "abn1",
	        "account": "account1",
	        "postalDeliveryBox": "postalDeliveryBox1",
	        "buildingPropertyName": "buildingPropertyName1",
	        "streetNumber": "streetNumber1",
	        "flatUnitDetails": "flatUnitDetails1",
	        "streetName": "streetName1",
	        "suburbCity": "suburbCity1",
	        "state": "QLD",
	        "postCode": "postCode1",
	        "country": "AUS"
	    },
	    "contact": {
	        "firstName": "firstName1",
	        "surname": "surname1",
	        "dateOfBirth": "2001-01-01",
	        "emailAddress": "emailAddress1@tutis.com.au",
	        "phoneType": "MOBILE",
	        "phone": "phone1"
	    }
	}';

	curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

	//for debug only!
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

	$result = curl_exec( $curl );
    $obj = json_decode( $result, true );
	echo $obj['totalAmount'];
	curl_close($curl);
	//var_dump($obj);
	return ob_get_clean();
}

add_shortcode('tester_payment','tester_payment');
function tester_payment() {
	ob_start();
	?>
	<form onsubmit="return false;">
      <div id="securepay-ui-container"></div>
      <button onclick="mySecurePayUI.tokenise();">Submit</button>
      <button onclick="mySecurePayUI.reset();">Reset</button>
    </form>
    <script id="securepay-ui-js" src="https://payments-stest.npe.auspost.zone/v3/ui/client/securepay-ui.min.js"></script>
    <script type="text/javascript">
    
    
    
      var mySecurePayUI = new securePayUI.init({
        containerId: 'securepay-ui-container',
        scriptId: 'securepay-ui-js',
        clientId: '0oaxb9i8P9vQdXTsn3l5',
        merchantCode: '5AR0055',
        card: {
            allowedCardTypes: ['visa', 'mastercard'],
            showCardIcons: false,
            onCardTypeChange: function(cardType) {
              // card type has changed
            },
            onBINChange: function(cardBIN) {
              // card BIN has changed
            },
            onFormValidityChange: function(valid) {
              // form validity has changed
            },
            onTokeniseSuccess: function(tokenisedCard) {
                console.log(tokenisedCard.token);          

              // card was successfully tokenised or saved card was successfully retrieved 
            },
            onTokeniseError: function(errors) {
              // tokenization failed
              console.log(errors);  
            }
        },
        style: {
          backgroundColor: 'rgba(135, 206, 250, 0.1)',
          label: {
            font: {
                family: 'Arial, Helvetica, sans-serif',
                size: '1.1rem',
                color: 'darkblue'
            }
          },
          input: {
           font: {
               family: 'Arial, Helvetica, sans-serif',
               size: '1.1rem',
               color: 'darkblue'
           }
         }  
        },
        onLoadComplete: function () {
          // the UI Component has successfully loaded and is ready to be interacted with
          console.log('bring it on');  
        }
      });
      
    </script>
	<?php
	return ob_get_clean();
}

?>