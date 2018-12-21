<?php
/**
 * Main core class file
 *
 * This is used to define internationalization, admin specific hooks, and
 * public specific hooks.
 *
 * @since      1.0
 * @package    LocationService
 * @subpackage LocationService/lib
 *
 */

class LocationService {

	public function __construct() {

		$this->load_dependencies();

	}

	/**
	 * Loads all the dependencies of this plugin
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		$root_dir = plugin_dir_path( dirname( __FILE__ ) );
		require_once $root_dir . 'lib/class-locationservice-helper.php';
		require_once $root_dir . 'lib/class-locationservice-path.php';
		require_once $root_dir . 'lib/class-locationservice-i18n.php';
		require_once $root_dir . '/lib/class-locationservice-settings.php';
		require_once $root_dir . '/lib/class-locationservice-cpt.php';
		require_once $root_dir . 'lib/class-locationservice-assets.php';
		require_once $root_dir . 'lib/class-locationservice-shortcodes.php';
		require_once $root_dir . 'lib/class-locationservice-request-handler.php';

	}

	/**
	 * Register all of the action, hooks, filters and shortcodes with WordPress.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function run () {


		//load plugin text domain
		new LocationService_i18n();

		// enqueue styles and scripts
		new LocationService_Assets();

		// load admin option page
		if(is_admin()) {

			new LocationService_Settings();
            new LocationService_CustomPostType();

		}

		//register all shortcodes
		new LocationService_Shortcodes();

		//handles all the request from end point
		new LocationService_RequestHandler();

		/*if ( class_exists( 'GFCommon' ) ) {
			//plugin is activated, do something
			//new LocationService_GravityForms();
		}*/


	}
}
