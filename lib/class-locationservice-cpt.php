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
class LocationService_CustomPostType
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
        add_action( 'init', array( $this, 'register_location_post_type' ) );
        add_action( 'add_meta_boxes', array( $this, 'wpt_add_event_metaboxes'));
        add_action( 'save_post', array( $this, 'wpt_save_events_meta'), 1, 2 );
    }

    /*
    * Creating a function to create our CPT
    */
    public function register_location_post_type() {
        // Set UI labels for Custom Post Type
        $labels = array(
            'name'                => _x( 'Locations', 'Post Type General Name', 'twentythirteen' ),
            'singular_name'       => _x( 'Location', 'Post Type Singular Name', 'twentythirteen' ),
            'menu_name'           => __( 'Locations', 'twentythirteen' ),
            'parent_item_colon'   => __( 'Parent Location', 'twentythirteen' ),
            'all_items'           => __( 'All Locations', 'twentythirteen' ),
            'view_item'           => __( 'View Location', 'twentythirteen' ),
            'add_new_item'        => __( 'Add New Location', 'twentythirteen' ),
            'add_new'             => __( 'Add New', 'twentythirteen' ),
            'edit_item'           => __( 'Edit Location', 'twentythirteen' ),
            'update_item'         => __( 'Update Location', 'twentythirteen' ),
            'search_items'        => __( 'Search Location', 'twentythirteen' ),
            'not_found'           => __( 'Not Found', 'twentythirteen' ),
            'not_found_in_trash'  => __( 'Not found in Trash', 'twentythirteen' ),
        );

        // Set other options for Custom Post Type
        $args = array(
            'label'               => __( 'locations', 'location-RSPCA' ),
            'description'         => __( 'Location news and reviews', 'location-RSPCA' ),
            'labels'              => $labels,
            // Features this CPT supports in Post Editor
            'supports'            => array( 'title', 'thumbnail' ),
            // You can associate this CPT with a taxonomy or custom taxonomy.
            /* A hierarchical CPT is like Pages and can have
            * Parent and child items. A non-hierarchical CPT
            * is like Posts.
            */
            'hierarchical'          => false,
            'public'                => false,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'show_in_nav_menus'     => false,
            'show_in_admin_bar'     => true,
            'menu_position'         => 30,
            'can_export'            => true,
            'has_archive'           => false,
            'exclude_from_search'   => true,
            'publicly_queryable'    => false,
            'capability_type'       => 'page',
            'menu_icon'             => 'dashicons-location-alt'
        );

        // Registering your Custom Post Type
        register_post_type( 'locations-centers', $args );

    }
    /*
    * Register the Metaboxes
    */
    public function wpt_add_event_metaboxes() {
        add_meta_box(
    		'location_category',
    		'Location - Category',
    		array($this, 'location_category'),
    		'locations-centers',
    		'normal',
    		'default'
    	);
        add_meta_box(
            'location_service',
            'Location - Service',
            array($this, 'location_service'),
            'locations-centers',
            'normal',
            'default'
        );
        add_meta_box(
    		'location_address',
    		'Location - Address',
    		array($this, 'location_address'),
    		'locations-centers',
    		'normal',
    		'default'
    	);
        add_meta_box(
    		'location_postcode',
    		'Location - Postcode',
    		array($this, 'location_postcode'),
    		'locations-centers',
    		'normal',
    		'default'
    	);
        add_meta_box(
    		'location_phone',
    		'Location - Phone',
    		array($this, 'location_phone'),
    		'locations-centers',
    		'normal',
    		'default'
    	);
        add_meta_box(
            'location_email',
            'Location - Email',
            array($this, 'location_email'),
            'locations-centers',
            'normal',
            'default'
        );
        add_meta_box(
            'location_hours',
            'Location - Hours(Week Days)',
            array($this, 'location_hours'),
            'locations-centers',
            'normal',
            'default'
        );
        add_meta_box(
            'location_hours_02',
            'Location - Hours(Week Ends)',
            array($this, 'location_hours_02'),
            'locations-centers',
            'normal',
            'default'
        );
        add_meta_box(
    		'location_latitude',
    		'Location - Latitude',
    		array($this, 'location_latitude'),
    		'locations-centers',
    		'normal',
    		'default'
    	);
        add_meta_box(
    		'location_longitude',
    		'Location - Longitude',
    		array($this, 'location_longitude'),
    		'locations-centers',
    		'normal',
    		'default'
    	);
        add_meta_box(
            'location_url',
            'Location - URL',
            array($this, 'location_url'),
            'locations-centers',
            'normal',
            'default'
        );
    }
    /**
    * Output the HTML for each the metabox.
    */
    public function location_address() {
    	global $post;
    	// Nonce field to validate form request came from current site
    	wp_nonce_field( basename( __FILE__ ), 'location_fields' );
    	// Get the location data if it's already been entered
    	$location_address = get_post_meta( $post->ID, 'location_address', true );
    	// Output the field
    	echo '<input type="text" name="location_address" value="' . esc_textarea( $location_address )  . '" class="widefat">';
    }    
    public function location_postcode() {
    	global $post;
    	// Nonce field to validate form request came from current site
    	wp_nonce_field( basename( __FILE__ ), 'location_fields' );
    	// Get the location data if it's already been entered
    	$location_postcode = get_post_meta( $post->ID, 'location_postcode', true );
    	// Output the field
    	echo '<input type="text" name="location_postcode" value="' . esc_textarea( $location_postcode )  . '" class="widefat">';
    }
    public function location_category() {
    	global $post;
    	// Nonce field to validate form request came from current site
    	wp_nonce_field( basename( __FILE__ ), 'location_fields' );
    	// Get the location data if it's already been entered
    	$location_category = get_post_meta( $post->ID, 'location_category', true );
    	// Output the field
    	echo '<input type="text" name="location_category" value="' . esc_textarea( $location_category )  . '" class="widefat">';
    }
    public function location_phone() {
    	global $post;
    	// Nonce field to validate form request came from current site
    	wp_nonce_field( basename( __FILE__ ), 'location_fields' );
    	// Get the location data if it's already been entered
    	$location_phone = get_post_meta( $post->ID, 'location_phone', true );
    	// Output the field
    	echo '<input type="text" name="location_phone" value="' . esc_textarea( $location_phone )  . '" class="widefat">';
    }
    public function location_latitude() {
    	global $post;
    	// Nonce field to validate form request came from current site
    	wp_nonce_field( basename( __FILE__ ), 'location_fields' );
    	// Get the location data if it's already been entered
    	$location_latitude = get_post_meta( $post->ID, 'location_latitude', true );
    	// Output the field
    	echo '<input type="text" name="location_latitude" value="' . esc_textarea( $location_latitude )  . '" class="widefat">';
    }
    public function location_longitude() {
    	global $post;
    	// Nonce field to validate form request came from current site
    	wp_nonce_field( basename( __FILE__ ), 'location_fields' );
    	// Get the location data if it's already been entered
    	$location_longitude = get_post_meta( $post->ID, 'location_longitude', true );
    	// Output the field
    	echo '<input type="text" name="location_longitude" value="' . esc_textarea( $location_longitude )  . '" class="widefat">';
    }
    public function location_service() {
        global $post;
        // Nonce field to validate form request came from current site
        wp_nonce_field( basename( __FILE__ ), 'location_fields' );
        // Get the location data if it's already been entered
        $location_service = get_post_meta( $post->ID, 'location_service', true );
        // Output the field
        echo '<input type="text" name="location_service" value="' . esc_textarea( $location_service )  . '" class="widefat">';
    }
    public function location_email() {
        global $post;
        // Nonce field to validate form request came from current site
        wp_nonce_field( basename( __FILE__ ), 'location_fields' );
        // Get the location data if it's already been entered
        $location_email = get_post_meta( $post->ID, 'location_email', true );
        // Output the field
        echo '<input type="text" name="location_email" value="' . esc_textarea( $location_email )  . '" class="widefat">';
    }
    public function location_hours() {
        global $post;
        // Nonce field to validate form request came from current site
        wp_nonce_field( basename( __FILE__ ), 'location_fields' );
        // Get the location data if it's already been entered
        $location_hours = get_post_meta( $post->ID, 'location_hours', true );
        // Output the field
        echo '<input type="text" name="location_hours" value="' . esc_textarea( $location_hours )  . '" class="widefat">';
    }
    public function location_hours_02() {
        global $post;
        // Nonce field to validate form request came from current site
        wp_nonce_field( basename( __FILE__ ), 'location_fields' );
        // Get the location data if it's already been entered
        $location_hours_02 = get_post_meta( $post->ID, 'location_hours_02', true );
        // Output the field
        echo '<input type="text" name="location_hours_02" value="' . esc_textarea( $location_hours_02 )  . '" class="widefat">';
    }
    public function location_url() {
        global $post;
        // Nonce field to validate form request came from current site
        wp_nonce_field( basename( __FILE__ ), 'location_fields' );
        // Get the location data if it's already been entered
        $location_url = get_post_meta( $post->ID, 'location_url', true );
        // Output the field
        echo '<input type="text" name="location_url" value="' . esc_textarea( $location_url )  . '" class="widefat">';
    }                
    /**
     * Save the metabox data
     */
    function wpt_save_events_meta( $post_id, $post ) {
    	// Return if the user doesn't have edit permissions.
    	if ( ! current_user_can( 'edit_post', $post_id ) ) {
    		return $post_id;
    	}
    	// Verify this came from the our screen and with proper authorization,
    	// because save_post can be triggered at other times.
    	if ( ! isset( $_POST['location_address'] ) || ! wp_verify_nonce( $_POST['location_fields'], basename(__FILE__) ) ) {
    		return $post_id;
    	}
    	if ( ! isset( $_POST['location_postcode'] ) || ! wp_verify_nonce( $_POST['location_fields'], basename(__FILE__) ) ) {
    		return $post_id;
    	}
    	if ( ! isset( $_POST['location_category'] ) || ! wp_verify_nonce( $_POST['location_fields'], basename(__FILE__) ) ) {
    		return $post_id;
    	}
        if ( ! isset( $_POST['location_service'] ) || ! wp_verify_nonce( $_POST['location_fields'], basename(__FILE__) ) ) {
            return $post_id;
        }
    	if ( ! isset( $_POST['location_phone'] ) || ! wp_verify_nonce( $_POST['location_fields'], basename(__FILE__) ) ) {
    		return $post_id;
    	}
        if ( ! isset( $_POST['location_email'] ) || ! wp_verify_nonce( $_POST['location_fields'], basename(__FILE__) ) ) {
            return $post_id;
        }
        if ( ! isset( $_POST['location_hours'] ) || ! wp_verify_nonce( $_POST['location_fields'], basename(__FILE__) ) ) {
            return $post_id;
        }
        if ( ! isset( $_POST['location_hours_02'] ) || ! wp_verify_nonce( $_POST['location_fields'], basename(__FILE__) ) ) {
            return $post_id;
        }
    	if ( ! isset( $_POST['location_latitude'] ) || ! wp_verify_nonce( $_POST['location_fields'], basename(__FILE__) ) ) {
    		return $post_id;
    	}
    	if ( ! isset( $_POST['location_longitude'] ) || ! wp_verify_nonce( $_POST['location_fields'], basename(__FILE__) ) ) {
    		return $post_id;
    	}
        if ( ! isset( $_POST['location_url'] ) || ! wp_verify_nonce( $_POST['location_fields'], basename(__FILE__) ) ) {
            return $post_id;
        }
    	// Now that we're authenticated, time to save the data.
    	// This sanitizes the data from the field and saves it into an array $events_meta.
    	$events_meta['location_address'] = ( $_POST['location_address'] );
    	$events_meta['location_postcode'] = esc_textarea( $_POST['location_postcode'] );
    	$events_meta['location_category'] = esc_textarea( $_POST['location_category'] );
        $events_meta['location_service'] = esc_textarea( $_POST['location_service'] );
    	$events_meta['location_phone'] = esc_textarea( $_POST['location_phone'] );
        $events_meta['location_email'] = esc_textarea( $_POST['location_email'] );
        $events_meta['location_hours'] = ( $_POST['location_hours'] );
        $events_meta['location_hours_02'] = ( $_POST['location_hours_02'] );
    	$events_meta['location_latitude'] = esc_textarea( $_POST['location_latitude'] );
    	$events_meta['location_longitude'] = esc_textarea( $_POST['location_longitude'] );
        $events_meta['location_url'] = esc_textarea( $_POST['location_url'] );
    	// Cycle through the $events_meta array.
    	// Note, in this example we just have one item, but this is helpful if you have multiple.
    	foreach ( $events_meta as $key => $value ) :
    		// Don't store custom data twice
    		if ( 'revision' === $post->post_type ) {
    			return;
    		}
    		if ( get_post_meta( $post_id, $key, false ) ) {
    			// If the custom field already has a value, update it.
    			update_post_meta( $post_id, $key, $value );
    		} else {
    			// If the custom field doesn't have a value, add it.
    			add_post_meta( $post_id, $key, $value);
    		}
    		if ( ! $value ) {
    			// Delete the meta key if there's no value
    			delete_post_meta( $post_id, $key );
    		}
    	endforeach;
    }
}
?>
