<?php
/*
Plugin Name: Tutis Enrollment Plugin
Description: Customize plugin for Tutis client that connect to its API
Plugin URI: https://fixedwp.com/
Author: Tutis
Author URI: https://www.tutis.com.au/
Version: 1.0
License: GPL2
Text Domain: tutis-enrollment
*/

require plugin_dir_path( __FILE__ ) . 'inc/tutis-enrollment.php';

new Tutis_Enrollment();



