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

define( 'FS_CORE', dirname( __FILE__) );
define( 'FS_INCLUDES', FS_CORE . '/includes/' );
define( 'FS_TEMPLATES', FS_CORE . '/templates/' );
$path	= wp_upload_dir();
define( 'FS_UPLOAD', $path['basedir'] . '/fontseller/' );
define( 'FS_FONTS', site_url() . "/wp-content/uploads/fontseller/recorded/" );
define( 'FS_RECORDED', FS_UPLOAD . 'recorded/' );

//var_dump( dirname(__FILE__, 3 ) );


// Test minimum requirements
require_once( FS_INCLUDES . 'FontsellerPlugin.php' );

if( !FontsellerPlugin::test() )
{
	// echo error
	exit();
}
FontsellerPlugin::initialise();

require_once( FS_INCLUDES . 'FontsellerDatabase.php' );
require_once( FS_INCLUDES . 'FontsellerCustomPosts.php' );
require_once( FS_INCLUDES . 'FontsellerUpload.class.php' );
require_once( FS_INCLUDES . 'FontsellerAdmin.php' );
require_once( FS_INCLUDES . 'FontsellerShortcodes.php' );
require_once( FS_INCLUDES . 'FontsellerPricing.php' );
require_once( FS_INCLUDES . 'FontsellerPages.php' );
require_once( FS_INCLUDES . 'FontsellerOrders.class.php' );
require_once( FS_INCLUDES . 'FontsellerFunctions.php' );
//require_once( FS_INCLUDES . 'FontsellerPaymentGateways.php' );
require_once( FS_INCLUDES . 'FontsellerActions.php' );

// Run databases
FontsellerDatabase::run();
//FontsellerCustomPosts::run();

// We'll need Session variables
if (!session_id()) {
    session_start();
}