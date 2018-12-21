<?php
/**
 * Main admin settings page
 *
 *
 * @since      1.0
 * @package    LocationService
 * @subpackage LocationService/lib
 *
 */
class LocationService_Settings
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Service Location Finder Settings',
            'Service Location Finder Settings',
            'manage_options',
            'service-location-setting-admin',
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'locationservice_option_name' );
        ?>
        <div class="wrap">
            <h1>Settings</h1>
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'locationservice_option_group' );
                do_settings_sections( 'my-setting-admin' );
                submit_button();
            ?>
            </form>
        </div>
        <?php
    }

     /**
     * Register and add settings
     */
    public function page_init()
    {
        register_setting(
            'locationservice_option_group', // Option group
            'locationservice_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'Location Service Locator Settings', // Title
            array( $this, 'print_section_info' ), // Callback
            'my-setting-admin' // Page
        );

        add_settings_field(
            'google_map_api', // ID
            'Google MAPS API KEY', // Title
            array( $this, 'google_map_api_callback' ), // Callback
            'my-setting-admin', // Page
            'setting_section_id' // Section
        );

        add_settings_field(
            'default_radius',
            'Default Radius in Km',
            array( $this, 'default_radius_callback' ),
            'my-setting-admin',
            'setting_section_id'
        );

        add_settings_field(
            'main_search_category',
            'Main Search Category Name',
            array( $this, 'main_category_callback' ),
            'my-setting-admin',
            'setting_section_id'
        );
    }

    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();


        if( isset( $input['google_map_api'] ) )
            $new_input['google_map_api'] = sanitize_text_field( $input['google_map_api'] );

         if( isset( $input['default_radius'] ) )
            $new_input['default_radius'] = absint( $input['default_radius'] );

        if( isset( $input['main_search_category'] ) )
            $new_input['main_search_category'] = sanitize_text_field( $input['main_search_category'] );

        return $new_input;
    }

    /**
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Enter your settings below:';
    }


     /**
     * Print the Gravity Section text
     */
    public function print_gravity_forms_info()
    {
        print 'Enter your settings below:';
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function default_radius_callback()
    {
        printf(
            '<input type="text" id="default_radius" name="locationservice_option_name[default_radius]" value="%s" /><span>km</span>',
            isset( $this->options['default_radius'] ) ? esc_attr( $this->options['default_radius']) : ''
        );
    }

    /**
     * Get the settings option array and print one of its values
     */
    public function google_map_api_callback()
    {
        printf(
            '<input type="text" id="google_map_api" name="locationservice_option_name[google_map_api]" value="%s" />',
            isset( $this->options['google_map_api'] ) ? esc_attr( $this->options['google_map_api']) : ''
        );
    }

    public function main_category_callback()
    {
        printf(
            '<input type="text" id="main_search_category" name="locationservice_option_name[main_search_category]" value="%s" />',
            isset( $this->options['main_search_category'] ) ? esc_attr( $this->options['main_search_category']) : ''
        );
    }


}
?>
