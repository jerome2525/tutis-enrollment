<?php 
$type_column_col = 'tutis-two-col';
$type_hide_field = '';

$state_column_col = 'tutis-two-col-half';
$state_hide_field = '';
if( get_option('tutis_display_state') == 0 ) { 
  	$state_column_col = 'tutis-one-col';	
  	$state_hide_field = 'hide-field';
}
else {
	$state_column_col = 'tutis-two-col-half';	
	$state_hide_field = '';
}
?>
<form class="tutis-filter-form <?php echo $auto_class; ?>" id="tutis-filter-form1" method="POST" action="<?php echo admin_url( "admin-ajax.php" ); ?>" <?php echo $attribute_range; ?>>
  <input type="hidden" name="action" value="wsfilter">

  <div class="tutis-col <?php echo $type_column_col; ?>">

    <div class="tutis-filter-field tutis-filter-select">
      <select name="program" class="tutis-input-field" id="program1">
        <?php 
        	if( get_option('tutis_program_label') ) {
        		$prog_label = get_option('tutis_program_label');
        	}
        	else {
        		$prog_label = 'Program';
        	}
        	echo tutis_select_field_option_tax_program( 'program', $prog_label, $program ); 
        ?>
      </select>
    </div>

    <div class="tutis-filter-field tutis-filter-select">
    	<input type="hidden" name="course_code_offering" id="coursename_hidden1">
    	<select class="tutis-input-field" id="coursename1" theme="google" placeholder="Course Name" data-search="true">
    		<?php echo tutis_select_field_option_post_type( 'courses', 'Course Name', $course_code ); ?>
      	</select>
    </div>
  </div>

  <div class="tutis-col <?php echo $state_column_col; ?>">
    <div class="tutis-filter-field tutis-filter-select <?php echo $state_hide_field; ?>">
      <select name="state" class="tutis-input-field" id="state1">
        <?php echo tutis_select_field_option_tax( 'state','State', $state ); ?>
      </select>
    </div>  

    <div class="tutis-filter-field tutis-filter-select">
      <select name="location" class="tutis-input-field" id="location1">
        <?php echo tutis_select_field_option_location( 'location','Location', $location ); ?>
      </select>
    </div>
  </div>  

  <div class="tutis-col tutis-three-col">
    <div class="tutis-filter-field tutis-filter-select">
      <input name="date_range_field" type="text" class="tutis-input-field tutis-input-field-date tutis-input-date-range" placeholder="Next 30 Days" id="daterange1">
    </div>  

    <div class="tutis-filter-field">
      <input type="text" class="tutis-input-field tutis-input-field-date tutis-input-disabled" id="tutis-start">
       <input name="start" type="hidden" id="starthidden">
    </div>

    <div class="tutis-filter-field">
      <input type="text" class="tutis-input-field tutis-input-field-date tutis-input-disabled"  id="tutis-end">
      <input name="end" type="hidden" id="endhidden" value="Onwards">
      <input name="pagi" type="hidden" value="1" id="tutispagination1">
      <input name="random_id" type="hidden" value="<?php echo $random_id; ?>">
    </div>
  </div> 

	<div class="tutis-button-field">
		<input type="submit" value="Search" id="searchbtn" class="tutis-btn">
	</div>
</form>

<div class="loader"></div>
<div id="result" class="tutis-result tutis-course-page1 tutis-main-result-form"></div>