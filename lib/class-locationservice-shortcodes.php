<?php
/**
 * Register all schortcodes
 *
 * @since      1.0
 * @package    LocationService
 * @subpackage LocationService/lib
 *
 */
class LocationService_Shortcodes {


	public function __construct() {

		$this->register_shortcodes();

	}

	/**
	 * Register shortcode to display the main locationservice finder page
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   public
	 */

	public function register_shortcodes() {

		add_shortcode( 'displayfinder', array( new LocationService_RequestHandler, 'display_finder' ) );

        if (function_exists('vc_map')) :
            vc_map(array(
               "name" => __("Location Finder"),
               "base" => "displayfinder",
               "category" => __('Leafcutter')
            ));
        endif;

		add_shortcode( 'displayfinderresults', array( new LocationService_RequestHandler, 'display_finder_results' ) );

        if (function_exists('vc_map')) :
            vc_map(array(
               "name" => __("Location Finder Results"),
               "base" => "displayfinderresults",
               "category" => __('Leafcutter')
            ));
        endif;
	}

}
