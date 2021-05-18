jQuery(document).ready(function($){
	$('.button-reset').click(function(e) {
		e.preventDefault();
		if( confirm('Warning this will delete all of your existing data and reload it from TUTIS, this may take  a while to complete and put a heavy load on your server. Are you sure you want to do this now?') ) {
			$('#reset-form1').submit();
		}
	});
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
           			setTimeout(function() {
           				location.reload();
           			}, 3000);
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

    $('.button-pause').click(function() {
		if ( $(this).hasClass('clicked') ) {    
			$(this).removeClass('clicked');	
			$('.button-sync').addClass('auto_submit_class');
			$(this).html('Pause <span class="dashicons dashicons-controls-pause"></span>');
			$('.auto_submit_class').click();
		} 
		else {
			$(this).addClass('clicked');
			$('.button-sync').removeClass('auto_submit_class');
			$(this).html('Play <span class="dashicons dashicons-controls-play"></span>');
		}
    });

	$( '#tutis-tabs' ).tabs();
	setTimeout(function (){
		$('.auto_submit_class').click();
	}, 4000);

});	