<?php
add_action( 'init', 'tutis_cron_jobs_auto', 10 );
function tutis_cron_jobs_auto() {
	if( get_option('tutis_auto_sync') == '1' ) {
		if ( get_option('tutis_next_sync') ) {
			$current_time = current_time( 'mysql' );
		    $present = date('d M Y H:i:s', strtotime( $current_time ) ); 
		    if( $present >= get_option('tutis_next_sync') ) {
				tutis_auto_update_offerings();
			}
		}
	}

	if ( get_option('tutis_offering_update') == 'active' ) {
		tutis_auto_update_offerings();
		update_option('tutis_offering_update', 'not-active');
	}

	if ( get_option('tutis_program_update') == 'active' ) {
   		tutis_update_program_from_api();
   		update_option('tutis_program_update', 'not-active');
   		tutis_auto_update_day_time_flag();
	}


	if ( get_option('tutis_state_update') == 'active' ) {
		tutis_update_state_country_from_api();
   		update_option('tutis_state_update', 'not-active');
   		tutis_auto_update_day_time_flag();
	}

	if ( get_option('tutis_setting_update') == 'active' ) {
		tutis_generate_settings_payment_api();
		update_option('tutis_setting_update', 'not-active');
		tutis_auto_update_day_time_flag();
	}

	if ( get_option('tutis_all_update') == 'active' ) {
    	tutis_update_program_from_api();
    	tutis_update_state_country_from_api();
		tutis_auto_update_offerings();
       	tutis_generate_settings_payment_api();
       	update_option('tutis_all_update', 'not-active');
	}
}
?>