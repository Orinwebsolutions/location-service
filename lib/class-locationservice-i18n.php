<?php
/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 *
 * @package    LocationService
 * @subpackage LocationService/lib
 */

class LocationService_i18n {
	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */

	public function __construct() {

		$this->set_locale();

	}


	public function set_locale() {

		add_action( 'plugins_loaded', array( $this, 'load_text_domain' ) );

	}

	public function load_text_domain() {

		load_plugin_textdomain( 
			str_replace( PLUGIN_NAME, '_', '-'), 
			FALSE, 
			LocationServicePath::root_dir('/languages/') );

	}
}