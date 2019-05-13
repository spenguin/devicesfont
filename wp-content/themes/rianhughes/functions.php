<?php
/**
 *  Theme functions and definitions
 *
 *  @package Core
 *  @since 0.1.0
 */
namespace Core;

// Useful global constants
define( 'CORE_URL', get_stylesheet_directory_uri() );
define( 'CORE_TEMPLATE_URL', get_template_directory_uri() );
define( 'CORE_PATH', get_template_directory() . '/' ); 
define( 'CORE_INC', CORE_PATH . 'includes/' );
define( 'CORE_VENDOR', CORE_INC . 'vendor' );
define( 'CORE_VIEW', CORE_PATH . 'view/' );
define( 'CORE_TEMPLATES', CORE_PATH . 'templates/' );
define( 'CORE_PLUGINS_PATH', plugins_url() );

define( 'SITE_STATUS', 'dev' ); // Switch to  test to test or live when going live

if( isset( $_GET['noBar'] ) )
{
	?>
		<style>
			#wpadminbar {
                display: none;
                margin-top: 0;
			}
		</style>
	<?php
}

require_once CORE_INC . 'custom-posts.php';
require_once CORE_INC . 'images.php';