<div id="result" class="tutis-student-result">
	<?php get_student_list_default( $_GET['temp_id'] ); ?>
</div>

<form class="tutis-filter-form" id="tutis-delete-list-form1" method="POST" action="<?php echo admin_url( "admin-ajax.php" ); ?>">
	<input type="hidden" name="action" value="wsfilter">
	<input name="temp_id" type="hidden" value="<?php echo $_GET['temp_id']; ?>">
	<input name="course_id" type="hidden" value="<?php echo $_GET['course_id']; ?>">
	<input name="student_id" type="hidden" value="" class="tutis-student-id">
</form>

<form class="tutis-filter-form" id="tutis-filter-form1" method="POST" action="<?php echo admin_url( "admin-ajax.php" ); ?>">
	<input type="hidden" name="action" value="wsfilter">
	<input name="temp_id" type="hidden" value="<?php echo $_GET['temp_id']; ?>">
	<input name="course_id" type="hidden" value="<?php echo $_GET['course_id']; ?>">
	<div class="tutis-filter-field">
		<label>First Name *</label>
		<input type="text" name="student_firstname" class="tutis-input-field" placeholder="First Name *" required="">
	</div>

	<div class="tutis-filter-field">
		<label>Last Name *</label>
	 	<input type="text" name="student_lastname" class="tutis-input-field" placeholder="Last Name *" required="">
	</div>

	<div class="tutis-filter-field">
		<label>Date of Birth *</label>
		<input type="date" name="student_birth" class="tutis-input-field" placeholder="Date of Birth *" required="">
	</div>

	<div class="tutis-filter-field">
		<label>Email Address *</label>
	 	<input type="email" name="student_email" class="tutis-input-field" placeholder="Email Address *" required="">
	</div> 

	<div class="tutis-filter-field">
		<label>Mobile Phone</label>
	 	<input type="text" name="student_phone" class="tutis-input-field" placeholder="Mobile Phone" required="">
	</div> 

	<div class="tutis-filter-field">
		<label>USI</label>
	 	<input type="text" name="student_usi" class="tutis-input-field" placeholder="USI">
	</div>

	<input type="submit" value="Add Student" id="addstudentbtn">
</form>

<div class="loader"></div>
