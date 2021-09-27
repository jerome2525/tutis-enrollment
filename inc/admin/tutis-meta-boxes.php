<?php
/**
 * Define the metabox and field configurations.
 */
class Tutis_Meta_Boxes {

	public function __construct() {
		$this->register_meta_box();
	}

	public function add_custom_meta_box() {
	    $offering_screens = [ 'offerings', 'wporg_cpt' ];
	    foreach ( $offering_screens as $offering_screen ) {
	        add_meta_box(
	            'wporg_box_id', 
	            'Offering Fields',
	            array( $this, 'offering_meta_box_html'), 
	            $offering_screen 
	        );
	    }

	    $course_screens = [ 'courses', 'wporg_cpt' ];
	    foreach ( $course_screens as $course_screen ) {
	        add_meta_box(
	            'wporg_box_id', 
	            'Course Fields',
	            array( $this, 'course_meta_box_html'), 
	            $course_screen 
	        );
	    }

	    $student_screens = [ 'student', 'wporg_cpt' ];
	    foreach ( $student_screens as $student_screen ) {
	        add_meta_box(
	            'wporg_box_id', 
	            'Student Fields',
	            array( $this, 'student_meta_box_html'), 
	            $student_screen 
	        );
	    }

	    $state_country_screens = [ 'state_country', 'wporg_cpt' ];
	    foreach ( $state_country_screens as $state_country_screen ) {
	        add_meta_box(
	            'wporg_box_id', 
	            'State Country Fields',
	            array( $this, 'state_country_meta_box_html'), 
	            $state_country_screens
	        );
	    }

		$enrollies_country_screens = [ 'enrollies', 'wporg_cpt' ];
	    foreach ( $enrollies_country_screens as $enrollies_country_screen ) {
	        add_meta_box(
	            'wporg_box_id', 
	            'Enrollies Fields',
	            array( $this, 'enrollies_meta_box_html'), 
	            $enrollies_country_screens
	        );
	    }

	    $quotes_country_screens = [ 'quotes', 'wporg_cpt' ];
	    foreach ( $quotes_country_screens as $quotes_country_screen ) {
	        add_meta_box(
	            'wporg_box_id', 
	            'Quote Fields',
	            array( $this, 'enrollies_meta_box_html'), 
	            $quotes_country_screens
	        );
	    }

	    $secure_screens = [ 'securepay-error', 'wporg_cpt' ];
	    foreach ( $secure_screens as $secure_screen ) {
	        add_meta_box(
	            'wporg_box_id', 
	            'Securepay Error Fields',
	            array( $this, 'secure_meta_box_html'), 
	            $secure_screens
	        );
	    }
	}

	public function enrollies_meta_box_html( $post ) {
	    $tutis_json_body_sent = get_post_meta( $post->ID, '_tutis_json_body_sent', true );
	    $tutis_response_code = get_post_meta( $post->ID, '_tutis_response_code', true );
	    $tutis_response_body = get_post_meta( $post->ID, '_tutis_response_body', true );
	    ?>
	    <div class="tutis-field-box">
		    <label>Json body sent</label>
		    <?php if( $tutis_json_body_sent ) { ?>
		    	<code><?php echo $tutis_json_body_sent; ?></code>
			<?php } ?>
		</div>

		<div class="tutis-field-box">
		    <label>Response code</label>
		    <?php if( $tutis_response_code ) { ?>
		    	<code><?php echo $tutis_response_code; ?></code>
			<?php } ?>
		</div>

		<div class="tutis-field-box">
		    <label>Response body</label>
		    <?php if( $tutis_response_body ) { ?>
		    	<code><?php echo json_encode( $tutis_response_body ); ?></code>
			<?php } ?>
		</div>
	    <?php
	}

	public function secure_meta_box_html( $post ) {
	    $tutis_securepay_error_code = get_post_meta( $post->ID, '_tutis_securepay_error_code', true );
	    ?>
	    <div class="tutis-field-box">
		    <label>Error Code</label>
		    <?php if( $tutis_securepay_error_code ) { ?>
		    	<?php if( !is_array( $tutis_securepay_error_code ) ) { ?>
		    		<code><?php echo $tutis_securepay_error_code; ?></code>
		    	<?php } else { ?>	
		    		<code><?php echo json_encode( $tutis_securepay_error_code ); ?></code>	
		    	<?php } ?>	
			<?php } ?>
		</div>
	    <?php
	}

	public function state_country_meta_box_html( $post ) {
	    $country_code = get_post_meta( $post->ID, '_country_code', true );
	    $country_name = get_post_meta( $post->ID, '_country_name', true );
	    $state_code = get_post_meta( $post->ID, '_state_code', true );
	    $state_name = get_post_meta( $post->ID, '_state_name', true );
	    ?>
	     <div class="tutis-field-box">
		    <label for="country_code">Country Code</label>
		    <input type="text" name="country_code" id="country_code" class="postbox" value="<?php echo $country_code; ?>">
		</div>
	    <div class="tutis-field-box">
		    <label for="country_name">Country Name</label>
		    <input type="text" name="country_name" id="country_name" class="postbox" value="<?php echo $country_name; ?>">
		</div>
		<div class="tutis-field-box">
		    <label for="state_code">State Code</label>
		    <input type="text" name="state_code" id="state_code" class="postbox" value="<?php echo $state_code; ?>">
		</div>
		<div class="tutis-field-box">
		    <label for="state_name">State Name</label>
		    <input type="text" name="state_name" id="state_name" class="postbox" value="<?php echo $state_name; ?>">
		</div>
	    <?php
	}

	public function offering_meta_box_html( $post ) {
	    $course_code_value = get_post_meta( $post->ID, '_course_code', true );
	    $start_date_value = get_post_meta( $post->ID, '_start_date', true );
	    $start_date_converted_value = get_post_meta( $post->ID, '_start_date_current_timezone', true );
	    $end_date_value = get_post_meta( $post->ID, '_end_date', true );
	    $course_fee_value = get_post_meta( $post->ID, '_course_fee', true );
	    $vacancies_value = get_post_meta( $post->ID, '_vacancies', true );
	    $offering_code_value = get_post_meta( $post->ID, '_offering_code', true );
	    $location_code_value = get_post_meta( $post->ID, '_location_code', true );
	    $offering_id_value = get_post_meta( $post->ID, '_offering_id', true );
	    $public_id_value = get_post_meta( $post->ID, '_public', true );
	    $rounded_time = get_post_meta( $post->ID, '_rounded_time', true );
	    ?>
	    <div class="tutis-field-box">
		    <label for="offering_id">Offering ID</label>
		    <input type="text" name="offering_id" id="offering_id" class="postbox" value="<?php echo $offering_id_value; ?>">
		</div>
	    <div class="tutis-field-box">
		    <label for="course_code">Course Code</label>
		    <input type="text" name="course_code" id="course_code" class="postbox" value="<?php echo $course_code_value; ?>">
		</div>

		<div class="tutis-field-box">
	    	<label for="course_code">Start Date</label>
	    	<input type="text" name="start_date" id="start_date" class="postbox" value="<?php echo $start_date_value; ?>">
		</div>

		<div class="tutis-field-box">
	    	<label for="course_code">End Date</label>
	    	<input type="text" name="end_date" id="end_date" class="postbox" value="<?php echo $end_date_value; ?>">
		</div>

		<div class="tutis-field-box">
	    	<label for="course_code">Start Date converted based Timezone</label>
	    	<input type="text" name="start_date_converted" id="start_date_converted" class="postbox" value="<?php echo $start_date_converted_value; ?>">
		</div>

		<div class="tutis-field-box">
	    	<label for="course_code">Rounded Time</label>
	    	<input type="text" name="rounded_time" id="rounded_time" class="postbox" value="<?php echo $rounded_time; ?>">
		</div>

		<div class="tutis-field-box">
		    <label for="course_fee">Fee</label>
		    <input type="text" name="course_fee" id="course_fee" class="postbox" value="<?php echo $course_fee_value; ?>">
		</div>

		<div class="tutis-field-box">
	    	<label for="vacancies">Vacancies</label>
	    	<input type="text" name="vacancies" id="vacancies" class="postbox" value="<?php echo $vacancies_value; ?>">
		</div>

		<div class="tutis-field-box">
	    	<label for="offering_code">Offering Code</label>
	    	<input type="text" name="offering_code" id="offering_code" class="postbox" value="<?php echo $offering_code_value; ?>">
		</div>

		<div class="tutis-field-box">
	    	<label for="location_code">Location Code</label>
	    	<input type="text" name="location_code" id="location_code" class="postbox" value="<?php echo $location_code_value; ?>">
		</div>

		<div class="tutis-field-box">
		    <label for="temp_id">Public</label>
		    <input type="text" name="public" id="public" class="postbox" value="<?php echo $public_id_value; ?>">
		</div>
	    <?php
	}

	public function course_meta_box_html( $post ) {
	    $program_id_value = get_post_meta( $post->ID, '_program_id', true );
	    $course_code_value = get_post_meta( $post->ID, '_course_code', true );
	    $course_type_value = get_post_meta( $post->ID, '_course_type', true );
	    ?>
	     <div class="tutis-field-box">
		    <label for="course_code">Program ID</label>
		    <input type="text" name="program_id" id="course_code" class="postbox" value="<?php echo $program_id_value; ?>">
		</div>
	    <div class="tutis-field-box">
		    <label for="course_code">Course Code</label>
		    <input type="text" name="course_code" id="course_code" class="postbox" value="<?php echo $course_code_value; ?>">
		</div>
		<div class="tutis-field-box">
		    <label for="course_code">Course Type</label>
		    <input type="text" name="course_type" id="course_type" class="postbox" value="<?php echo $course_type_value; ?>">
		</div>
	    <?php
	}

	public function student_meta_box_html( $post ) {
		$first_name_value = get_post_meta( $post->ID, '_first_name', true );
	    $last_name_value = get_post_meta( $post->ID, '_last_name', true );
	    $birth_date_value = get_post_meta( $post->ID, '_birth_date', true );
	    $email_value = get_post_meta( $post->ID, '_email', true );
	    $phone_value = get_post_meta( $post->ID, '_phone', true );
	    $usi_value = get_post_meta( $post->ID, '_usi', true );
	    $temp_id_value = get_post_meta( $post->ID, '_temp_id', true );
	    
	    ?>
	    <div class="tutis-field-box">
		    <label for="last_name">First Name</label>
		    <input type="text" name="first_name" id="first_name" class="postbox" value="<?php echo $first_name_value; ?>">
		</div>

	    <div class="tutis-field-box">
		    <label for="last_name">Last Name</label>
		    <input type="text" name="last_name" id="last_name" class="postbox" value="<?php echo $last_name_value; ?>">
		</div>

		<div class="tutis-field-box">
		    <label for="birth_date">Date Of Birth</label>
		    <input type="text" name="birth_date" id="birth_date" class="postbox" value="<?php echo $birth_date_value; ?>">
		</div>

		<div class="tutis-field-box">
		    <label for="email">Email Address</label>
		    <input type="text" name="email" id="email" class="postbox" value="<?php echo $email_value; ?>">
		</div>

		<div class="tutis-field-box">
		    <label for="email">Mobile Phone</label>
		    <input type="text" name="phone" id="phone" class="postbox" value="<?php echo $phone_value; ?>">
		</div>

		<div class="tutis-field-box">
		    <label for="usi">USI</label>
		    <input type="text" name="usi" id="usi" class="postbox" value="<?php echo $usi_value; ?>">
		</div>

		<div class="tutis-field-box">
		    <label for="temp_id">Temp ID</label>
		    <input type="text" name="temp_id" id="temp_id" class="postbox" value="<?php echo $temp_id_value; ?>">
		</div>

	    <?php
	}

	public function save_meta_box( $post_id ) {
		if ( array_key_exists( 'program_id', $_POST ) ) {
	        update_post_meta(
	            $post_id,
	            '_program_id',
	            $_POST['program_id']
	        );
	    }

	    if ( array_key_exists( 'course_code', $_POST ) ) {
	        update_post_meta(
	            $post_id,
	            '_course_code',
	            $_POST['course_code']
	        );
	    }

	    if ( array_key_exists( 'course_type', $_POST ) ) {
	        update_post_meta(
	            $post_id,
	            '_course_type',
	            $_POST['course_type']
	        );
	    }

	    if ( array_key_exists( 'start_date', $_POST ) ) {
	        update_post_meta(
	            $post_id,
	            '_start_date',
	            $_POST['start_date']
	        );
	    }

	    if ( array_key_exists( 'end_date', $_POST ) ) {
	        update_post_meta(
	            $post_id,
	            '_end_date',
	            $_POST['end_date']
	        );
	    }

		if ( array_key_exists( 'course_fee', $_POST ) ) {
	        update_post_meta(
	            $post_id,
	            '_course_fee',
	            $_POST['course_fee']
	        );
	    }

	    if ( array_key_exists( 'available', $_POST ) ) {
	        update_post_meta(
	            $post_id,
	            '_available',
	            $_POST['available']
	        );
	    }

	    if ( array_key_exists( 'vacancies', $_POST ) ) {
	        update_post_meta(
	            $post_id,
	            '_vacancies',
	            $_POST['vacancies']
	        );
	    }

	    if ( array_key_exists( 'offering_code', $_POST ) ) {
	        update_post_meta(
	            $post_id,
	            '_offering_code',
	            $_POST['offering_code']
	        );
	    }

	     if ( array_key_exists( 'location_code', $_POST ) ) {
	        update_post_meta(
	            $post_id,
	            '_location_code',
	            $_POST['location_code']
	        );
	    }

	    if ( array_key_exists( 'offering_id', $_POST ) ) {
	        update_post_meta(
	            $post_id,
	            '_offering_id',
	            $_POST['offering_id']
	        );
	    }

	    if ( array_key_exists( 'first_name', $_POST ) ) {
	        update_post_meta(
	            $post_id,
	            '_first_name',
	            $_POST['first_name']
	        );
	    }

	    if ( array_key_exists( 'last_name', $_POST ) ) {
	        update_post_meta(
	            $post_id,
	            '_last_name',
	            $_POST['last_name']
	        );
	    }

	    if ( array_key_exists( 'birth_date', $_POST ) ) {
	        update_post_meta(
	            $post_id,
	            '_birth_date',
	            $_POST['birth_date']
	        );
	    }

	    if ( array_key_exists( 'email', $_POST ) ) {
	        update_post_meta(
	            $post_id,
	            '_email',
	            $_POST['email']
	        );
	    }

	    if ( array_key_exists( 'phone', $_POST ) ) {
	        update_post_meta(
	            $post_id,
	            '_phone',
	            $_POST['phone']
	        );
	    }

	    if ( array_key_exists( 'usi', $_POST ) ) {
	        update_post_meta(
	            $post_id,
	            '_usi',
	            $_POST['usi']
	        );
	    }

	    if ( array_key_exists( 'temp_id', $_POST ) ) {
	        update_post_meta(
	            $post_id,
	            '_temp_id',
	            $_POST['temp_id']
	        );
	    }

	    if ( array_key_exists( 'public', $_POST ) ) {
	        update_post_meta(
	            $post_id,
	            '_public',
	            $_POST['public']
	        );
	    }

	    if ( array_key_exists( 'country_code', $_POST ) ) {
	        update_post_meta(
	            $post_id,
	            '_country_code',
	            $_POST['country_code']
	        );
	    }

	    if ( array_key_exists( 'country_name', $_POST ) ) {
	        update_post_meta(
	            $post_id,
	            '_country_name',
	            $_POST['country_name']
	        );
	    }

	    if ( array_key_exists( 'state_name', $_POST ) ) {
	        update_post_meta(
	            $post_id,
	            '_state_name',
	            $_POST['state_name']
	        );
	    }


	    if ( array_key_exists( 'state_code', $_POST ) ) {
	        update_post_meta(
	            $post_id,
	            '_state_code',
	            $_POST['state_code']
	        );
	    }

	}

	public function register_meta_box() {
		add_action( 'save_post', array( $this, 'save_meta_box' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_custom_meta_box' ) );
	}

}

$tutis_meta_box = new Tutis_Meta_Boxes();