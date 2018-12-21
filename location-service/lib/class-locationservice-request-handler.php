<?php
/**
 * Handles request from the user including ajax request, shortcodes
 *
 *
 * @since      1.0
 * @package    LocationService
 * @subpackage LocationService/lib
 *
 */

class LocationService_RequestHandler {

	public function __construct() {
		add_action( 'wp_ajax_load_location_data',  array( $this, 'load_location_data' ) );
		add_action( 'wp_ajax_nopriv_load_location_data', array( $this, 'load_location_data' ) );

	}


	/**
	 * Reads locationservice json file and outputs as a json data
	 *
	 * @since    1.0.0
	 * @access   public
	 * @return 	 jsonData
	 */
	public function load_location_data () {

		if ( !wp_verify_nonce( $_REQUEST['nonce'], 'ajax_nonce')) {

		  exit( "No naughty business please!");

		}
        global $wpdb;
        $sql = "SELECT DISTINCT wp_posts.ID, wp_posts.post_title, wp_postmeta.meta_id, wp_postmeta.meta_key, wp_postmeta.meta_value
           FROM wp_posts
           JOIN wp_postmeta ON wp_posts.ID = wp_postmeta.post_ID
           WHERE wp_posts.post_type = 'locations-centers' AND wp_posts.post_status = 'publish'";
        // call DB
        $results = $wpdb->get_results( $sql );

        if ( ! empty( $results ) ) {
            $previous_id = 0;
            $structured_results = array();
            foreach ( $results as $post ) {
                if ( $previous_id !== $post->ID ) {
                    $structured_results[ $post->ID ] = array(
                        'ID' => $post->ID,
                        'post_title' => $post->post_title,
                        // etc for all other columns in wp_posts
                        $post->meta_key => $post->meta_value
                    );
                } else {
                    $structured_results[ $post->ID ][$post->meta_key] = $post->meta_value;

                }
                $previous_id = $post->ID;
            }
        }

		$result = json_encode($structured_results);
        echo($result);
		wp_die();

	}


	/**
	 * Handles display finder schortcode
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public static function display_finder( $shortcode_options ) {

		//plugin option
		$plugin_options = get_option( 'locationservice_option_name' );

		//shortcode option
		$shortcode_options = shortcode_atts( array(
		'gravity_id' => NULL
		), $shortcode_options, 'displayfinder' );

		if( empty( $plugin_options['google_map_api'] ) ) {
			wp_die( 'Please configure Google Maps API Key from the settings page to run G8 LocationService service locator.' );
		}

		ob_start();
		require_once( LocationServicePath::public_views( 'display_finder.php' ) );
		$html_finder = ob_get_contents();
		ob_get_clean();

		return $html_finder;
	}

   public static function display_finder_results( $shortcode_options ) {

       //plugin option
       $plugin_options = get_option( 'locationservice_option_name' );
       if( empty( $plugin_options['google_map_api'] ) ) {
           wp_die( 'Please configure Google Maps API Key from the settings page to run G8 LocationService service locator.' );
       }

       ob_start();
       require_once( LocationServicePath::public_views( 'display_finder_results.php' ) );
       $html_finder = ob_get_contents();
       ob_get_clean();

       return $html_finder;
   }
}
