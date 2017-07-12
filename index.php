<?php
/**
 * Plugin Name: Cubilis Fastbooker
 * Plugin URI: http://www.cubilis.com
 * Description: Cubilis Fastbooker® widget allows you to easily integrate the Cubilis Booking Engine® on your website. Using this widget, visitors are able to find available rooms based on their arrival and departure date. An active Cubilis subscription is required, please contact sales@stardekk.be if you don’t have an activate account yet.
 * Version: v.1.0.8
 * Author: Stardekk
 * Author URI: http://www.stardekk.be
 * License: GPLv2
 */
 
// Block direct requests
if ( !defined('ABSPATH') )
	die('-1');

// Load internalization
add_action( 'plugins_loaded', 'stardekk_cubilis_fastbooker_load_textdomain' );
	
// Includes
include('widget/cubilis_fastbooker_widget.php');

// Make a cubilis menu
add_action("admin_menu", "cubilisFastbookerMenu");

// Register Widget
add_action( 'widgets_init', function(){
     register_widget( 'Cubilis_Fastbooker_Widget' );
});

/**
 * Cubilis Fastbooker Menu
 * - make a menu item in the main menu of Wordpress
 */
function cubilisFastbookerMenu() {
	$appName = "Cubilis";
	$appID   = "cubilis-fastbooker-plugin";
	// Make a menu item (Cubilis Fastbooker plugin)
	add_menu_page($appName, $appName, 'manage_options', $appID, 'cubilis_fastbooker_settings',plugins_url( 'stardekk-cubilis-fastbooker/images/cubilis_klein.png'));
}

/**
 * Cubilis Fastbooker Settings
 * - includes the settings page
 */
function cubilis_fastbooker_settings() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( _e( 'You do not have sufficient permissions to view this page.', 'text_domain' ) );
	}
	
	include('admin/cubilis_fastbooker_settings_admin.php');
}

/**
 * Load plugin textdomain.
 */
function stardekk_cubilis_fastbooker_load_textdomain() {
	load_plugin_textdomain( 'text_domain', false, plugin_basename( dirname( __FILE__ ) ) . '/languages' ); 
}
 ?>