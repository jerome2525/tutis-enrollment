jQuery(document).ready(function($){
	// Function to create the cookie
	function tutis_create_cookie(name, value, days) {
		var expires;
	      
	    if (days) {
	        var date = new Date();
	        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
	        expires = "; expires=" + date.toGMTString();
	    }
	    else {
	        expires = "";
	    }
	      
	    document.cookie = escape(name) + "=" + 
		escape(value) + expires + "; path=/";
	}

	function tutis_get_offset_time() {
		var timezone_offset_minutes = new Date().getTimezoneOffset();
		timezone_offset_minutes = timezone_offset_minutes == 0 ? 0 : -timezone_offset_minutes;

		// Timezone difference in minutes such as 330 or -360 or 0
		return timezone_offset_minutes;
	}

	tutis_create_cookie('tutis_offset_time', tutis_get_offset_time(), '10');

});	