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
   add_menu_page( 'Fontsellers', 'Fontseller Fonts', 'manage_options', 'fontseller-options', '\Fontseller\Admin\fsDisplay' );
   add_submenu_page( 'fontseller-options', 'Fontsellers Settings', 'Fontsellers Settings', 'manage_options', 'fs-op-settings', '\Fontseller\Admin\fsSettingsPage' );
   add_submenu_page( 'fontseller-options', 'Fontsellers Upload', 'Fontsellers Upload', 'manage_options', 'fs-op-upload', '\Fontseller\Admin\fsUploadPage' );
}

function fsDisplay()
{
    if( isset( $_GET['fontFamily'] ) )
    {
        // Get child fonts
        $parent     = getParent( $_GET['fontFamily'] );
        $standard   = reset( getSetTerms( $parent->ID, 'standard' ) );
        $prices     = getPricing( $standard ); 
        $fonts      = getChildFonts( $parent->ID ); // use getFonts?
        $fontIds    = extractFontIds( $fonts, $prices[strtolower( $standard )] );
        include_once( FS_TEMPLATES . 'partials/display-children.php' );

    }
    else
    {   
        $pageNo = isset( $_REQUEST['pageNo'] ) ? filter_var( $_REQUEST['pageNo'], FILTER_VALIDATE_INT ) : 1;
        $pages  = getFontPages();
        // Get Parent fonts
        $fonts   = getFonts( 0, $pageNo );
        include_once( FS_TEMPLATES . '/partials/admin/displayFontFamilies.php' );
    }
    
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