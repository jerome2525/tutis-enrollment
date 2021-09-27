<?php
class Tutis_Rest extends WP_REST_Controller {
	private $api_namespace;
	private $base;
	private $api_version;
	private $required_capability;
	
	public function __construct() {
		$this->api_namespace = 'tutis_rest/v';
		$this->base = 'update';
		$this->api_version = '1';
		$this->required_capability = 'read'; 
		$this->init();
	}
	
	public function register_routes() {
		$namespace = $this->api_namespace . $this->api_version;
		
		register_rest_route( $namespace, '/' . $this->base, array(
			array( 'methods' => 'GET', 
				'callback' => array( $this, 'tutis_api_rest' ), 
				'permission_callback' => false,
				'args'     => [
					'service' => [
						'required' => true,
					],
				]
			),
		)  );
	}

	// Register our REST Server
	public function init(){
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}
	
	public function tutis_api_rest( WP_REST_Request $request ) {

		$service = $request->get_param( 'service' );
		$bearer_wp_token = get_option('tutis_rest_token');
		$headers = getallheaders();
		$bearer_token = $headers['token'];

		if( empty( $bearer_wp_token ) ) {
			return new WP_Error( 'invalid-method', 'Please Generate a Token first in the wordpress admin!', array( 'status' => 400 /* Bad Request */ ) );
		}

		if( empty( $service ) ) {
			return new WP_Error( 'invalid-method', 'Please add a service parameter!', array( 'status' => 400 /* Bad Request */ ) );
		}

		if( empty( $bearer_token ) ) {
			return new WP_Error( 'invalid-method', 'Please add a token parameter!', array( 'status' => 400 /* Bad Request */ ) );
		}

		if( $bearer_token == $bearer_wp_token ) {
			if( $service == 'offering' ) {
				update_option('tutis_offering_update', 'active');
				return $service . ' Successfully updated!';
			}
			else if( $service == 'course' ) {
				update_option('tutis_program_update', 'active');
        		return $service . ' Successfully updated!';
			}
			else if( $service == 'location' ) {
				update_option('tutis_state_update', 'active');
        		return $service . ' Successfully updated!';
			}
			else if( $service == 'setting' ) {
				update_option('tutis_setting_update', 'active');
        		return $service . ' Successfully updated!';
			}
			else if( $service == 'all' ) {
				update_option('tutis_all_update', 'active');
        		return $service . ' Successfully updated!';
			}
			
			else {
				return new WP_Error( 'invalid-method', 'Invalid Service!', array( 'status' => 400 /* Bad Request */ ) );
			}
		}
		else {
			return new WP_Error( 'invalid-method', 'Please Invalid Token!', array( 'status' => 400 /* Bad Request */ ) );
		}
		
	}
}
 
$Tutis_Rest = new Tutis_Rest();
?>