<?php
function tutis_select_field_option_tax( $type, $label, $defaul_id ) {
    if( $type ) {
    	$taxonomies = get_terms( array(
    		'taxonomy' => $type,
    		'hide_empty' => true
    	) );

    	$output = '';
    	$output .= '<option value="">'. esc_html( $label ) .'</option>';
    	if ( !empty( $taxonomies ) ) {
    		
    		foreach( $taxonomies as $category ) {
    			if ( ! in_array( $category->slug, array( 'uncategorized' ) ) ) {
    				$location_parent = '';
    				$location_parent = get_term_meta( $category->term_id , 'tutis_location_parent', true );
                    $location_public = get_term_meta( $category->term_id , 'tutis_location_public', true );
                    $state_code = get_term_meta( $category->term_id , 'tutis_state_code', true );
                    if( empty( $location_public ) ) {
                        $location_public = 0;
                    } 
    				if( $defaul_id == $state_code ) {
    					$output .= '<option public_tax="' . $location_public . '" parent_tax="'. $location_parent .'" value="'. $category->term_id .'" selected>'. $category->name .'</option>';
    				}
    				else {
    	        		$output .= '<option public_tax="' . $location_public . '" parent_tax="'. $location_parent .'" value="'. $category->term_id .'">'. $category->name .'</option>';
    	        	}
    			}
    		}
    	}
    	return $output;
    }
}

function tutis_select_field_option_location( $type = 'location', $label, $defaul_location ) {
    if( $type ) {
        $taxonomies = get_terms( array(
            'taxonomy' => $type,
            'hide_empty' => true
        ) );

        $output = '';
        $output .= '<option value="">'. esc_html( $label ) .'</option>';
        if ( !empty( $taxonomies ) ) {
            
            foreach( $taxonomies as $category ) {
                if ( ! in_array( $category->slug, array( 'uncategorized' ) ) ) {
                    $location_parent = '';
                    $location_parent = get_term_meta( $category->term_id , 'tutis_location_parent', true );
                    $location_public = get_term_meta( $category->term_id , 'tutis_location_public', true );
                    $location_code = get_term_meta( $category->term_id , 'tutis_location_code', true );
                    if( empty( $location_public ) ) {
                        $location_public = 0;
                    } 
                    if( get_option('tutis_public_course') ) {
                        if( $location_public == 1 ) {
                            if( $defaul_location == $location_code ) {
                                $output .= '<option public_tax="' . $location_public . '" parent_tax="'. $location_parent .'" value="'. $category->term_id .'" selected>'. $category->name .'</option>';
                            }
                            else {
                                $output .= '<option public_tax="' . $location_public . '" parent_tax="'. $location_parent .'" value="'. $category->term_id .'">'. $category->name .'</option>';
                            }
                        }
                    }
                    else {
                        if( $defaul_location == $location_code ) {
                            $output .= '<option public_tax="' . $location_public . '" parent_tax="'. $location_parent .'" value="'. $category->term_id .'" selected>'. $category->name .'</option>';
                        }
                        else {
                            $output .= '<option public_tax="' . $location_public . '" parent_tax="'. $location_parent .'" value="'. $category->term_id .'">'. $category->name .'</option>';
                        }
                    }
                }
            }
        }
        return $output;
    }
}

function tutis_select_field_option_tax_program( $type, $label, $defaul_id ) {
    if( $type ) {
    	$taxonomies = get_terms( array(
    		'taxonomy' => $type,
    		'hide_empty' => true
    	) );

    	$output = '';
    	$output = '<option value="">'. esc_html( $label ) .'</option>';

    	if ( !empty( $taxonomies ) ) {
    		foreach( $taxonomies as $category ) {
    			if ( ! in_array( $category->slug, array( 'uncategorized' ) ) ) {
    				$tutis_program_code_id = get_term_meta( $category->term_id , 'tutis_program_code', true );
    				if( $defaul_id == $tutis_program_code_id ) {
    					$output .= '<option value="'. $category->term_id .'" selected>'. $category->name .'</option>';
    				}
    				else {
    	        		$output .= '<option value="'. $category->term_id .'">'. $category->name .'</option>';
    	        	}
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
	    $output = '';
		$output .= '<option value="">'. $label .'</option>';
		    if ( $wp_query->have_posts() ) { 
		    	
		      	while ( $wp_query->have_posts() ) {
					$wp_query->the_post();
					if( !in_array( get_the_title(), $list ) ) {
						$pid = get_the_ID();
						$title = get_the_title();
						$program_term_id = get_post_meta( $pid, '_program_id', true );
						$course_code_value = get_post_meta( $pid, '_course_code', true );
						if( $course_name_id == $course_code_value ) {
							$output .= '<option value="' . $course_code_value . '" data-programid="' . $program_term_id . '" data-coursecode="'. $course_code_value .'" class="default-selected" selected>'. $title .'</option>';
						}
						else {
							$output .= '<option value="' . $course_code_value . '" data-programid="' . $program_term_id . '" data-coursecode="'. $course_code_value .'">'. $title .'</option>';
						}
					}
					$list[] = get_the_title();
				}
				wp_reset_postdata();
				
		    }
		return $output;    
	}	
}

function tutis_get_taxonomy_id_from_post_id( $pid, $taxname, $type ) {
	$terms = get_the_terms( $pid, $taxname );
	if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
		$result = '';
        foreach ( $terms as $term ) {
            $term_name = $term->name;
            $term_ID = $term->term_id;
            $term_slug = $term->slug;
            if( $type == 'name') {
            	$result .= ", $term_name";
            }
            elseif( $type == 'slug') {
            	$result .= ", $term_slug";
            }
            else {
				$result .= ", $term_ID";
            }
        }
        return  $result = substr( $result, 1 );
    }
}

function tutis_get_country_name_from_state( $pid ) {
    if( $pid ) {
        $terms = get_the_terms( $pid, 'state' );
        if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
            $result = '';
            foreach ( $terms as $term ) {
                $term_ID = $term->term_id;
                return get_term_meta( $term_ID, 'tutis_country_name', true );
                
            }
        }
    }
}

function get_student_list_default( $temp_id ) {
    if( $temp_id ) {
        $args = array(
            'post_type'     => 'student',
            'post_status'   => 'publish',
            'posts_per_page' => -1,
        );

        
        $meta_query[] = array(
            'relation' => 'AND',
            array(
                'key'   =>  '_temp_id',
                'value' => $temp_id,
                'compare' => '=',
            )
        );
        $args['meta_query'] = $meta_query;

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
                        echo '<p>' . $title . ' <a href="' . $student_id . '" class="tutis-delete-student" title="Are you sure that you wanted to delete this student?"><i class="fa fa-times" aria-hidden="true"></i></a></p>';
                    echo '</div>';  
                echo '</div>';
            }
    		echo '</div>';
        }
    }
}

function tutis_count_student( $temp_id ) {
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

function tutis_get_course_name( $code ) {
    if( $code ) {
        $args = array(
            'post_type'     => 'courses',
            'post_status'   => 'publish',
            'posts_per_page' => -1,
        );

        $meta_query[] = array(
            'relation' => 'AND',
            array(
                'key'   =>  '_course_code',
                'value' => $code,
                'compare' => 'IN'
            )
        );

        $args['meta_query'] = $meta_query;

        $wp_query = new WP_Query( $args );
        if ( $wp_query->have_posts() ) { 
            while ( $wp_query->have_posts() ) {
                $wp_query->the_post();
                if( get_the_title() ) {
                    return get_the_title();
                }
            }
        }
    }
}   

function count_student_vacancies( $temp_id ) {
    if( $temp_id ) {
        $args = array(
            'post_type'     => 'student',
            'post_status'   => 'publish',
            'posts_per_page' => -1,
        );

        
        $meta_query[] = array(
            'relation' => 'AND',
            array(
                'key'   =>  '_temp_id',
                'value' => $temp_id,
                'compare' => '=',
            )
        );
        $args['meta_query'] = $meta_query;

        $wp_query = new WP_Query( $args );

        if ( $wp_query->have_posts() ) { 
            return $wp_query->found_posts;
        }
    }
}

function set_timezone_based_browser() {
    if( $_COOKIE['tutis_offset_time'] ) {
        $timezone_offset_minutes = $_COOKIE['tutis_offset_time']; 
        $timezone_name = timezone_name_from_abbr("", $timezone_offset_minutes * 60, false);
        date_default_timezone_set( $timezone_name );
    }
}

function get_selected_course_default() {
    $course_id = $_GET['course_id'];
    $temp_id = $_GET['temp_id'];
    if( $course_id && $temp_id ) {
        set_timezone_based_browser();
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
                $course_code_value = get_post_meta( $pid, '_course_code', true );
                $start_date_value = get_post_meta( $pid, '_start_date', true );
                $end_date_value = get_post_meta( $pid, '_end_date', true );
                $start_date_value = date('d M Y g:iA', strtotime( $start_date_value ) );  
                $end_date_value= date('d M Y g:iA', strtotime( $end_date_value ) );
                $course_fee_value = round( get_post_meta( $pid, '_course_fee', true ) );
                $vacancies_value = get_post_meta( $pid, '_vacancies', true );
                $offering_code_value = get_post_meta( $pid, '_offering_code', true );
                $state = tutis_get_taxonomy_id_from_post_id( $pid, 'state','name' );
                $location = tutis_get_taxonomy_id_from_post_id( $pid, 'location','name' );
                $coursetype = tutis_get_taxonomy_id_from_post_id( $pid, 'coursetype','name' );
                $program = tutis_get_taxonomy_id_from_post_id( $pid, 'program','name' );
                $qty = tutis_count_student( $temp_id );
                $total = $qty * $course_fee_value;
                $content = apply_filters( 'the_content', get_the_content() );
                $title = 'No Course assigned!';
    			$country_name = tutis_get_country_name_from_state( $pid );
                if( $course_code_value ) {
                    $title = tutis_get_course_name( $course_code_value );
                }

                if( empty( $state ) && empty( $location ) ) {
                    $state = 'Remote';
                }

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
                    if( !empty( $state ) && !empty( $location ) ) {
                        echo '<p><strong class="title">Delivery State:</strong> <strong>' . $state . '</strong></p>';
                        echo '<p><strong class="title">Delivery Location:</strong> <strong>' . $location . '</strong></p>';
                        echo '<p><strong class="title">Delivery Country:</strong> <strong>' . $country_name . '</strong></p>';
                    }
                    else {
                        echo '<p><strong class="title">Location:</strong> <strong>Remote</strong></p>';
                    }
                  echo '</div>';
                echo '</div>';
                echo '<div class="tbbodycontent cl' . $pid . ' tbbodycontentpre">';
                    echo '<div class="tbbodycontentcol">';
                            echo '<p><strong class="title">' . $pre_label . ':</strong> ' . $content . '</p>';
                    echo '</div>';
                echo '</div>';
    		}
    		echo '</div>';
            wp_reset_postdata();
        }
        else {
            echo '<h3>No Offering Found!</h3>';
        }
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
    if( $offeringId ) {
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
}

function tutis_offering_id_to_id( $offeringId ) {
	if( $offeringId ) {
	    $args = array(
	        'post_type'     => 'offerings',
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
	            if( get_the_ID() ) {
	            	return get_the_ID();
	        	}
	        }
	    }
	}
}

function tutis_get_add_all_offerings_from_api( $courseCode, $startDate, $endDate, $prerequisits, $fee, $vacancies, $offeringCode, $deliveryLocation, $offeringId, $public ) {
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
            tutis_compare_program_course( $courseCode, $post_id );
            tutis_save_state_location_offerings( $deliveryLocation, $post_id );
        }
        else {
            //there was an error in the post insertion, 
            echo $post_id->get_error_message();
        }
    }
}

function tutis_get_update_all_offerings_from_api( $courseCode, $startDate, $endDate, $prerequisits, $fee, $vacancies, $offeringCode, $deliveryLocation, $offeringId, $pid, $public ) {
    if( $offeringId && $offeringCode && $pid ) {
        $courses_args = array(
        	'ID'           =>  $pid,
            'post_title'    => $offeringCode,
            'post_content'  => $prerequisits,
            'post_status'   => 'publish',
            'post_type'   => 'offerings',
        );
         
        // Insert the post into the database.
        $post_id = wp_update_post( $courses_args );
        if( !is_wp_error( $post_id ) ) {
            update_post_meta( $post_id, '_course_code', $courseCode );
            update_post_meta( $post_id, '_start_date', $startDate );
            update_post_meta( $post_id, '_end_date', $endDate );
            update_post_meta( $post_id, '_course_fee', $fee );
            update_post_meta( $post_id, '_vacancies', $vacancies );
            update_post_meta( $post_id, '_offering_code', $offeringCode );
            update_post_meta( $post_id, '_offering_id', $offeringId );
            update_post_meta( $post_id, '_public', $public );
            update_post_meta( $post_id, '_location_code', $deliveryLocation );
            tutis_save_state_location_offerings( $deliveryLocation, $post_id );
        }
        else {
            //there was an error in the post insertion, 
            echo $post_id->get_error_message();
        }
    }
}

function tutis_auto_update_day_time_flag() {
    $current_time = current_time( 'mysql' );
    $last_updated = date('d M Y H:i:s', strtotime( $current_time ) ); 
    update_option('tutis_last_sync', $last_updated );
    $next_updated = date('d M Y H:i:s', strtotime('+1 hour', strtotime( $current_time ) ) );
    update_option('tutis_next_sync', $next_updated ); 
    $auto_run_last_updated = date('Y-m-d', strtotime( $current_time ) ); 
    update_option('tutis_auto_next_sync', $auto_run_last_updated ); 
}

function tutis_auto_update_offerings() {
    $tutis_api_url = get_option('tutis_api_url');
    $tutis_api_key = get_option('tutis_api_key');
    $tutis_api_size = get_option('tutis_api_size');
    $tutis_auto_next_sync = get_option('tutis_auto_next_sync');
    //$tutis_auto_next_sync = '2021-07-16'; //testing purpose
    //$current_time = current_time( 'mysql' );
    //$last_updated = date('Y-m-d', strtotime( $current_time ) ); 
    if( $tutis_api_url && $tutis_api_key && $tutis_api_size && $tutis_auto_next_sync ) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $tutis_api_url . '/api/offerings/recent?lastDate=' . $tutis_auto_next_sync . 'T00:00:00.000Z&size=2000');
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
                        if( tutis_offering_id_to_id( $offeringId ) ) {
                        	tutis_get_update_all_offerings_from_api( $courseCode, $startDate, $endDate, $prerequisits, $fee, $vacancies, $offeringCode, $deliveryLocation, $offeringId, tutis_offering_id_to_id( $offeringId ), $public );
                        }
                        else {
                        	tutis_get_add_all_offerings_from_api( $courseCode, $startDate, $endDate, $prerequisits, $fee, $vacancies, $offeringCode, $deliveryLocation, $offeringId, $public );
                        }

                    }
                }
                tutis_auto_update_day_time_flag();
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

function get_student_data( $temp_id ) {
    if( $temp_id ) {
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
                $birth_date = str_replace('/', '-', $birth_date );  
         		$birth_date = date('Y-m-d', strtotime( $birth_date ) );  
                if( empty( $usi ) && $phone ) {
                    $val .= '{
                        "firstName": "' . $first_name . '",
                        "surname": "' . $last_name . '",
                        "dateOfBirth": "' . $birth_date . '",
                        "emailAddress": "' . $email . '",
                        "mobilePhone": "' . $phone . '"
                    }';
                }
                else if( empty( $phone ) && $usi ) {
                    $val .= '{
                        "firstName": "' . $first_name . '",
                        "surname": "' . $last_name . '",
                        "dateOfBirth": "' . $birth_date . '",
                        "emailAddress": "' . $email . '",
                        "usi": "' . $usi . '"
                    }';
                }
                else if( empty( $phone ) && empty( $usi ) ) {
                    $val .= '{
                        "firstName": "' . $first_name . '",
                        "surname": "' . $last_name . '",
                        "dateOfBirth": "' . $birth_date . '",
                        "emailAddress": "' . $email . '"
                    }';
                }
                else {
             		$val .= '{
        	            "firstName": "' . $first_name . '",
        	            "surname": "' . $last_name . '",
        	            "dateOfBirth": "' . $birth_date . '",
        	            "emailAddress": "' . $email . '",
        	            "mobilePhone": "' . $phone . '",
        	            "usi": "' . $usi . '"
        	        }';
                }
    	        if ( ( $wp_query->current_post + 1 ) != ( $wp_query->post_count ) ) {
    	        	$val .= ',';
    	        }

            }
            return $val;
        }
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

function tutis_generate_settings_payment_api() {
    $tutis_api_url = get_option('tutis_api_url');
    $tutis_api_key = get_option('tutis_api_key');
    if( $tutis_api_url && $tutis_api_key ) {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $tutis_api_url . '/api/setting/payments' );
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
            if( isset( $obj ) ) {
                foreach( $obj as $key => $entry ) { 
    	            $accessTokenUri = $entry['accessTokenUrl'];
    	       		$merchantCode = $entry['merchantCode'];
    	           	$clientId = $entry['clientId'];
    	       		$clientSecret = $entry['clientSecret'];
                    $type = $entry['type'];
                    $mode = $entry['apiMode'];
                    $paymentUrl = $entry['paymentUrl'];
                    $js_url = $entry['jsUrl'];
                    if( $type == 'SECURE_PAY') {
        	          	update_option('tutis_secure_pay_client_id', $clientId );
        	            update_option('tutis_secure_pay_merchant_code', $merchantCode );
                        update_option('tutis_secure_pay_client_secret', $clientSecret );
                        update_option('tutis_secure_pay_access_url', $accessTokenUri );
                        update_option('tutis_secure_payment_url', $paymentUrl );
                        update_option('tutis_secure_js_url', $js_url );
                        if( $mode == 'SANDBOX') {
                            update_option('tutis_sandbox', '0' );
                        }
                        else {
                            update_option('tutis_sandbox', '1' );
                        }
                    }
                }
            }
            //var_dump($obj);
        }
        curl_close($ch);
    }
    else {
    	echo '<p>Please Update first the Tutis api key and url!</p>';
    }
}

function tutis_set_offerings_to_program( $code, $term_id, $course_name ) {
    if( $code && $term_id ) {
        $args = array(
            'post_type'     => 'offerings',
            'post_status'   => 'publish',
            'posts_per_page' => -1,
        );

        $meta_query[] = array(
            'relation' => 'AND',
            array(
                'key'   =>  '_course_code',
                'value' => $code,
                'compare' => 'IN'
            )
        );

        $args['meta_query'] = $meta_query;

        $wp_query = new WP_Query( $args );
        if ( $wp_query->have_posts() ) { 
            while ( $wp_query->have_posts() ) {
                $wp_query->the_post();
                $pid = get_the_ID();
                $start_date_value = get_post_meta( $pid, '_start_date', true );
                update_post_meta( $pid, '_rounded_time', strtotime( $start_date_value ) . '' . $course_name );
                wp_set_post_terms( $pid, $term_id, 'program' );
            }
        }
    }
}

function tutis_insert_courses_from_program_api( $course_name, $code, $type, $term_id ) {
    if( $course_name ) {
        $courses_args = array(
            'post_title'    => $course_name,
            'post_status'   => 'publish',
            'post_type'   => 'courses',
        );
         
        // Insert the post into the database.
        $post_id = wp_insert_post( $courses_args );
        if( !is_wp_error( $post_id ) ) {   
            add_post_meta( $post_id, '_program_id', $term_id, true );      
            add_post_meta( $post_id, '_course_code', $code, true );
            add_post_meta( $post_id, '_course_type', $type, true );
        }
        else {
            //there was an error in the post insertion, 
            echo $post_id->get_error_message();
        }
    }
}

function tutis_insert_program_to_course() {
    $terms = get_terms( array(
        'taxonomy' => 'program',
        'hide_empty' => false
    ) );

    foreach ( $terms as $term ) {
        $term_id = $term->term_id; 
        $tutis_course_program_data = get_term_meta( $term_id, 'tutis_course_program_data', true );   
        foreach ( $tutis_course_program_data as $key => $value ) {
            $course_name = $value['name'];
            $code = $value['code'];
            $type = $value['type'];
            $course_archived = $value['archived'];
            if( $course_archived === false ) {
                tutis_set_offerings_to_program( $code, $term_id, $course_name );
                tutis_insert_courses_from_program_api( $course_name, $code, $type, $term_id );
            }
        }
    } 
}

function tutis_compare_program_course( $course_code, $pid ) {
    if( $course_code && $pid ) {
        $terms = get_terms( array(
            'taxonomy' => 'program',
            'hide_empty' => false
        ) );

        foreach ( $terms as $term ) {
            $term_id = $term->term_id; 
            $tutis_course_program_data = get_term_meta( $term_id, 'tutis_course_program_data', true );   
            foreach ( $tutis_course_program_data as $key => $value ) {
                $course_name = $value['name'];
                $code = $value['code'];
                $type = $value['type'];
                if( $course_code == $code ) {
                    wp_set_post_terms( $pid, $term_id, 'program' );
                }
            }
        } 
    }
}

function tutis_get_location_id_from_code( $location_code ) {
    if( $location_code ) {
        $terms = get_terms( array(
            'taxonomy' => 'location',
            'hide_empty' => false
        ) );

        foreach ( $terms as $term ) {
            $location_id = $term->term_id;
            $code = get_term_meta( $location_id, 'tutis_location_code', true ); 
            if( $location_code == $code ) {
                return $location_id;
            }
        }
    }
}

function tutis_save_state_location_offerings( $location_code, $pid ) {
    if( $location_code && $pid ) {
        $terms = get_terms( array(
            'taxonomy' => 'location',
            'hide_empty' => false
        ) );

        foreach ( $terms as $term ) {
            $location_id = $term->term_id; 
            $code = get_term_meta( $location_id, 'tutis_location_code', true ); 
            $state_id = get_term_meta( $location_id, 'tutis_location_parent', true );  
            if( $location_code == $code ) {
                wp_set_post_terms( $pid, $state_id, 'state' );
                wp_set_post_terms( $pid, $location_id, 'location' );
            }
        } 
    }
}

function tutis_set_offerings_to_location( $location_code, $location_id, $state_id ) {
    if( $location_code && $location_id && $state_id ) {
        $args = array(
            'post_type'     => 'offerings',
            'post_status'   => 'publish',
            'posts_per_page' => -1,
        );

        $meta_query[] = array(
            'relation' => 'AND',
            array(
                'key'   =>  '_location_code',
                'value' => $location_code,
                'compare' => 'IN'
            )
        );

        $args['meta_query'] = $meta_query;

        $wp_query = new WP_Query( $args );
        if ( $wp_query->have_posts() ) { 
            while ( $wp_query->have_posts() ) {
                $wp_query->the_post();
                $pid = get_the_ID();
                wp_set_post_terms( $pid, $location_id, 'location' );
                wp_set_post_terms( $pid, $state_id, 'state' );
            }
        }
    }
}

function tutis_insert_location_from_api( $state_id ) {
    if( $state_id ) {
        $tutis_state_location_data = get_term_meta( $state_id, 'tutis_location_data', true );   
        foreach ( $tutis_state_location_data as $key => $value ) {
            $location_name = $value['name'];
            $location_code = $value['code'];
            $location_public = $value['public'];
            $location_id_temp = wp_insert_term(
                $location_name,  
                'location'
            );

            if( ! is_wp_error( $location_id_temp ) ) {
                $location_id = $location_id_temp['term_id'];
                update_term_meta( $location_id, 'tutis_location_code', $location_code );
                update_term_meta( $location_id, 'tutis_location_public', $location_public );
                update_term_meta( $location_id, 'tutis_location_parent', $state_id );
                tutis_set_offerings_to_location( $location_code, $location_id, $state_id );
            }
        }
    }
}

function tutis_compare_state_code_tax_state( $state_code ) {
    if( $state_code ) {
        $terms = get_terms( array(
            'taxonomy' => 'state',
            'hide_empty' => false
        ) );

        foreach ( $terms as $term ) { 
            $state_code_meta = get_term_meta( $term->term_id, 'tutis_state_code', true );  
            if( $state_code == $state_code_meta ) {
                return $term->term_id;
            }
        } 
    }
}

function tutis_compare_location_data_and_code( $location_code ) {
    if( $location_code ) {
        $terms = get_terms( array(
            'taxonomy' => 'location',
            'hide_empty' => false
        ) );

        foreach ( $terms as $term ) { 
            $tutis_location_code_meta = get_term_meta( $term->term_id, 'tutis_location_code', true );  
            if( $location_code == $tutis_location_code_meta ) {
                return $term->term_id;
            }
        } 
    }
} 

function tutis_set_offerings_to_state_location( $location_code, $location_id, $state_id ) {
    if( $location_code && $location_id && $state_id ) {
        $args = array(
            'post_type'     => 'offerings',
            'post_status'   => 'publish',
            'posts_per_page' => -1,
        );

        $meta_query[] = array(
            'relation' => 'AND',
            array(
                'key'   =>  '_location_code',
                'value' => $location_code,
                'compare' => 'IN'
            )
        );

        $args['meta_query'] = $meta_query;

        $wp_query = new WP_Query( $args );
        if ( $wp_query->have_posts() ) { 
            while ( $wp_query->have_posts() ) {
                $wp_query->the_post();
                $pid = get_the_ID();
                wp_set_post_terms( $pid, $location_id, 'location' );
                wp_set_post_terms( $pid, $state_id, 'state' );
            }
        }
    }
}

function tutis_update_location_from_state( $state_id, $tutis_state_location_data ) {
    if( $state_id && $tutis_state_location_data ) { 
        foreach ( $tutis_state_location_data as $key => $value ) {
            $location_name = $value['name'];
            $location_code = $value['code'];
            $location_public = $value['public'];
            $location_archived = $value['archived'];
            if( $location_archived === false ) {
                if( tutis_compare_location_data_and_code( $location_code ) ) {
                    $update = wp_update_term( tutis_compare_location_data_and_code( $location_code ), 'location', array(
                        'name' => $location_name,
                        'slug' => $location_name
                    ) );

                    update_term_meta( tutis_compare_location_data_and_code( $location_code ), 'tutis_location_code', $location_code );
                    update_term_meta( tutis_compare_location_data_and_code( $location_code ), 'tutis_location_public', $location_public );
                    update_term_meta( tutis_compare_location_data_and_code( $location_code ), 'tutis_location_parent', $state_id );  
                    tutis_set_offerings_to_state_location( $location_code, tutis_compare_location_data_and_code( $location_code ), $state_id );
                }
                else {
                    $location_id_temp = wp_insert_term(
                        $location_name,  
                        'location'
                    );

                    if( ! is_wp_error( $location_id_temp ) ) {
                        $location_id = $location_id_temp['term_id'];
                        update_term_meta( $location_id, 'tutis_location_code', $location_code );
                        update_term_meta( $location_id, 'tutis_location_public', $location_public );
                        update_term_meta( $location_id, 'tutis_location_parent', $state_id );
                        tutis_set_offerings_to_state_location( $location_code, $location_id, $state_id );
                    }
                }
            }
        }
    }
}

function tutis_update_state_country_from_api() {
    $tutis_api_url = get_option('tutis_api_url');
    $tutis_api_key = get_option('tutis_api_key');
    $tutis_api_size = get_option('tutis_api_size');
    $current_time = current_time( 'mysql' );
    $last_updated = date('Y-m-d', strtotime( $current_time ) );
    $tutis_auto_next_sync = get_option('tutis_auto_next_sync');
    //$tutis_auto_next_sync = '2021-07-13'; //testing purpose
    if( $tutis_api_url && $tutis_api_key && $tutis_api_size && $tutis_auto_next_sync ) {
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $tutis_api_url . '/api/states/recent?lastDate=' . $tutis_auto_next_sync . 'T00:00:00.000Z');
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET');

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
            if( isset( $obj ) ) {
                foreach( $obj as $key => $entry ) { 
                    $countryCode = $entry['countryCode'];
                    $countryName = $entry['countryName'];
                    $stateCode = $entry['stateCode'];
                    $stateName = $entry['stateName'];
                    $locations = $entry['locations'];
                    if( tutis_compare_state_code_tax_state( $stateCode ) ) {
                        $update = wp_update_term( tutis_compare_state_code_tax_state( $stateCode ), 'state', array(
                            'name' => $stateName,
                            'slug' => $stateName
                        ) );
                        update_term_meta( tutis_compare_state_code_tax_state( $stateCode ), 'tutis_state_code', $stateCode );
                        update_term_meta( tutis_compare_state_code_tax_state( $stateCode ), 'tutis_country_code', $countryCode );
                        update_term_meta( tutis_compare_state_code_tax_state( $stateCode ), 'tutis_country_name', $countryName );
                        tutis_update_location_from_state( tutis_compare_state_code_tax_state( $stateCode ), $locations );
                    }
                    else {
                        $state_id_temp = wp_insert_term(
                            $stateName,  
                            'state'
                        );

                        if( ! is_wp_error( $state_id_temp ) ) {
                            $state_id = $state_id_temp['term_id'];
                            update_term_meta( $state_id, 'tutis_state_code', $stateCode );
                            update_term_meta( $state_id, 'tutis_country_code', $countryCode );
                            update_term_meta( $state_id, 'tutis_country_name', $countryName );
                            update_term_meta( $state_id, 'tutis_location_data', $locations );
                            tutis_update_location_from_state( $state_id, $locations );
                        }
                    }
                }
            }
            else {
                echo '<p>Invalid Key or URL</p>';
            }
            //var_dump($obj);
        }
        curl_close($ch);
    }

}

function tutis_insert_state_country_from_api() {
    $tutis_api_url = get_option('tutis_api_url');
    $tutis_api_key = get_option('tutis_api_key');
    $tutis_api_size = get_option('tutis_api_size');
    if( $tutis_api_url && $tutis_api_key && $tutis_api_size ) {
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $tutis_api_url . '/api/states/all');
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET');

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
            if( isset( $obj ) ) {
                foreach( $obj as $key => $entry ) { 
                    $countryCode = $entry['countryCode'];
                    $countryName = $entry['countryName'];
                    $stateCode = $entry['stateCode'];
                    $stateName = $entry['stateName'];
                    $locations = $entry['locations'];
                    $state_id_temp = wp_insert_term(
                        $stateName,  
                        'state'
                    );

                    if( ! is_wp_error( $state_id_temp ) ) {
                        $state_id = $state_id_temp['term_id'];
                        update_term_meta( $state_id, 'tutis_state_code', $stateCode );
                        update_term_meta( $state_id, 'tutis_country_code', $countryCode );
                        update_term_meta( $state_id, 'tutis_country_name', $countryName );
                        update_term_meta( $state_id, 'tutis_location_data', $locations );
                        tutis_insert_location_from_api( $state_id );
                    }
                }
                
            }
            else {
                echo '<p>Invalid Key or URL</p>';
            }
            //var_dump($obj);
        }
        curl_close($ch);
    }

}

function tutis_compare_pcode_program( $program_code ) {
    if( $program_code ) {
        $terms = get_terms( array(
            'taxonomy' => 'program',
            'hide_empty' => false
        ) );

        foreach ( $terms as $term ) { 
            $program_code_meta = get_term_meta( $term->term_id, 'tutis_program_code', true );  
            if( $program_code == $program_code_meta ) {
                return $term->term_id;
            }
        } 
    }
}

function tutis_update_courses_from_program_api( $course_name, $code, $type, $term_id ) {
    if( $course_name && $code && $term_id ) {
        $args = array(
            'post_type'     => 'courses',
            'post_status'   => 'publish',
            'posts_per_page' => -1,
        );


        $meta_query[] = array(
            'relation' => 'AND',
            array(
                'key'   =>  '_course_code',
                'value' => $code,
                'compare' => 'IN'
            )
        );

        $args['meta_query'] = $meta_query;

        $wp_query = new WP_Query( $args );
        if ( $wp_query->have_posts() ) { 
            while ( $wp_query->have_posts() ) {
                $wp_query->the_post();
                $pid = get_the_ID();
                $update_args = array(
                    'ID' => $pid,
                    'post_title'    => $course_name,
                );
                 
                $update_id = wp_update_post( $update_args );
                if( !is_wp_error( $update_id ) ) {        
                    update_post_meta( $update_id, '_course_code', $code );
                    update_post_meta( $update_id, '_course_type', $type );
                    update_post_meta( $update_id, '_program_id', $term_id );
                }
                else {
                    echo $update_id->get_error_message();
                }
            }
        }
        else {
            $insert_args = array(
                'post_title'    => $course_name,
                'post_status'   => 'publish',
                'post_type' => 'courses'
            );

            $insert_id = wp_insert_post( $insert_args );
            if( !is_wp_error( $insert_id ) ) {
                add_post_meta( $insert_id, '_program_id', $term_id );
                add_post_meta( $insert_id, '_course_code', $code );
                add_post_meta( $insert_id, '_course_type', $type );
            }
            else { 
                echo $insert_id->get_error_message();
            }
        }
    }
}

function tutis_update_offerings_to_program( $term_id, $tutis_course_program_data ) {
    if( $term_id && $tutis_course_program_data ) {   
        foreach ( $tutis_course_program_data as $key => $value ) {
            $course_name = $value['name'];
            $code = $value['code'];
            $type = $value['type'];
            $course_archived = $value['archived'];
            if( $course_archived === false ) {
                tutis_update_courses_from_program_api( $course_name, $code, $type, $term_id );
                tutis_set_offerings_to_program( $code, $term_id, $course_name );
            }
        }
    }
}

function tutis_update_program_from_api() {
    $tutis_api_url = get_option('tutis_api_url');
    $tutis_api_key = get_option('tutis_api_key');
    $tutis_api_size = get_option('tutis_api_size');
    $current_time = current_time( 'mysql' );
    $last_updated = date('Y-m-d', strtotime( $current_time ) );
    $tutis_auto_next_sync = get_option('tutis_auto_next_sync');
    //$last_updated = '2021-07-07'; testing purpose
    if( $tutis_api_url && $tutis_api_key && $tutis_api_size && $tutis_auto_next_sync ) {
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $tutis_api_url . '/api/programs/recent?lastDate=' . $tutis_auto_next_sync . 'T00:00:00.000Z');
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET');

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
            if( isset( $obj ) ) {
                foreach( $obj as $key => $entry ) { 
                    $program_code = $entry['code'];
                    $program_type = $entry['type'];
                    $program_name = $entry['name'];
                    $program_courses = $entry['courses'];
                    $program_archived = $entry['archived'];
                    if( $program_archived === false ) {
                        if( tutis_compare_pcode_program( $program_code ) ) {
                            $update = wp_update_term( tutis_compare_pcode_program( $program_code ), 'program', array(
                                'name' => $program_name,
                                'slug' => $program_name
                            ) );
                            update_term_meta( tutis_compare_pcode_program( $program_code ), 'tutis_program_code', $program_code );
                            tutis_update_offerings_to_program( tutis_compare_pcode_program( $program_code ), $program_courses );
                        }
                        else {
                            $program_id = wp_insert_term(
                                $program_name,  
                                'program'
                            );

                            if( ! is_wp_error( $program_id ) ) {
                                $term_id = $program_id['term_id'];
                                update_term_meta( $term_id, 'tutis_program_code', $program_code );
                                update_term_meta( $term_id, 'tutis_course_program_data', $program_courses );
                                tutis_update_offerings_to_program( $term_id, $program_courses );
                            }
                        }
                    }
                }
                
            }
            else {
                echo '<p>Invalid Key or URL</p>';
            }
            //var_dump($obj);
        }
        curl_close($ch);
    }
}

function tutis_insert_program_from_api() {
    $tutis_api_url = get_option('tutis_api_url');
    $tutis_api_key = get_option('tutis_api_key');
    $tutis_api_size = get_option('tutis_api_size');
    if( $tutis_api_url && $tutis_api_key && $tutis_api_size ) {
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $tutis_api_url . '/api/programs/all');
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'GET');

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
            if( isset( $obj ) ) {
                foreach( $obj as $key => $entry ) { 
                    $program_code = $entry['code'];
                    $program_type = $entry['type'];
                    $program_name = $entry['name'];
                    $program_courses = $entry['courses'];
                    $program_archived = $entry['archived'];
                    if( $program_archived === false ) {
                        $program_id = wp_insert_term(
                            $program_name,  
                            'program'
                        );

                        if( ! is_wp_error( $program_id ) ) {
                            $term_id = $program_id['term_id'];
                            update_term_meta( $term_id, 'tutis_program_code', $program_code );
                            update_term_meta( $term_id, 'tutis_course_program_data', $program_courses );
                        }
                    }   
                }
                
            }
            else {
                echo '<p>Invalid Key or URL</p>';
            }
            //var_dump($obj);
        }
        curl_close($ch);
    }
}

function tutis_delete_all_post_type( $post_type ) {
    if( $post_type ) {
        $args = array(
            'post_type'     => $post_type,
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
}

function tutis_delete_all_offerings() {
    $args = array(
        'post_type'     => 'offerings',
        'post_status'   => 'publish',
        'posts_per_page' => 200,
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

function tutis_delete_all_tax( $taxonomy_name ) {
    if( $taxonomy_name ) {
        $terms = get_terms( array(
            'taxonomy' => $taxonomy_name,
            'hide_empty' => false
        ) );

        foreach ( $terms as $term ) {
            wp_delete_term( $term->term_id, $taxonomy_name );    
        } 
    }
}

function tutis_count_post_type( $type, $output = 'count_post' ) {
    if( $type && $output ) {
        $args = array(
            'post_type'     => $type,
            'post_status'   => 'publish',
            'posts_per_page' => 200,
        );

        $wp_query = new WP_Query( $args );

        if ( $wp_query->have_posts() ) { 
            if( $output == 'pagination_count') {
                return $wp_query->max_num_pages;
            }
            else {
                return $wp_query->found_posts;
            }
        }
    }
} 

function tutis_insert_securepay_error( $error_code, $from ) {
    if( $error_code ) {
        $auto_id = md5(microtime(true).random_int(0,99) );
        if( $_POST['temp_id'] ) {
            update_option( $_POST['temp_id'] .'_error_ref_id', $auto_id );
        }
        $securepay_error_args = array(
            'post_title'    => $auto_id,
            'post_status'   => 'publish',
            'post_type'   => 'securepay-error'
        );

        $post_id = wp_insert_post( $securepay_error_args );

        if( !is_wp_error( $post_id ) ) {
            update_post_meta( $post_id, '_tutis_securepay_error_code', $error_code );
            update_post_meta( $post_id, '_tutis_securepay_from', $from );
        } 
        else {
          echo $post_id->get_error_message();
        }
    }   
}

function tutis_get_secure_pay_auths_token( $tutis_api_url, $tutis_api_key, $tutis_secure_pay_client_secret, $tutis_secure_pay_client_id, $tutis_secure_pay_merchant_code, $temp_id ) {

    if( $tutis_api_url && $tutis_api_key && $tutis_secure_pay_client_secret && $tutis_secure_pay_client_id && $tutis_secure_pay_merchant_code ) {
        $tutis_base64 =  base64_encode( $tutis_secure_pay_client_id . ':' . $tutis_secure_pay_client_secret );

        $url = get_option('tutis_secure_pay_access_url');

        $curl = curl_init( $url );
        curl_setopt( $curl, CURLOPT_URL, $url );
        curl_setopt( $curl, CURLOPT_POST, true);
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "Authorization: Basic $tutis_base64",
            "Content-Type: application/x-www-form-urlencoded",
        );
        

        curl_setopt( $curl, CURLOPT_HTTPHEADER, $headers );

        $data = "grant_type=client_credentials&scope=https%3A%2F%2Fapi.payments.auspost.com.au%2Fpayhive%2Fpayments%2Fread%20https%3A%2F%2Fapi.payments.auspost.com.au%2Fpayhive%2Fpayments%2Fwrite%20https%3A%2F%2Fapi.payments.auspost.com.au%2Fpayhive%2Fpayment-instruments%2Fread%20https%3A%2F%2Fapi.payments.auspost.com.au%2Fpayhive%2Fpayment-instruments%2Fwrite";

        curl_setopt( $curl, CURLOPT_POSTFIELDS, $data );

        curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, 2 );
        curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, true );

        if ( !curl_errno( $curl ) ) {
            $result = curl_exec( $curl );
            $obj = json_decode( $result, true );
            //var_dump($obj);
            $access_token =  $obj['access_token'];
            if( empty( $access_token ) ) {
                tutis_insert_securepay_error( $obj, 'Token' );
                if( isset( $temp_id ) ) {
                    update_option( $temp_id, 'secure_tokken_error');
                }
            }
            else {
                return $access_token;
            }
        }

        curl_close($curl);
    }

}

function tutis_cancel_secure_pay_auths_data( $order_id ) {
    $tutis_api_url = get_option('tutis_api_url');
    $tutis_api_key = get_option('tutis_api_key');
    $tutis_secure_pay_client_secret = get_option('tutis_secure_pay_client_secret');
    $tutis_secure_pay_client_id = get_option('tutis_secure_pay_client_id');
    $tutis_secure_pay_merchant_code = get_option('tutis_secure_pay_merchant_code');
    $tutis_sandbox = get_option('tutis_sandbox');
    $tutis_ip = tutis_get_ip();
    $auth_token = tutis_get_secure_pay_auths_token( $tutis_api_url, $tutis_api_key, $tutis_secure_pay_client_secret, $tutis_secure_pay_client_id, $tutis_secure_pay_merchant_code, $order_id );
    
    if( $tutis_api_url && $tutis_api_key && $tutis_secure_pay_client_secret && $tutis_secure_pay_client_id && $tutis_secure_pay_merchant_code && $auth_token ) {
        $url = get_option('tutis_secure_payment_url') . "/preauths/$order_id/cancel";

        $curl = curl_init( $url );
        curl_setopt( $curl, CURLOPT_URL, $url );
        curl_setopt( $curl, CURLOPT_POST, true);
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
           "Content-Type: application/json",
           "Authorization: Bearer $auth_token",
        );

        curl_setopt( $curl, CURLOPT_HTTPHEADER, $headers );

        $data = array('merchantCode' => $tutis_secure_pay_merchant_code, 'ip' => $tutis_ip );

        $postdata = json_encode( $data );

        curl_setopt( $curl, CURLOPT_POSTFIELDS, $postdata );

        curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, 2 );
        curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, true );
        if ( !curl_errno( $curl ) ) {
            $result = curl_exec( $curl );
            $obj = json_decode( $result, true );
            //var_dump( $obj );
        }
        curl_close( $curl );
    }
}

add_shortcode('tester_all','tester_all');
function tester_all() {
	ob_start();
	// Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
		$ch = curl_init();

		curl_setopt($ch, CURLOPT_URL, 'https://uat-enrolment-api.tutis.com.au/api/offerings/all?size=3000');
		//curl_setopt($ch, CURLOPT_URL, 'http://msa-enrolment.mengz.ml/api/offerings/recent?lastDate=2020-03-10T00:00:00.000%2B00:00');
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');


		$headers = array();
		$headers[] = 'Accept: application/json';
		$headers[] = 'x-api-key: eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJkZXBsb3ltZW50IjoiZnNhLXVhdCJ9.yhbslpM7FObiiaA_LcFmsIDwyHLGnR4lg-UeN5KxKlk';
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$result = curl_exec($ch);
		$obj = json_decode($result, true);
		//$obj['totalPages'];
        //echo '<p>offeringId: '. $obj['totalPages'] . '</p>';
		if ( !curl_errno( $ch ) ) {
			foreach( $obj['content'] as $key => $entry) { 
	            $courseCode = $entry['courseCode'];
                $courseName = tutis_get_course_name( $courseCode );
	            $startDate = $entry['startDate'];
	            $endDate = $entry['endDate'];
	            $prerequisits = $entry['description'];
	            $fee = $entry['cost'];
	            $vacancies = $entry['vacancies'];
	            $offeringCode = $entry['offeringCode'];
	            $offeringId = $entry['offeringId'];

	            if( empty( $prerequisits ) ) {
	                $prerequisits = 'no prerequisite';
	            }
	            if( $startDate && $endDate ) {
                    echo '<h3>offeringCode: '. $offeringCode . '</h3>';
	            	echo '<p>offeringId: '. $offeringId . '</p>';
					echo '<p>Coursename: '. $courseName . '</p>';
					echo '<p>Vacant: ' . $vacancies . '</p>';
					echo '<p>startDate: ' . $startDate . '</p>';
				}
			}
		}
	
		//var_dump($obj);
		curl_close($ch);
	return ob_get_clean();
}

add_shortcode('tester_recent','tester_recent');
function tester_recent() {
	ob_start();
	// Generated by curl-to-PHP: http://incarnate.github.io/curl-to-php/
		$ch = curl_init();
		$current_time = current_time( 'mysql' );
 		echo $last_updated = date('Y-m-d', strtotime( $current_time ) ); 
		curl_setopt($ch, CURLOPT_URL, 'https://uat-enrolment-api.tutis.com.au/api/offerings/recent?lastDate=' . $last_updated . 'T00:00:00.000Z&size=500');
		//curl_setopt($ch, CURLOPT_URL, 'https://uat-enrolment-api.tutis.com.au/api/offerings/recent?lastDate=' . $last_updated . '&size=500');
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
		
		var_dump($obj);
		curl_close($ch);
	return ob_get_clean();
}

add_shortcode('tester_get_timezone_from_ip', 'tester_get_timezone_from_ip');
function tester_get_timezone_from_ip() {
    ob_start();
        // This is just an example. In application this will come from Javascript (via an AJAX or something)
    if( $_COOKIE['tutis_offset_time'] ) {
        $timezone_offset_minutes = $_COOKIE['tutis_offset_time'];  // $_GET['timezone_offset_minutes']

        // Convert minutes to seconds
        $timezone_name = timezone_name_from_abbr("", $timezone_offset_minutes*60, false);

        // Asia/Kolkata
        echo $timezone_name;
    }
    return ob_get_clean();
}

?>