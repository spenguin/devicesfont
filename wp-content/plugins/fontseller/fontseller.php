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
require_once( FS_INCLUDES . 'FontsellerAdmin.php' );
require_once( FS_INCLUDES . 'FontsellerShortcodes.php' );
require_once( FS_INCLUDES . 'FontsellerPaymentGateways.php' );

// Run databases
FontsellerDatabase::run();
//FontsellerCustomPosts::run();