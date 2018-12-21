<?php
/**
 * Path helper class for asset 
 *
 * @since      1.0
 * @package    LocationService
 * @subpackage LocationService/lib
 *
 */
class LocationServicePath {

	private static $public_path = 'public/assets/dev/';
	private static $admin_path = 'admin/assets/dev/';
	private static $public_views_path = 'public/views/';

	
	/**
	 * Return root directory of the plugin
	 *
	 * @since    1.0.0
	 * @access   public
	 * @return   string
	 *
	 */
	public static function root_dir( $path = '' ) {

		return plugin_dir_path( dirname( __FILE__ ) ) . $path;

	}

	/**
	 * Return root directory url of the plugin
	 *
	 * @since    1.0.0
	 * @access   public
	 * @return   string
	 *
	 */
	public static function root_url() {

		return plugin_dir_url( dirname( __FILE__ ) );

	}


	/**
	 * Return full public dist assets path of the specified file
	 *
	 * @since    1.0.0
	 * @access   public
	 * @param    string
	 * @return   string
	 *
	 */
	public static function public_asset( $filename = '' ) {


		if( !empty( $filename ) ) {

			return plugin_dir_url( dirname( __FILE__ ) ) . self::$public_path. $filename; 

		}
		
	}


	/**
	 * Return full admin dist assets path of the specified file
	 *
	 * @since    1.0.0
	 * @access   public
	 * @param    string
	 * @return   string
	 *
	 */
	public static function admin_asset( $filename = '' ) {

		if( !empty( $filename ) ) {

			return plugin_dir_url( dirname( __FILE__ ) ) . self::$admin_path. $filename; 

		}

	}


	/**
	 * Return full views path of the specified file
	 *
	 * @since    1.0.0
	 * @access   public
	 * @param    string
	 * @return   string
	 *
	 */
	public static function public_views( $filename = '' ) {

		if( !empty( $filename ) ) {

			return plugin_dir_path( dirname( __FILE__ ) ) . self::$public_views_path. $filename; ;

		}

	}

}
