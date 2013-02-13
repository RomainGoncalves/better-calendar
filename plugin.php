<?php
/*
Plugin Name: Better Calendar
Plugin URI: http://www.gotthefevermedia.com.au
Description: A better Worpress calendar for simple people
Version: 1.0
Author: Got The Fever Media
Author URI: http://www.gotthefevermedia.com.au
Author Email: gotthefevermedia@gmail.com
License:

  Copyright 2013 GNU General Public License (gotthefevermedia@gmail.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as 
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
  
*/

include_once dirname( __FILE__ ) . '/includes/custom-post-types.php';

// TODO: rename this class to a proper name for your plugin
class BetterCalendar {
	 
	/*--------------------------------------------*
	 * Constructor
	 *--------------------------------------------*/
	
	/**
	 * Initializes the plugin by setting localization, filters, and administration functions.
	 */
	function __construct() {
		
		// Load plugin text domain
		add_action( 'init', array( $this, 'plugin_textdomain' ) );

		// Register admin styles and scripts
		add_action( 'admin_print_styles', array( $this, 'register_admin_styles' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_admin_scripts' ) );
	
		// Register site styles and scripts
		add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_styles' ) );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_scripts' ) );
	
		// Register hooks that are fired when the plugin is activated, deactivated, and uninstalled, respectively.
		register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
		register_uninstall_hook( __FILE__, array( $this, 'uninstall' ) );
		
	    /*
	     * TODO:
	     * Define the custom functionality for your plugin. The first parameter of the
	     * add_action/add_filter calls are the hooks into which your code should fire.
	     *
	     * The second parameter is the function name located within this class. See the stubs
	     * later in the file.
	     *
	     * For more information: 
	     * http://codex.wordpress.org/Plugin_API#Hooks.2C_Actions_and_Filters
	     */

		//Adds Custom Post Type featured image for the edit screen
		add_image_size('featured_preview', 100, 75, true);

	    add_action( 'after_setup_theme', array( $this, 'custom_post_type' ) );
	    add_filter( 'TODO', array( $this, 'filter_method_name' ) );

		add_filter('manage_posts_columns', array($this, 'columns_head') );  
		add_action('manage_posts_custom_column', array($this, 'columns_content'), 10, 2);  

	} // end constructor
	
	/**
	 * Fired when the plugin is activated.
	 *
	 * @param	boolean	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog 
	 */
	public function activate( $network_wide ) {
		// TODO:	Define activation functionality here
	} // end activate
	
	/**
	 * Fired when the plugin is deactivated.
	 *
	 * @param	boolean	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog 
	 */
	public function deactivate( $network_wide ) {
		// TODO:	Define deactivation functionality here		
	} // end deactivate
	
	/**
	 * Fired when the plugin is uninstalled.
	 *
	 * @param	boolean	$network_wide	True if WPMU superadmin uses "Network Activate" action, false if WPMU is disabled or plugin is activated on an individual blog 
	 */
	public function uninstall( $network_wide ) {
		// TODO:	Define uninstall functionality here		
	} // end uninstall

	/**
	 * Loads the plugin text domain for translation
	 */
	public function plugin_textdomain() {
	
		// TODO: replace "better_calendar-locale" with a unique value for your plugin
		$domain = 'better_calendar';
		$locale = apply_filters( 'plugin_locale', get_locale(), $domain );
        load_textdomain( $domain, WP_LANG_DIR.'/'.$domain.'/'.$domain.'-'.$locale.'.mo' );
        load_plugin_textdomain( $domain, FALSE, dirname( plugin_basename( __FILE__ ) ) . '/lang/' );

	} // end plugin_textdomain

	/**
	 * Registers and enqueues admin-specific styles.
	 */
	public function register_admin_styles() {
	
		// TODO:	Change 'better_calendar' to the name of your plugin
		wp_enqueue_style( 'better_calendar-admin-styles', plugins_url( 'better_calendar/css/admin.css' ) );
	
	} // end register_admin_styles

	/**
	 * Registers and enqueues admin-specific JavaScript.
	 */	
	public function register_admin_scripts() {
	
		// TODO:	Change 'better_calendar' to the name of your plugin
		wp_enqueue_script( 'better_calendar-admin-script', plugins_url( 'better_calendar/js/admin.js' ) );
		wp_enqueue_script('backbone', $src = false, $deps = array('underscore'), $ver = false, $in_footer = false) ;
	
	} // end register_admin_scripts
	
	/**
	 * Registers and enqueues plugin-specific styles.
	 */
	public function register_plugin_styles() {
	
		// TODO:	Change 'better_calendar' to the name of your plugin
		wp_enqueue_style( 'better_calendar-plugin-styles', plugins_url( 'better_calendar/css/display.css' ) );
	
	} // end register_plugin_styles
	
	/**
	 * Registers and enqueues plugin-specific scripts.
	 */
	public function register_plugin_scripts() {
	
		// TODO:	Change 'better_calendar' to the name of your plugin
		wp_enqueue_script( 'better_calendar-plugin-script', plugins_url( 'better_calendar/js/display.js' ) );
	
	} // end register_plugin_scripts
	
	/*--------------------------------------------*
	 * Core Functions
	 *---------------------------------------------*/
	
	/**
 	 * NOTE:  Actions are points in the execution of a page or process
	 *        lifecycle that WordPress fires.
	 *
	 *		  WordPress Actions: http://codex.wordpress.org/Plugin_API#Actions
	 *		  Action Reference:  http://codex.wordpress.org/Plugin_API/Action_Reference
	 *
	 */
	function custom_post_type() {
    	
    	//Let's call and create the custom post type
    	$event = new Custom_Post_Type( 'Event', array('show_in_menu' => true, 'menu_position' => 25, 'has_archive' => true, 'taxonomies' => array('events')) );
    	$event->add_meta_box(
    		'Event Details',
    		array(
    			'Start Date'	=>	'date',
    			'Starts at'		=>	'time',
    			'End Date'		=>	'date',
    			'Ends at'		=>	'time',
    			'Where'			=>	'text',
    			'RSVP'			=>	'checkbox',
    			'Entry Fee'		=>	'number'
    		)
    	) ;

	} // end custom_post_type
	
	/**
	 * NOTE:  Filters are points of execution in which WordPress modifies data
	 *        before saving it or sending it to the browser.
	 *
	 *		  WordPress Filters: http://codex.wordpress.org/Plugin_API#Filters
	 *		  Filter Reference:  http://codex.wordpress.org/Plugin_API/Filter_Reference
	 *
	 */
	function filter_method_name() {
	    // TODO:	Define your filter method here
	} // end filter_method_name

	// GET FEATURED IMAGE  
	function get_featured_image($post_ID) {  
	    $post_thumbnail_id = get_post_thumbnail_id($post_ID);  
	    if ($post_thumbnail_id) {  
	        $post_thumbnail_img = wp_get_attachment_image_src($post_thumbnail_id, 'featured_preview');  
	        return $post_thumbnail_img[0];  
	    }  
	}

	// ADD NEW COLUMN  
	function columns_head($defaults) {  
	    $defaults['featured_image'] = 'Featured Image';  
	    return $defaults;  
	}  
	  
	// SHOW THE FEATURED IMAGE  
	function columns_content($column_name, $post_ID) {  
	    if ($column_name == 'featured_image') {  
	        $post_featured_image = $this->get_featured_image($post_ID);  
	        if ($post_featured_image) {  
	            echo '<img src="' . $post_featured_image . '" />';  
	        }  
	    }  
	}
  
} // end class

// TODO:	Update the instantiation call of your plugin to the name given at the class definition
$betterCalendar = new BetterCalendar();
