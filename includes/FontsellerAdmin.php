<?php
/**
 * Admin pages
 */
namespace Fontseller\Admin;

\Fontseller\Admin\initialise();

function initialise()
{
    add_action( 'admin_menu' , '\Fontseller\Admin\addFontMenu' ); 
    add_action( 'admin_menu', '\Fontseller\Admin\addFontsellerMenu');
}


function addFontMenu() {
    add_submenu_page( 'edit.php?post_type=fonts', 'Font Admin', 'Custom Settings', 'edit_posts', basename(__FILE__), '\Fontseller\Admin\fontMenuFunction');
}

function fontMenuFunction()
{
    //return NULL;
}

function addFontsellerMenu()
{
   add_menu_page( 'Fontsellers', 'Fontsellers', 'manage_options', 'fontseller-options', '\Fontseller\Admin\fsDisplay' );
   add_submenu_page( 'fontseller-options', 'Fontsellers Settings', 'Fontsellers Settings', 'manage_options', 'fs-op-settings', '\Fontseller\Admin\fsSettingsPage' );
   add_submenu_page( 'fontseller-options', 'Fontsellers Upload', 'Fontsellers Upload', 'manage_options', 'fs-op-upload', '\Fontseller\Admin\fsUploadPage' );
}

function fsDisplay()
{
        
    include_once( FS_TEMPLATES . '/partials/admin/displayFontFamilies.php' );
}

function fsSettingsPage()
{
    echo '<div class="wrap"><div id="icon-options-general" class="icon32"><br></div>
    <h2>Fontseller Settings</h2></div>';    
}

function fsUploadPage()
{
    $fontsellerUpload   = new \FontsellerUpload;
    $fontsellerUpload->display();
    //\FontsellerUpload::display();
}