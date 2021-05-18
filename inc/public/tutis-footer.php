<?php

class Tutis_Footer {
	public function __construct() {
        $this->load_hooks();
    }

    public function load_hooks() {
        add_action( 'wp_footer', array( $this, 'display_footer' ) ); 
    }

    public function securepay_api() {
    	if( get_option('tutis_secure_pay_client_id') && get_option('tutis_secure_pay_merchant_code') ) { 
    		if( get_option('tutis_sandbox') == 1 ) {
    			$secure_url = 'https://payments.auspost.net.au';
    		}
    		else {
    			$secure_url = 'https://payments-stest.npe.auspost.zone';
    		}
    	?>
		    <script id="securepay-ui-js" src="<?php echo $secure_url; ?>/v3/ui/client/securepay-ui.min.js"></script>
		    <script type="text/javascript">
		      var mySecurePayUI = new securePayUI.init({
		        containerId: 'securepay-ui-container',
		        scriptId: 'securepay-ui-js',
		        clientId: '<?php echo get_option('tutis_secure_pay_client_id'); ?>',
		        merchantCode: '<?php echo get_option('tutis_secure_pay_merchant_code'); ?>',
		        card: {
		            showCardIcons: true,
		            onCardTypeChange: function(cardType) {
		              // card type has changed
		              console.log(cardType); 
		            },
		            onBINChange: function(cardBIN) {
		              // card BIN has changed
		            },
		            onFormValidityChange: function(valid) {
		              // form validity has changed
		            },
		            onTokeniseSuccess: function(tokenisedCard) {
		            	console.log(tokenisedCard.scheme); 
		            	jQuery('#securepay_card_type_field').val(tokenisedCard.scheme.toUpperCase());  
		                jQuery('#securepay_token_field').val(tokenisedCard.token);  
		                jQuery('#tutis-final-payment1').submit();    

		              // card was successfully tokenised or saved card was successfully retrieved 
		            },
		            onTokeniseError: function(errors) {
		              // tokenization failed
		            }
		        },
		        onLoadComplete: function () {
		          // the UI Component has successfully loaded and is ready to be interacted with
		        }
		      });
		    </script>
  		<?php 
  		} 
    }

    public function display_footer() {
    	if( isset( $_GET['temp_id'] ) ) {
			$this->securepay_api();
		}
    }
}

new Tutis_Footer;