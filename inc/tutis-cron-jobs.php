<?php
add_action('init', 'tutis_cron_jobs_auto', 10);
function tutis_cron_jobs_auto() {
	if ( get_option('tutis_next_sync') ) {
		$current_time = current_time( 'mysql' );
	    $present = date('d M Y H:i:s', strtotime( $current_time ) ); 
	    if( $present >= get_option('tutis_next_sync') ) {
			tutis_default_sync_course_api();
		}
	}
}
?>