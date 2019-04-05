<?php
/*
Plugin Name: Fontseller
Plugin URI:  http://fontseller.com
Description: Create interactive webfont previews via shortcodes. Create and edit previews from the &para; Fontseller sidebar menu or click "Settings" on the left.
				Based on Fontsampler (http://fontsampler.johannesneumeier.com) by Underscore (https://underscoretype.com/)
Version:     0.0.1
Author:      John Anderson/Soaring Penguin
Author URI:  https://soaringpenguin.com
Copyright:   Copyright 2019 Soaring Penguin Lrd
Text Domain: fontseller
*/
defined( 'ABSPATH' ) or die( 'Access denied.' );


// PHP version check first and foremost
function displayPhpError() 
{
	echo '<section id="fontseller-admin">';
	echo '<div class="notice error">Your server is running PHP version ' . PHP_VERSION
	     . ', <br>Fontseller requires at least PHP version 5.6.33 or higher to run.<br><br>'
	     . 'The <a href="https://wordpress.org/about/requirements/">recommended PHP version '
	     . 'for Wordpress itself is 7</a> or greater.<br><br>'
	     . 'While legacy Wordpress support extends to 5.2.4, <strong>Fontseller requires a minimum '
	     . 'of PHP 5.6.33.</strong> Please be in touch with your webserver provider about upgrading or enabling '
	     . 'a more modern version of PHP.';
	echo '</section>';
	exit();
}

function addMenu() 
{
	add_menu_page( 'Fontseller plugin page', 'Fontseller', 'manage_options', 'fontseller', 'displayPhpError', 'dashicons-editor-paragraph' );
	wp_enqueue_style( 'fontseller_admin_css', plugin_dir_url( __FILE__ ) . '/admin/css/fontseller-admin.css', false, '1.0.0' );
}

if ( version_compare( PHP_VERSION, "5.6.33" ) < 0 ) {
	add_action( 'admin_menu', 'addMenu' );
} else {
    global $wpdb;
	// PHP version is good, let's go all bells and whistles...
	require_once( 'FontsellerPlugin.php' );

	// Convenience subclasses instantiated within the FontsellerPlugin class
	require_once( 'FontsellerDatabase.php' );
	//require_once( 'FontsellerFormhandler.php' );
	//require_once( 'FontsellerLayout.php' );
	//require_once( 'FontsellerHelpers.php' );
	//require_once( 'FontsellerPagination.php' );
	//require_once( 'FontsellerMessages.php' );
	//require_once( 'FontsellerNotifications.php' );
	//require_once( 'FontsellerOrders.php' );
	//require_once( 'FontsellerPaymentGateways.php' );

	/*require_once( 'vendor/oyejorge/less.php/lessc.inc.php' );
	require_once( 'vendor/autoload.php' );*/

	// hook all plugin classes init to when Wordpress is ready
	$loader 	 = new Twig_Loader_Filesystem( __DIR__ . '/includes' );
	$twig   	 = new Twig_Environment( $loader );
	$fontseller  = new FontsellerPlugin( $wpdb, $twig );

	// load translations, then kick off actual Fontseller setup and hooks
	add_action( 'plugins_loaded', array( $fontseller, 'fontseller_load_text_domain' ) );
	add_action( 'init', "fontseller_init" );
	register_activation_hook( __FILE__, array( $fontseller, 'fontseller_activate' ) );
}