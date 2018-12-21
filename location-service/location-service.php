<?php
/**
* Plugin Name: Location Service
* Plugin URI: https://wordpress.org/
* Description: Location center locator
* Version: 1.0
* Author: Amila Priyankara
* License: GPLv2 or later
* Domain Path: /languages
*/

/**
 *
 * The code that runs during plugin activation.
 *
 */
if ( !defined( 'ABSPATH' ) ) {

	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;

}

define( 'PLUGIN_NAME', 'Location Service' );
define( 'DEFAULT_RADIUS', '45' ); // in kms
define( 'MAIN_SEARCH_CATEGORY', 'Shelters' ); // in kms


/**
 * The core plugin class.
 */

require_once plugin_dir_path( __FILE__ ) . '/lib/class-locationservice.php';
$locationservice = new LocationService();
$locationservice->run();
?>
