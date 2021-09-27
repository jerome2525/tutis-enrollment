jQuery(document).ready(function($){
	$('.button-reset').click(function(e) {
		e.preventDefault();
		$('#reset-form1').submit();
	});

	var getUrlParameter = function getUrlParameter(sParam) {
		var sPageURL = window.location.search.substring(1),
	        sURLVariables = sPageURL.split('&'),
	        sParameterName,
	        i;

		for (i = 0; i < sURLVariables.length; i++) {
	        sParameterName = sURLVariables[i].split('=');

	        if (sParameterName[0] == sParam) {
	            return true;
	        }
	    }
	    return false;
	};
	
	function admin_ws_filter_ajx(filter, res_id) {
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
           			if(res_id != '#restresult1') {
	           			setTimeout(function() {
	           				//location.reload();
	           				window.location.href = window.location.href.replace( /[\?#].*|$/, "?page=tutis-settings&reload=1" );
	           			}, 3000);
           			}
                },
                async: "false",
            });
        }
    }

    $('#sync-form1').submit(function(){
		var res_id = '#adminresult1';
		admin_ws_filter_ajx(this, res_id );
		return false;
    });

    $('#reset-form1').submit(function(){
		var res_id = '#reset-form1';
		admin_ws_filter_ajx(this, res_id );
		return false;
    });

    $('#update-form1').submit(function(){
		var res_id = '#update-form1';
		admin_ws_filter_ajx(this, res_id );
		return false;
    });

    $('#rest-form1').submit(function(){
		var res_id = '#restresult1';
		admin_ws_filter_ajx(this, res_id );
		return false;
    });

	$( '#tutis-tabs' ).tabs();
	
	setTimeout(function (){
		$('.auto_submit_class').click();
	}, 4000);

	$('.button-reset').tooltip();

	setTimeout(function (){
		if( getUrlParameter('reload') ) {
			$('.tutis-button-row p.submit .button-primary').removeClass('disabled');
		}
	}, 60000);

  	$('.confirm-btn').confirm({
	    title: 'Confirm New Credentials',
	    content: '<p>This action is about to generate new credentials for communication between Tutis and the Enrolment Plugin.</p> <p>The existing connection will be broken until you update Tutis.</p><p>Do you wish to continue ?</p>',
	    buttons: {
	        yes: {
	            text: 'Yes',
	            action: function(){
	            	$('#rest-form1').submit();
	            }
	        },
	        close: {
	            text: 'No',
	        }
	    }
	});

});	