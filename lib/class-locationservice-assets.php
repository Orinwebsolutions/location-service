<?php
/**
 * Register all actions and filters for the plugin
 *
 * @since      1.0
 * @package    LocationService
 * @subpackage LocationService/lib
 *
 */
class LocationService_Assets {


	private $plugin_name = 'location_service';
	private $plugin_options;
	private $script_local_data;

	public function __construct() {

		if ( defined( 'PLUGIN_NAME' ) ) {

			$this->plugin_name = PLUGIN_NAME;

		}

		$this->public_hooks();
		$this->admin_hooks();
		$this->plugin_options = get_option( 'locationservice_option_name' );


	}


	/**
	 * Register all of the hooks related to the public area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   public
	 */

	public function public_hooks() {

		add_action( 'wp_enqueue_scripts', array( $this, 'load_public_scripts' ) );

	}

	/**
	 * Register all public styles and scripts
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   public
	 */

	public function load_public_scripts() {

		wp_enqueue_style( $this->plugin_name.'_public_style',
			LocationServicePath::public_asset( 'css/main.css' )
		);

		wp_register_script( $this->plugin_name.'_mustache',
			LocationServicePath::public_asset( 'js/mustache.min.js' ),
			array('jquery')
		);
		wp_register_script( $this->plugin_name.'_public_map_script',
			LocationServicePath::public_asset( 'js/gmaps.js' ),
			array('jquery', $this->plugin_name.'_mustache')
		);
		wp_register_script( $this->plugin_name.'_public_script',
			LocationServicePath::public_asset( 'js/main.js' ),
			array('jquery', $this->plugin_name.'_public_map_script')
		);




		$this->script_local_data = array(

			'googleMapsAPI' 	=> !empty($this->plugin_options['google_map_api'])?$this->plugin_options['google_map_api']:GOOGLE_MAPS_API,
			'defaultRadius' 	=> !empty($this->plugin_options['default_radius'])?$this->plugin_options['default_radius']:DEFAULT_RADIUS,
			'mainsearchcategory' 	=> !empty($this->plugin_options['main_search_category'])?$this->plugin_options['main_search_category']:MAIN_SEARCH_CATEGORY,
			'ajaxUrl' 			=> admin_url('admin-ajax.php'),
			'ajaxNonce' 		=> wp_create_nonce('ajax_nonce'),
			'imagePath' 		=> LocationServicePath::public_asset( 'images/' ),

		);
		wp_localize_script( $this->plugin_name.'_public_script',
			'locationservice',
			$this->script_local_data
		);
		$googleMapsAPI	= !empty($this->plugin_options['google_map_api'])?$this->plugin_options['google_map_api']:GOOGLE_MAPS_API;

		/*wp_register_script( $this->plugin_name.'_map_loads',
			//( '//maps.googleapis.com/maps/api/js?key='.$googleMapsAPI.'&libraries=places' ),
			( '//maps.googleapis.com/maps/api/js?key=AIzaSyDLHr8u2lbW4j45jw5g4PKcCEwek-AgUwk&libraries=places' ),
			array('jquery', $this->plugin_name.'_public_script')
		);
		wp_enqueue_script( $this->plugin_name.'_map_loads' );*/
		wp_enqueue_script( $this->plugin_name.'_public_script' );

	}


	/**
	 * Register all of the scripts and styles related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   public
	 */

	public function admin_hooks() {

		add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_scripts' ) );

	}

	/**
	 * Register all admin styles and scripts
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   public
	 */

	public function load_admin_scripts() {

		wp_enqueue_style( $this->plugin_name.'_admin_style',
			LocationServicePath::admin_asset( 'css/main.css' )
		);

	}

}
