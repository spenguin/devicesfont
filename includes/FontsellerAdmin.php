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
        $classes    = getTerms( 'classification' ); 
        $standards  = getTerms( 'standard' );
        
        // Get child fonts
        $parent     = getParent( $_GET['fontFamily'] ); //var_dump( $parent );
        $fonts      = getChildFonts( $parent->ID ); //var_dump( $fonts );
        if( isset( $_POST['submit'] ) )
        {
            $setTitle   = filter_var( $_POST['setTitle'], FILTER_SANITIZE_STRING );
            $setStandard= $_POST['setStandard'];
            $setRepFont = $_POST['setRepFont']; 
            $setRepFontSize = filter_var( $_POST['setRepFontSize'], FILTER_SANITIZE_STRING ); 
            //$setFontFormatOffered   = $_POST['setFontFormatOffered'];
            $setFontClass   = $_POST['setFontClass'];

            // Write the results to the db
            if( $parent->post_title !== $setTitle )
            {
                wp_update_post( [
                    'ID'            => $parent->ID,
                    'post_title'    => $setTitle
                ] );
            }
            wp_set_post_terms( $parent->ID, [$setStandard], 'standard' );
            add_post_meta( $parent->ID, 'repFont', $setRepFont );
            add_post_meta( $parent->ID, 'repFontSize', $setRepFontSize );
            add_post_meta( $parent->ID, 'fontFormatOffered', $setRepFontSize );
            wp_set_post_terms( $parent->ID, $setFontClass, 'classification' );
        }

        $standard   = reset( getSetTerms( $parent->ID, 'standard' ) );
        $prices     = getPricing( $standard ); 
        $fontIds    = extractFontIds( $fonts, $prices[strtolower( $standard )] );
        $repFont    = reset( explode( '.', getRepFont( $parent->ID ) ) ); 
        $repFontSize= get_post_meta( $parent->ID, 'repFontSize', TRUE );
        $fontClasses= getSetTerms( $parent->ID, 'classification' );
        //$fontFormatOffered  = get_post_meta( $parent->ID, 'fontFormatOffered', TRUE );
        include_once( FS_TEMPLATES . 'partials/admin/displayFontSet.php' );

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
    $classes    = getTerms( 'classification' ); 
    $standards  = getTerms( 'standard' );
    if( isset( $_POST['submit'] ) )
    {
        $fontFormatOffered  = $_POST['setFontFormatOffered'];   // [FIX]Should sanitise
        $repFontSize        = $_POST['setRepFontSize'];
        $showFontSets       = $_POST['showFontSets'];
        $status             = isset( $_POST['setStatus'] ) ? $_POST['setStatus'] : 0;
        $testAccount        = isset( $_POST['setTestAccount'] ) ? $_POST['setTestAccount'] : '';
        $liveAccount        = isset( $_POST['setLiveAccount'] ) ? $_POST['setLiveAccount'] : '';

        update_option( 'fontFormatOffered', $fontFormatOffered );
        update_option( 'repFontSize', $repFontSize );
        update_option( 'showFontSets', $showFontSets );
        update_option( 'paypalStatus', $status );
        update_option( 'testAccount', $testAccount );
        update_option( 'liveAccount', $liveAccount );
    }
    $fontFormatOffered  = get_option( 'fontFormatOffered' );
    $repFontSize        = get_option( 'repFontSize' );
    $showFontSets       = get_option( 'showFontSets' );
    $prices             = getPricing( NULL );
    $status             = get_option( 'paypalStatus', 0 ); 
    $testAccount        = get_option( 'testAccount' );
    $liveAccount        = get_option( 'liveAccount' );
    
    include_once( FS_TEMPLATES . '/partials/admin/displayFontSettings.php' );
  
}

function fsUploadPage()
{
    $fontsellerUpload   = new \FontsellerUpload;
    $fontsellerUpload->display();
    //\FontsellerUpload::display();
}