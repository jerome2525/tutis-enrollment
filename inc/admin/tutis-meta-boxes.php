<?php
/**
 * Define the metabox and field configurations.
 */
class Tutis_Meta_Boxes {

	public function __construct() {
		$this->register_meta_box();
	}

	public function add_custom_meta_box() {
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
	}

	public function course_meta_box_html( $post ) {
	    $course_code_value = get_post_meta( $post->ID, '_course_code', true );
	    $start_date_value = get_post_meta( $post->ID, '_start_date', true );
	    $end_date_value = get_post_meta( $post->ID, '_end_date', true );
	    $course_fee_value = get_post_meta( $post->ID, '_course_fee', true );
	    $vacancies_value = get_post_meta( $post->ID, '_vacancies', true );
	    $offering_code_value = get_post_meta( $post->ID, '_offering_code', true );
	    $offering_id_value = get_post_meta( $post->ID, '_offering_id', true );
	    $public_id_value = get_post_meta( $post->ID, '_public', true );
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
		    <label for="course_fee">Course Fee</label>
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
		    <label for="temp_id">Public</label>
		    <input type="text" name="public" id="public" class="postbox" value="<?php echo $public_id_value; ?>">
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
	    if ( array_key_exists( 'course_code', $_POST ) ) {
	        update_post_meta(
	            $post_id,
	            '_course_code',
	            $_POST['course_code']
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
	}

	public function register_meta_box() {
		add_action( 'save_post', array( $this, 'save_meta_box' ) );
		add_action( 'add_meta_boxes', array( $this, 'add_custom_meta_box' ) );
	}

}

new Tutis_Meta_Boxes;