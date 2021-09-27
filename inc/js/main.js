jQuery(document).ready(function($){

	var daterange1 = $('#tutis-filter-form1').attr('daterange');
	if(daterange1) {
		if( daterange1 == 30 || daterange1 == 90 || daterange1 == 7 ) {
			var start_def = daterange1;
		}
		else {
			var start_def = 30;
		}

	}
	else {
		var start_def = 30;
	}

    var start = moment();
    var end = moment().add(start_def, 'days');
    var label = 'Next ' + start_def + ' Days';

    function cb(start, end, label) {
        $('#tutis-start').val(start.format('D/M/YYYY'));
        $('#starthidden').val(start.format('YYYY-MM-DD'));
    	$('#tutis-end').val(end.format('D/M/YYYY'));
    	$('#endhidden').val(end.format('YYYY-MM-DD'));
        $('#daterange1').val(label);
    }

    $('#daterange1').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
           'Next 30 Days': [moment(), moment().add(30, 'days'),'Next 30 Days'],
           'Next 7 Days': [moment(), moment().add(7, 'days'), 'Next 7 Days'],
           'Next 90 Days': [moment(), moment().add(90, 'days'), 'Next 90 Days'],
        },
        autoUpdateInput: false,
        locale: {
			cancelLabel: 'Clear'
		}

    }, cb);

    cb(start, end, label);

    function expand_icon() {

		$('.tutis-course-page1 .tbbody').click(function() {
			var last_cl = $(this).attr('class').split(' ').pop();
			var final_str = last_cl.substring('2');
			$('.tutis-course-page1 .tbbodycontent').not('.tutis-course-page1 .cl' + final_str).removeClass('content-show');
			$('.tutis-course-page1 .cl' + final_str).toggleClass('content-show');
			$('.tutis-course-page1 .expand').find('i').removeClass('fa-minus-circle').addClass('fa-plus-circle');
			$('.tutis-course-page1 .tbbody').removeClass('active');
	    	$('.tutis-course-page1 .' + last_cl).addClass('active');
	    	$('.tutis-course-page1 .first.' + last_cl).find('.expand i').removeClass('fa-minus-circle').addClass('fa-plus-circle');
	    	$('.tutis-course-page1 .first.active.' + last_cl).find('.expand i').removeClass('fa-plus-circle').addClass('fa-minus-circle');
		});

    	$('.tutis-course-page1 .expand').click(function() {
	    	var eid = $(this).attr('id');
	    	$('.tutis-course-page1 .tbbodycontent').not('.tutis-course-page1 .' + eid).removeClass('content-show');
	    	$('.tutis-course-page1 .expand').not('#' + eid).find('i').removeClass('fa-minus-circle').addClass('fa-plus-circle');
	    	$(this).find('i').toggleClass('fa-minus-circle');
	    	$('.tutis-course-page1 .' + eid).toggleClass('content-show');
	    	var last_cl = $(this).parent().attr('class').split(' ').pop();
	    	$('.tutis-course-page1 .tbbody').removeClass('active');
	    	$('.' + last_cl).addClass('active');
	    });
	    
    }

    function expand_icon2() {

		$('.tutis-invoice-result .tbbody').click(function() {
			var last_cl = $(this).attr('class').split(' ').pop();
			var final_str = last_cl.substring('2');
			$('.tutis-invoice-result .tbbodycontent').not('.tutis-invoice-result .cl' + final_str).removeClass('content-show');
			$('.tutis-invoice-result .cl' + final_str).toggleClass('content-show');
			$('.tutis-invoice-result .expand').find('i').removeClass('fa-minus-circle').addClass('fa-plus-circle');
			$('.tutis-invoice-result .tbbody').removeClass('active');
	    	$('.' + last_cl).addClass('active');
	    	$('.tutis-invoice-result .first.' + last_cl).find('.expand i').removeClass('fa-minus-circle').addClass('fa-plus-circle');
	    	$('.tutis-invoice-result .first.active.' + last_cl).find('.expand i').removeClass('fa-plus-circle').addClass('fa-minus-circle');
		});

    	$('.tutis-invoice-result .expand').click(function() {
	    	var eid = $(this).attr('id');
	    	$('.tutis-invoice-result .tbbodycontent').not('.' + eid).removeClass('content-show');
	    	$('.tutis-invoice-result .expand').not('#' + eid).find('i').removeClass('fa-minus-circle').addClass('fa-plus-circle');
	    	$(this).find('i').toggleClass('fa-minus-circle');
	    	$('.tutis-invoice-result .' + eid).toggleClass('content-show');
	    	var last_cl = $(this).parent().attr('class').split(' ').pop();
	    	$('.tutis-invoice-result .tbbody').removeClass('active');
	    	$('.' + last_cl).addClass('active');
	    });
	    
    }

    function tutis_pagination() {
    	$('.tutis-pagination a.page-numbers').click(function(e) {
    		e.preventDefault();
    		$('#tutispagination1').val($(this).text());
    		$('#searchbtn').click();
    		$('.tutis-pagination a.page-numbers').removeClass('current');
    		$(this).addClass('current');
    		if($(this).hasClass('next')) {
    			$('.tutis-pagination .current').next().click();
    		}

    		if($(this).hasClass('prev')) {
    			$('.tutis-pagination .current').prev().click();
    		}
    	});
    }	

    function tutis_student_btn_disabled() {
		if( parseInt( $('.student-result-list').length ) > 0 ) {
	 		$('.multisteps-form__form .student-btn').removeClass('disabled');
	  	}
	  	else {
	  		$('.multisteps-form__form .student-btn').addClass('disabled');
	  	}
	}

	function tutis_student_delete() {
		$('.tutis-delete-student').click(function(e) {
			e.preventDefault();
			var tutis_student_id = $(this).attr('href');
			$('.tutis-student-id').val( tutis_student_id );
			$('#tutis-delete-list-form1').submit();
			tutis_student_btn_disabled();
		});
		
	}

	function tutis_check_student_list() {
		tutis_student_btn_disabled();
	  	$('.tutis-btn').click(function() {
	  		if( parseInt($('#result .student-count').length) > 1 ) {
		 		$('.cs1').click();
		 		tutis_input_checker();
		 		$('.multi-radio').hide();
		  	}
		  	else {
		  		$('.multi-radio').show();
		  	}
		  	tutis_student_btn_disabled();
		  	setTimeout( function() {
		  		if( tutis_frontend_object.tutis_temp_id ) {
					mySecurePayUI.reset();
				}
				var final_amount = $('.tutis-total-amount').text().replace(/\./g,'');
				var final_amount_billed = $('.tutis-total-amount').text();
				var quote_token = $('#quoteID1').text();
				$('#billedAmount1').text( final_amount_billed );
				if ( final_amount_billed == Math.floor( final_amount_billed ) ) {
					$('#securepay_price_field').val( final_amount + '00' );
				}
				else {
					$('#securepay_price_field').val( final_amount );
				}
				
				$('#quote_token_field').val(quote_token);
			}, 2000);
		  	
	  	}); 
	}

	function tutis_review_btn_click() {
	    $('#reviewbtn1').click(function() {
			//$('#tutis-payment-form1').submit();
			$('#tutis-payment-student-form1').submit();
			$('#tutis-company-form1').submit();
		});
	}

	function tutis_count_qty() {
		var qty = $('.tutis-review-invoice-student-list .student-result-list').length;
		var pr = $('#tbfee1').text();
		var tot = $('#coursefee1').text();
		$('#tbqty1').text(qty);
		$('#tbtotal1').text( tot );
		//console.log(tot);
	}

	var getUrlParameter = function getUrlParameter(sParam) {
		var sPageURL = window.location.search.substring(1),
	        sURLVariables = sPageURL.split('&'),
	        sParameterName,
	        i;

		for (i = 0; i < sURLVariables.length; i++) {
	        sParameterName = sURLVariables[i].split('=');

	        if (sParameterName[0] == sParam) {
	            return decodeURIComponent(sParameterName[1]);
	        }
	    }
	    return false;
	};

	function disabled_button_student() {
		var tutis_student_count = $('#result .student-count').length;
		var tutis_course_vacant_count = tutis_frontend_object.tutis_course_vacant_count;
		if( tutis_student_count >= tutis_course_vacant_count ) {
			$('#student-btn1').hide();
		}
		else {
			$('#student-btn1').show();
		}
	}

	function ws_filter_ajx(filter, res_id) {
        var filter = $(filter);
        if( filter ) {
            $.ajax({
                url:filter.attr('action'),
                data:filter.serialize(), // form data
                type:filter.attr('method'), // POST
                cache: false,
                beforeSend: function() {
                    $('.loader').show();
                },
                complete: function(){
                    $('.loader').hide();
                },
                success:function(data){
           			$(res_id).html(data);
           			if( res_id == '#result' ) {
           				disabled_button_student();
           			}
     				expand_icon();
	             	tutis_pagination();
	               	tutis_student_delete();
	              	$('#tutis-student-form1').trigger('reset');
	            	tutis_check_student_list();
	            	tutis_count_qty();
	            	$('.multisteps-form-field label').removeClass('show-label');
	            	if( res_id == '#resultfinal') {
	            		var temp_id = getUrlParameter('temp_id');
	            		//console.log(temp_id);
	            		if( $('.status').text() !== 'failed' ) {
	            			window.location.href = window.location.href.replace( /[\?#].*|$/, "?ref_id=" + temp_id );
	            		}
	            	}
                    
                },
                async: "false",
            });
        }

    }

    function tutis_validate_abn(num) {
	    const weights = new Array(10, 1, 3, 5, 7, 9, 11, 13, 15, 17, 19);
	    // Convert to string and remove all white space
	    num = num.toString().replace(/\s/g, "");
	    // Split it to number array
	    num = num.split('').map(n => parseInt(n));
	    // Subtract 1 from the first (left-most) digit of the ABN to give a new 11 digit number
	    num[0] = num[0] - 1;
	    // Multiply each of the digits in this new number by a "weighting factor" based on its position as shown in the table below
	    num = num.map((n, i) => n * weights[i]);
	    // Sum the resulting 11 products
	    let total = num.reduce((acc, n) => {
	        return acc + n;
	    }, 0);
	    // Divide the sum total by 89, noting the remainder
	    if(total % 89 === 0) {
	        return true;
	    } else {
	        return false;
	    }
	}

    function tutis_validate_email($email) {
		var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
		return emailReg.test( $email );
	}

	function tutis_validate_phone($phone) {
		var intRegex = /[0-9 -()+]+$/;
		if(($phone.length >  6) || ( intRegex.test($phone) ) ) {
			return intRegex.test($phone);
		}
	}

    function tutis_input_checker() {
		var inputsVal = $('.tutis-employer-row').find( $('.input-has-val') );
		var inputsReq = $('.input-required').length;
		var inputsError = $('.tutis-employer-row .input-error').length;
		if(inputsVal.length == inputsReq) {
			$('.multisteps-form__form .employer-btn').removeClass('disabled');
		}
		else {
			$('.multisteps-form__form .employer-btn').addClass('disabled');
		}
    }

    function tutis_validate_street_number( str ) {
	  if (!/^\d{1,5}$/.test(str)) {
	    return false;
	  }
	  const num = parseInt(str, 10);
	  return num >= 1 && num < 1e5;
	}


	tutis_review_btn_click();

	$('#apply-discount-btn').click(function() {
		$('#tutis-company-form1').submit();
		$('[data-lity-close]', window.parent.document).trigger('click');
	});

	$('#invoice-btn1').click(function() {
		$('#tutis-final-payment1').submit();
		$('[data-lity-close]', window.parent.document).trigger('click');
	});


	$('#tutis-payment-student-form1').submit(function(){
		var res_id = '#result2';
		ws_filter_ajx(this, res_id );
		return false;
    });

	$('#tutis-payment-form1').submit(function(){
		var res_id = '#result3';
		ws_filter_ajx(this, res_id );
		return false;
    });

	$('#tutis-student-form1').submit(function(){
		var res_id = '#result';
		ws_filter_ajx(this, res_id);
		return false;
    });

	$('#tutis-filter-form1').submit(function(){
		var res_id = '#result';
		ws_filter_ajx(this, res_id);
		return false;
    });

    $('#tutis-company-form1').submit(function(){
		var res_id = '#resultsubtotal';
		ws_filter_ajx(this, res_id);
		return false;
    });

    $('#tutis-final-payment1').submit(function(){
		var res_id = '#resultfinal';
		ws_filter_ajx(this, res_id);
		return false;
    });

     $('#tutis-securepay-error-payment1').submit(function(){
		var res_id = '#resultsecurepay';
		ws_filter_ajx(this, res_id);
		return false;
    });

    $('#tutis-filter-form1 #searchbtn').click();

    $('.tutis-datepicker').daterangepicker({
		singleDatePicker: true,
		showDropdowns: true,
		minYear: 1960,
		maxYear: parseInt(moment().format('YYYY'),10),
		locale: {
	      format: 'D/M/YYYY',
	      cancelLabel: 'Clear'
	    }
	});
    
    expand_icon();
	expand_icon2();
	tutis_pagination();
	tutis_student_delete();
	$('#tutis-delete-list-form1').submit(function(){
		var res_id = '#result';
  		ws_filter_ajx(this, res_id);
  		return false;
    });

	$('#tutis-student-form1, #tutis-company-form1').trigger('reset');
	tutis_check_student_list();

	$('.tutis-employer-row .input-required').keyup(function() {
	 	tutis_input_checker();
	});

	$('.tutis-employer-row .input-required').change(function() {
		tutis_input_checker();
		if( $(this).hasClass('input-email') ) {
			if( tutis_validate_email( $(this).val() ) ) { 
				$(this).addClass('input-has-val');
				$(this).removeClass('input-error');
			}
			else {
				$(this).removeClass('input-has-val');
				$(this).addClass('input-error');
			}
		}
		else if( $(this).hasClass('input-phone') ) {
			if( tutis_validate_phone( $(this).val() ) ) { 
				$(this).addClass('input-has-val');
				$(this).removeClass('input-error');
			}
			else {
				$(this).removeClass('input-has-val');
				$(this).addClass('input-error');
			}
		}
		else if( $(this).hasClass('input-abn') ) {
			if( tutis_validate_abn( $(this).val() ) ) { 
				$(this).addClass('input-has-val');
				$(this).removeClass('input-error');
			}
			else {
				$(this).removeClass('input-has-val');
				$(this).addClass('input-error');
			}
		}
		else if( $(this).hasClass('input-street-number') ) {
			if( tutis_validate_street_number( $(this).val() ) ) { 
				$(this).addClass('input-has-val');
				$(this).removeClass('input-error');
			}
			else {
				$(this).removeClass('input-has-val');
				$(this).addClass('input-error');
			}
		}
		else {
			if( $(this).val() ) {
				$(this).addClass('input-has-val');
				$(this).removeClass('input-error');
			}
			else {
				$(this).removeClass('input-has-val');
				$(this).addClass('input-error');
			}
		}

		tutis_input_checker();
	});

	$('.tutis-employer-row .input-abn-not-required').keyup(function() {
		if( $(this).val() ) {
			$(this).addClass('input-has-val input-required');
			if( tutis_validate_abn( $(this).val() ) ) { 
				$(this).addClass('input-has-val');
				$(this).removeClass('input-error');
			}
			else {
				$(this).removeClass('input-has-val');
				$(this).addClass('input-error');
			}
	 	}
	 	else {
	 		$(this).removeClass('input-error');
	 		$(this).removeClass('input-has-val input-required');
	 	}
	 	tutis_input_checker();
	});

	$('.company-option').click(function() {
		var com_val = $(this).val();
		if( com_val == 1 ) {
			$('.tutis-employer-row').show();
			$('.multisteps-form__form .employer-btn').addClass('disabled');
		} 
		else {
			$('.tutis-employer-row').hide();
			$('.multisteps-form__form .employer-btn').removeClass('disabled');
		}
	});

	$('.cs1').click(function() {
  		$('#reviewbtn1').addClass('disabled');
  		$('#paylaterbtn1').show();
  	});	

	$('.cs2').click(function() {
  		$('#reviewbtn1').removeClass('disabled');
  		$('#paylaterbtn1').hide();
  	});	

	$('.js-btn-next').click(function() {
		$('.multisteps-form__panel').removeClass('js-active');
		var data_step = $(this).attr('data-step');
		var data_step_added = parseInt(data_step) + 1;
		$('.pg' + data_step_added ).addClass('js-active');
		$('.step' + data_step_added ).addClass('js-active');
	});

	$('.js-btn-prev').click(function() {
		$('.multisteps-form__panel').removeClass('js-active');
		var data_step = $(this).attr('data-step');
		var data_step_minus = parseInt(data_step) - 1;
		$('.pg' + data_step_minus).addClass('js-active');
		$('.step' + data_step).removeClass('js-active');
		$('.multisteps-form-field label').not('.company-radio').addClass('show-label');
	});

	$('.multisteps-form-field input, .multisteps-form-field select').change(function() {
		if( $(this).val().length === 0 ) {
			$(this).siblings().removeClass('show-label');
		}
		else {
			$(this).siblings().addClass('show-label');
		}
	});

	$('#secureSubmit1').click(function() {
		if( tutis_frontend_object.tutis_temp_id ) {
			mySecurePayUI.tokenise();
		}
	});

	$('.tutis-count-generate-col').each(function() {
		var field_count = $(this).find('.multisteps-form-field').length;
		$(this).addClass('added-col' + field_count);
	});

	$('#coursename1').selectstyle();

	$('.tutis-filter-select .ss_ul li').click(function() {
		$('#coursename_hidden1').val( $(this).attr('value') );
	});

	$('.tutis-filter-select .ss_button').click(function() {
		setTimeout( function() {
			$('#ss_search input').focus();
		}, 500);

		
	});

	$('#program1').change(function() {
		var prog_val = $(this).val();
		var sel_text = $('div#select_style #select_style_text').text();
		if( prog_val ) {
			setTimeout( function() {
				$( '.ss_ul li' ).hide();
				$( '.ss_ul li' ).each(function( index ) {
					var li_id = $(this).attr('data-programid');
					if( prog_val == li_id ) {
						$(this).show();
					}
				});
				
				if( sel_text != 'Course Name' ) {
					$( '.ss_ul li' ).first().click();
				}

			}, 500);
		}
		else {
			$( '.ss_ul li' ).show();
			setTimeout( function() {
				if( sel_text != 'Course Name' ) {
					$( '.ss_ul li' ).first().click();
				}
			}, 500);
		}
	});

	$('#state1').change(function() {
		var state_val = $(this).val();
		$('#location1 option:eq(0)').prop('selected', true);
		if( state_val ) {
			$('#location1 option').each(function() {
				var location_parent = $(this).attr('parent_tax');
				if( state_val == location_parent ) {
					$(this).show();
				}
				else {
					$(this).hide();
				}
			});
		}
		else {
			$('#location1 option').show();
		}
	});	

	setTimeout( function() {
		if ( $('#coursename1 .default-selected').length ) { 
			$('.ss_ul li').each(function() {
				var def_li_id = $(this).attr('data-title');
				var def_id = $('#coursename1 .default-selected').text();
				if( def_li_id == def_id ) {
					$( this ).click();
					$('#searchbtn').submit();
				}
			});
		}
	}, 500);

	$('.tutis-delete-student').tooltip();

	disabled_button_student();

	$('#zero-btn1').click(function() {
		$('#tutis-final-payment1').submit();
	});

});


