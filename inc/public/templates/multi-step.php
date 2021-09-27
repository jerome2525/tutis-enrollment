<div class="loader"></div>

<form class="tutis-filter-form" id="tutis-student-form1" method="POST" action="<?php echo admin_url( "admin-ajax.php" ); ?>">
	<input type="hidden" name="action" value="wsfilter">
	<input name="temp_id" type="hidden" value="<?php echo $_GET['temp_id']; ?>">
	<input name="course_id" type="hidden" value="<?php echo $_GET['course_id']; ?>">	
</form>
<form class="tutis-filter-form" id="tutis-delete-list-form1" method="POST" action="<?php echo admin_url( "admin-ajax.php" ); ?>">
	<input type="hidden" name="action" value="wsfilter">
	<input name="temp_id" type="hidden" value="<?php echo $_GET['temp_id']; ?>">
	<input name="course_id" type="hidden" value="<?php echo $_GET['course_id']; ?>">
	<input name="student_id" type="hidden" value="" class="tutis-student-id">
</form>

<form class="tutis-filter-form" id="tutis-company-form1" method="POST" action="<?php echo admin_url( "admin-ajax.php" ); ?>">
  <input type="hidden" name="action" value="wsfilter">
  <input name="temp_id" type="hidden" value="<?php echo $_GET['temp_id']; ?>">
  <input name="course_id" type="hidden" value="<?php echo $_GET['course_id']; ?>">
  <input name="subtotal_pagi" type="hidden" value="1">
</form>

<form class="tutis-filter-form" id="tutis-payment-student-form1" method="POST" action="<?php echo admin_url( "admin-ajax.php" ); ?>">
  <input type="hidden" name="action" value="wsfilter">
  <input name="temp_id" type="hidden" value="<?php echo $_GET['temp_id']; ?>">
  <input name="course_id" type="hidden" value="<?php echo $_GET['course_id']; ?>">
  <input name="payment_student_pagi" type="hidden" value="1">
  <input name="student_id" type="hidden" value="" class="tutis-student-id">
</form>

<form class="tutis-filter-form" id="tutis-payment-form1" method="POST" action="<?php echo admin_url( "admin-ajax.php" ); ?>">
  <input type="hidden" name="action" value="wsfilter">
  <input name="temp_id" type="hidden" value="<?php echo $_GET['temp_id']; ?>">
  <input name="course_id" type="hidden" value="<?php echo $_GET['course_id']; ?>">
  <input name="payment_pagi" type="hidden" value="1">
</form>

<form class="tutis-filter-form" id="tutis-final-payment1" method="POST" action="<?php echo admin_url( "admin-ajax.php" ); ?>">
  <input type="hidden" name="action" value="wsfilter">
  <input name="temp_id" type="hidden" value="<?php echo $_GET['temp_id']; ?>">
  <input name="course_id" type="hidden" value="<?php echo $_GET['course_id']; ?>">
  <input name="final_payment_pagi" type="hidden" value="1">
</form>

<form class="tutis-filter-form" id="tutis-securepay-error-payment1" method="POST" action="<?php echo admin_url( "admin-ajax.php" ); ?>">
  <input type="hidden" name="action" value="wsfilter">
  <input name="temp_id" type="hidden" value="<?php echo $_GET['temp_id']; ?>">
  <input name="course_id" type="hidden" value="<?php echo $_GET['course_id']; ?>">
  <input name="secure_error_code" id="secure-error-code1" type="hidden" value="<?php echo $_GET['course_id']; ?>">
  <input name="failed_securepay_pagi" type="hidden" value="1">
</form>


<!--multisteps-form-->
<div class="multisteps-form">
  <!--progress bar-->
  <div class="multisteps-form__progress">
    <button class="multisteps-form__progress-btn step1 js-active" type="button" title="Enroll Students">Enroll Students</button>
    <button class="multisteps-form__progress-btn step2" type="button" title="Company Details">Company Details</button>
    <button class="multisteps-form__progress-btn step3" type="button" title="Review Enrolment">Review Enrolment</button>
    <button class="multisteps-form__progress-btn step4" type="button" title="Payment">Payment</button>
  </div>

  <!--form panels-->
  <form class="multisteps-form__form" id="tutis-step-form1" onsubmit="return false;">
    <!--single form panel-->
    <div class="multisteps-form__panel pg1 js-active" data-animation="scaleIn" data-page="1">
      <h3 class="multisteps-form__title"><?php if( get_option('tutis_enrol_student_title') ) { echo get_option('tutis_enrol_student_title'); } else { echo 'Thankyou for deciding to study with us'; } ?></h3>
      <p class="multisteps-form__p"><?php if( get_option('tutis_enrol_student_instruction') ) { echo get_option('tutis_enrol_student_instruction'); } else { echo 'Lets get you setup, please add details for all students you wish to control'; } ?></p>
      <div id="result" class="tutis-student-result">
        <?php get_student_list_default( $_GET['temp_id'] ); ?>
      </div>
      <div class="multisteps-form__content">
        <div class="multisteps-form-field">
          <label>First Name *</label>
          <input name="student_firstname" type="text" placeholder="First Name *" form="tutis-student-form1" maxlength="128" required="" />
        </div>

        <div class="multisteps-form-field">
          <label>Last Name *</label>
          <input name="student_lastname" type="text" placeholder="Last Name *" form="tutis-student-form1" maxlength="128" required="" />
        </div>

        <div class="multisteps-form-field">
          <label>Date of Birth *</label>
			    <input class="multisteps-form__input form-control tutis-datepicker" name="student_birth" placeholder="Birth Date *" form="tutis-student-form1" required="" />
        </div>

        <div class="multisteps-form-field">
          <label>Email Address *</label>
          <input class="multisteps-form__input form-control" name="student_email" type="email" placeholder="Email *" form="tutis-student-form1" maxlength="256" required="" />
        </div>

        <div class="multisteps-form-field">
          <label>Mobile Phone</label>
          <input class="multisteps-form__input form-control" name="student_phone" id="student-phone" type="tel" pattern="^04[0-9]{8}$" placeholder="Phone" form="tutis-student-form1" oninvalid="this.setCustomValidity('Invalid Value: please check your data and try again')"  onchange="this.setCustomValidity('')" />
        </div>

        <div class="multisteps-form-field">
          <label>USI</label>
          <input class="multisteps-form__input form-control" name="student_usi" id="student-usi" type="text" pattern="^[2-9A-HJ-NP-Z]{10}$" placeholder="USI" form="tutis-student-form1" oninvalid="this.setCustomValidity('Invalid Value: please check your data and try again')"  onchange="this.setCustomValidity('')" />
        </div>	
      </div>

      <div class="multisteps-form-btn-field align-right">
          <input type="submit" class="tutis-btn" id="student-btn1" value="Add Student" form="tutis-student-form1">
    	</div>

    	<div class="multisteps-form-btn-field step-col-2">
        <div class="left-col">
    		  <a href="<?php echo get_permalink( $obj_id ) . '?&temp_id=' . $_GET['temp_id']; ?>" class="tutis-btn btn-left" data-step="1">Back</a>
        </div>
        <div class="right-col">
      	 <button class="tutis-btn js-btn-next btn-right student-btn disabled" type="button" data-step="1">Next</button>
        </div>
    	</div>
    </div>

    <!--single form panel-->
    <div class="multisteps-form__panel pg2" data-animation="scaleIn" data-page="2">
      <h3 class="multisteps-form__title"><?php if( get_option('tutis_company_title') ) { echo get_option('tutis_company_title'); } else { echo 'Your Company Details'; } ?></h3>
      <p><?php if( get_option('tutis_company_instruction') ) { echo get_option('tutis_company_instruction'); } else { echo 'If you enroll multiple students or enrolling on behalf of a business, these details are mandatory. If you are a private student enrolling only yourself, then leave these field blank.'; } ?></p>
      <div class="multisteps-form__content multisteps-form__content-full">
        <div class="multisteps-form-field multi-radio">
          <p class="label">Are you enrolling the student on behalf of a company?</p>
          <div class="multi-radio-container"><label class="company-radio"><input name="private_student" type="radio" value="1" required="" class="company-option cs1" form="tutis-company-form1" />Yes</label></div>
          <div class="multi-radio-container"><label class="company-radio"><input name="private_student" type="radio" value="0" required="" class="company-option cs2" form="tutis-company-form1" checked="checked"/>No</label></div>
        </div>
      </div>

      <div class="tutis-employer-row">
        <p class="label">Employer Details</p>
        <div class="multisteps-form__content tutis-employer-detail-col tutis-count-generate-col">
          <div class="multisteps-form-field">
            <label>Business Name *</label>
            <input name="company_businessname" type="text" class="input-required" placeholder="Business Name *" form="tutis-company-form1" required="" />
          </div>
          <?php if( get_option('tutis_hide_abn') != 1 ) { ?>
            <div class="multisteps-form-field">
              <label>ABN <?php if( get_option('tutis_required_abn') == 1 ) { echo '*'; } ?></label>
              <input name="company_abn" type="text" placeholder="ABN <?php if( get_option('tutis_required_abn') == 1 ) { echo '*'; } ?>" form="tutis-company-form1" class="<?php if( get_option('tutis_required_abn') == 1 ) { echo 'input-required'; } else { echo 'input-abn-not-required'; } ?> input-abn"/>
            </div>
          <?php } ?>  
          <?php if( get_option('tutis_hide_account') != 1 ) { ?>
            <div class="multisteps-form-field">
              <label>Account Number <?php if( get_option('tutis_required_abn') == 1 ) { echo '*'; } ?></label>
              <input name="company_accountnumber" type="text" placeholder="Account Number <?php if( get_option('tutis_required_abn') == 1 ) { echo '*'; } ?>" form="tutis-company-form1" class="<?php if( get_option('tutis_required_account') == 1 ) { echo 'input-required'; } ?> " />
            </div>
          <?php } ?> 
        </div>
      </div>  

      <div class="tutis-employer-row">
        <p class="label">Business Address</p>
        <div class="multisteps-form__content tutis-b-address-col tutis-count-generate-col">
          <?php if( get_option('tutis_hide_post_box') != 1 ) { ?>
            <div class="multisteps-form-field">
              <label>Post Delivery Box <?php if( get_option('tutis_required_post_box') == 1 ) { echo '*'; } ?></label>
              <input name="company_post_delivery" type="text" placeholder="Post Delivery Box <?php if( get_option('tutis_required_post_box') == 1 ) { echo '*'; } ?>" form="tutis-company-form1" class="<?php if( get_option('tutis_required_post_box') == 1 ) { echo 'input-required'; } ?> " />
            </div>
          <?php } ?>

          <?php if( get_option('tutis_hide_flat') != 1 ) { ?>
            <div class="multisteps-form-field">
              <label>Flat/Unit Details <?php if( get_option('tutis_required_flat') == 1 ) { echo '*'; } ?></label>
              <input name="company_flat_unit" type="text" placeholder="Flat/Unit Details <?php if( get_option('tutis_required_flat') == 1 ) { echo '*'; } ?>" form="tutis-company-form1"/>
            </div>
          <?php } ?> 

          <?php if( get_option('tutis_hide_building') != 1 ) { ?>
            <div class="multisteps-form-field">
              <label>Building / Property Name <?php if( get_option('tutis_required_building') == 1 ) { echo '*'; } ?></label>
              <input name="company_building" type="text" placeholder="Building / Property Name <?php if( get_option('tutis_required_building') == 1 ) { echo '*'; } ?>" form="tutis-company-form1" class="<?php if( get_option('tutis_required_building') == 1 ) { echo 'input-required'; } ?> " />
            </div>
          <?php } ?> 
        </div>

        <div class="multisteps-form__content tutis-b-address-col tutis-b-middle-address-col tutis-count-generate-col">
          <div class="multisteps-form-field">
            <label>Street Number *</label>
            <input name="company_st_number" type="text" placeholder="Street Number *" class="input-required input-street-number" form="tutis-company-form1"/>
          </div> 

          <div class="multisteps-form-field">
            <label>Street Name *</label>
            <input name="company_st_name" type="text" placeholder="Street Name *" form="tutis-company-form1" class="input-required" required="" />
          </div>

          <div class="multisteps-form-field">
            <label>Suburb / City *</label>
            <input name="company_city" type="text" placeholder="Suburb / City *" form="tutis-company-form1" class="input-required" required="" />
          </div>
        </div>

        <div class="multisteps-form__content tutis-b-address-col tutis-b-middle-address-col tutis-count-generate-col">
          <div class="multisteps-form-field multisteps-form-field-select">
            <label>State *</label>
            <select name="company_state" form="tutis-company-form1" class="input-required" required="">
              <option value="">State *</option>
              <option value="ACT">ACT</option>
              <option value="NSW">NSW</option>
              <option value="NT">NT</option>
              <option value="QLD">QLD</option>
              <option value="SA">SA</option>
              <option value="TAS">TAS</option>
              <option value="VIC">VIC</option>
              <option value="WA">WA</option>
            </select>
          </div>
          <div class="multisteps-form-field">
            <label>Post Code *</label>
            <input name="company_post_code" type="text" placeholder="Post Code *" form="tutis-company-form1" class="input-required input-street-number" required="" />
          </div>
          <?php if( get_option('tutis_hide_country') != 1 ) { ?>
            <div class="multisteps-form-field multisteps-form-field-select">
              <label>Country *</label>
              <select name="company_country" form="tutis-company-form1" class="input-required" required="">
                <option value="">Country *</option>
                <option value="AUS">Australia</option>
                <option value="NZL">New Zealand</option>
              </select>
            </div>
          <?php } ?>    
        </div>
      </div>  

      <div class="tutis-employer-row">
        <p class="label">Enroled By</p>
        <div class="multisteps-form__content tutis-employer-enrolled-by">
          <div class="multisteps-form-field">
            <label>First Name *</label>
            <input name="company_firstname" type="text" placeholder="First Name *" form="tutis-company-form1" class="input-required" required="" />
          </div>

          <div class="multisteps-form-field">
            <label>Last Name *</label>
            <input name="company_lastname" type="text" placeholder="Last Name *" form="tutis-company-form1" class="input-required" required="" />
          </div>

          <div class="multisteps-form-field">
            <label>Date of Birth *</label>
            <input name="company_birth" type="text" class="tutis-datepicker input-required" placeholder="Date of Birth *" form="tutis-company-form1" required="" />
          </div>

          <div class="multisteps-form-field">
            <label>Email Address *</label>
            <input name="company_email" type="email" placeholder="Email Address *" form="tutis-company-form1" class="input-email input-required" required="" />
          </div>
        </div>

        <div class="multisteps-form__content tutis-employer-enrolled-by-phone">
          <div class="multisteps-form-field  multisteps-form-field-select">
            <select name="company_phone_prefix" form="tutis-company-form1">
              <option value="HOME">Home</option>
              <option value="WORK">Work</option>
            </select>
          </div>

          <div class="multisteps-form-field">
            <label>Phone</label>
            <input name="company_phone" type="text" placeholder="Phone" form="tutis-company-form1" class="input-phone" required="" />
          </div>
        </div>
      </div>  
        
      <div class="multisteps-form-btn-field step-col-2">
        <div class="left-col">
          <button class="tutis-btn js-btn-prev btn-left" type="button" data-step="2">Back</button>
        </div>
        <div class="right-col">
          <button class="tutis-btn ml-auto js-btn-next btn-right employer-btn" type="button" data-step="2" id="reviewbtn1">Review Invoice</button>
        </div>
      </div>
    </div>

    <!--single form panel-->
    <div class="multisteps-form__panel pg3" data-animation="scaleIn" data-page="3">
      <div id="applycontent" style="background:#fff" class="lity-hide apply-discount-content">
        <h3 class="heading">Discount</h3>
        <div class="discount-form-field">
          <p>Please enter your discount code</p>
          <input name="apply_discount" type="text" placeholder="Discount Code" form="tutis-company-form1" />
          <p>Note you can only apply one discount code</p>
        </div>
        <div class="discount-button-field">
          <button class="tutis-btn ml-auto" id="apply-discount-btn" type="button" form="tutis-company-form1">Apply Discount</button>
        </div>
      </div>

      <div id="paylatercontent" style="background:#fff" class="lity-hide apply-discount-content">
        <div class="discount-form-field">
          <p>If you have any information you would like to add to this enrolment to help link it to your systems, please enter it now.
Otherwise just press submit and you will be issued a invoice to pay later.</p> 
          <input name="invoice_number" type="text" placeholder="Comments" form="tutis-final-payment1" maxlength="128" />
        </div>
        <div class="discount-button-field">
          <button class="tutis-btn ml-auto" type="button" form="tutis-final-payment1" id="invoice-btn1">Submit</button>
          <button class="tutis-btn cancel-btn" id="cancel-btn1" type="button" data-lity-close>Cancel</button>
        </div>
      </div>

      <h3 class="multisteps-form__title"><?php if( get_option('tutis_review_enrol_title') ) { echo get_option('tutis_review_enrol_title'); } else { echo 'OK, we are nearly there'; } ?></h3>
      <p class="multisteps-form__p"><?php if( get_option('tutis_review_enrol_instruction') ) { echo get_option('tutis_review_enrol_instruction'); } else { echo 'Please check the details, before going to the payment screen'; } ?></p>
      <h3 class="multisteps-form__title">Student Details: </h3>
      <div class="multisteps-form__content multisteps-form__content-full">
        <div id="result2" class="tutis-student-result tutis-review-invoice-student-list"></div>
        <div class="tutis-result tutis-invoice-result"><?php get_selected_course_default(); ?></div>
        <div id="resultsubtotal"></div>
      </div>

      <div class="multisteps-form-btn-field step-col-2">
        <div class="left-col">
          <button class="tutis-btn js-btn-prev btn-left" type="button" data-step="3">Back</button>
        </div>
        <div class="right-col">
          <?php if( get_option('tutis_paylater') == 1 ) { ?>
            <a href="#paylatercontent" class="pay-later-btn" id="paylaterbtn1" data-lity="">Pay Later</a>
          <?php } ?>
            <button class="tutis-btn ml-auto" type="button" form="tutis-final-payment1" id="zero-btn1">Pay Now</button>
            <button class="tutis-btn ml-auto js-btn-next btn-right employer-btn activate-payment-btn" type="button" data-step="3">Pay Now</button>
        </div>
      </div>
    </div>

  <!--single form panel-->
  <div class="multisteps-form__panel pg4" data-animation="scaleIn" data-page="4">
      <?php if( get_option('tutis_secure_pay_client_id') && get_option('tutis_secure_pay_merchant_code') ) { ?>
        <input name="securepay_card_type" type="hidden" id="securepay_card_type_field" form="tutis-final-payment1"/>
        <input name="payment_type" type="hidden" value="SECURE_PAY" id="payment_type" form="tutis-final-payment1"/>
        <input name="securepay_token" type="hidden" id="securepay_token_field" form="tutis-final-payment1"/>
        <input name="securepay_price" type="hidden" id="securepay_price_field" form="tutis-final-payment1"/>
        <input name="quote_token" type="hidden" id="quote_token_field" form="tutis-final-payment1"/>
        <input name="secure_ip" type="hidden" id="quote_ip_field" form="tutis-final-payment1" value="<?php echo tutis_get_ip(); ?>" />
        <h3 class="multisteps-form__title">Payment Details</h3>
        <div id="resultfinal"></div>
        <p>You are about to billed: <strong>$ <span id="billedAmount1" class="span-bold"></span></strong></p>
        <div id="securepay-ui-container"></div>
        <p class="tutis-notification">Please don't navigate away from the page until the payment is completed</p>
        <p class="tutis-notification">Please don't press the the submit payment button multiple times</p>
        <div class="multisteps-form-btn-field step-col-2">
          <div class="left-col">
            <button class="tutis-btn js-btn-prev btn-left" type="button" data-step="4">Back</button>
          </div>
          <div class="right-col">
            <button class="tutis-btn submit-payment-btn" type="button" id="secureSubmit1">Submit Payment</button>
          </div>
        </div>
      <?php } ?>  
      
  </div>
  </form>
</div>