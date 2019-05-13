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
    return NULL;
}

function addFontsellerMenu()
{
   add_menu_page( 'Fontsellers', 'Fontsellers', 'manage_options', 'fontseller-options', '\Fontseller\Admin\fsPage' );
   add_submenu_page( 'fontseller-options', 'Fontsellers Settings', 'Fontsellers Settings', 'manage_options', 'fs-op-settings', '\Fontseller\Admin\fsSettingsPage' );
   add_submenu_page( 'fontseller-options', 'Fontsellers Upload', 'Fontsellers Upload', 'manage_options', 'fs-op-upload', '\Fontseller\Admin\fsUploadPage' );
}

function fsPage()
{
    echo '<div class="wrap"><div id="icon-options-general" class="icon32"><br></div>
    <h2>Fontseller</h2></div>';
}

function fsSettingsPage()
{
    echo '<div class="wrap"><div id="icon-options-general" class="icon32"><br></div>
    <h2>Fontseller Settings</h2></div>';    
}

/**
 * Controller for Fontseller Upload page in Admin
 */

function fsUploadPage()
{
    $allowableExtensions    = \Fontseller\Admin\setAllowableExtensions();

    if( !empty( $_FILES['font-files'] ) )        // There are files being uploaded
    {   
        // Upload files to Fontseller upload directory
        \Fontseller\Admin\uploadFonts( $allowableExtensions );
    }
    elseif( isset( $_POST['subFontName'] ) )
    {
        if( empty( $_POST['family'] ) && empty( $_POST['familyId'] ) )
        {
            $error[]    = "If a previous Font Family name has not been selected, a Font Family name is must be provided";
        }
        else
        {
            if( !empty( $_POST['familyId'] ) )
            {
                $familyId   = $_POST['familyId']; // Need to filter
            }
            else
            {
                $familyId   = wp_insert_post( ['post_title'=>$_POST['family'], 'post_type'=>'font', 'post_status' => 'publish'] ); // Need to filter
                \Fontseller\Admin\setFamilyClassifications( $familyId );
            }
        }
        \Fontseller\Admin\writeFonts( $familyId );

    }
    elseif( isset( $_POST['subFonts'] ) )
    {
        $parentId   = $_POST['parentId'];
        foreach( $_POST['fileName'] as $k => $filename )
        {   
            // If fileName already exists, we're just overwriting the file
            
            $args   = [
                'post_type' => 'font',
                'post_title'    => $filename,
                'post_status'    => 'publish',
                'post_parent'   => $parentId
            ]; 
            $postId = wp_insert_post( $args );
            
            // Determine Category of font
            $category   = \Fontseller\Admin\getFontCategory( $filename );
            \Fontseller\Admin\writeFontCategory( $category, $postId );

            // Move font file to recorded/
            rename( FS_UPLOAD . 'upload/'. $filename, FS_UPLOAD . 'recorded/' . $filename );
        }
    }
    elseif( count( scandir( FS_UPLOAD . 'upload/' ) ) > 2 )        // There are files in upload needing confirmed
    {   
        \Fontseller\Admin\setFontFamily();
    }
    else
    {
        include_once( FS_TEMPLATES . '/partials/admin/uploadFontFiles.php' ); 
    }
}


function setFontFamily()
{
    // Get files from directory
    $files  = scandir( FS_UPLOAD . 'upload/' ); 
    $files  = array_diff( $files, ['.', '..'] );
    $guess  = \Fontseller\Admin\guessFamilyName( reset( $files ) );
    $exists     = \Fontseller\Admin\checkFamilyName( $guess );
    $classes    = \Fontseller\Admin\getClassifications(); 

    include_once( FS_TEMPLATES . '/partials/admin/fontName.php' );
}

/**
 * Guess the font title. (Needs to be improved.)
 * @param (string) font path
 * @param (string) font file name
 * @return (string) guessed name
 */
function guessFamilyName( $file )
{
    // Get meta data from file
    $title  = explode( '-', $file );
    return reset( $title );
}

/**
 * Upload Font files
 */
function uploadFonts( $allowableExtensions )
{
    $fontFiles  = $_FILES['font-files']; //var_dump( $_FILES['font-files'] );
    $errorStr   = '';
    foreach( $fontFiles['name'] as $key => $fontFile )
    {    
        $errors = [];
        // Check for errors
        if( !empty( $fontFiles['errors'][$key] ) )
        {
            $errors[]   = "File " . $fontFile  . "has error";
        }

        // Check if empty
        if( 0 == $fontFiles['size'][$key] )
        {
//            $errors[]   = "File " . $fontFile  . "is empty";
        }
        
        // Test extension
        if( !in_array( $fontFiles['type'][$key], $allowableExtensions ) )
        {
            $errors[]   = "File " . $fontFile  . "may be the wrong format";
        }
        if( empty( count( $errors) ) )
        {   
            // Extract the information
            if( 'application/x-zip-compressed' == $fontFiles['type'][$key] )
            {   
                $zip    = new \ZipArchive; 
                $res    = $zip->open( $fontFiles['tmp_name'][$key] ); // Move the file
                if( $res === TRUE ) 
                {   
                    //echo 'ok';
                    $zip->extractTo( FS_UPLOAD . 'upload/' );
                    $zip->close();
                } else {
                    echo 'failed, code:' . $res;
                }
            }
            else
            {
                move_uploaded_file( $fontFiles['tmp_name'][$key], FS_UPLOAD . 'upload/' . $fontFiles['name'][$key] ); // Move the file
            }
        }
    }
}

/**
 * Write Font to db as custom post type "font"
 * @param (string) familyName or filename
 * @param (int) [parentId]
 * @return (int) postId
 */
function writeFontFamily( $fontName )
{
    if( empty( $parentId ) ) // We are writing the Font Family parent post
    {
        $parentId   = wp_insert_post( ['post_title'=>$fontName, 'post_type'=>'font'] );
    }
    else    // We are writing a member of the Font Family
    {
        $parent = get_post( $parentId );
    }
}

function setAllowableExtensions()
{
    return ['woff', 'woff2', 'otf', 'ttf', 'application/x-zip-compressed'];
}

/**
 * Find Font Family names that are similar to the guessed Font Family name
 * @param (string) guessedName
 * @return (array) possible matches with associated Classifications
 *
 */
function checkFamilyName( $guess )
{
    global $wpdb;

    $sql    = "SELECT * FROM {$wpdb->prefix}posts WHERE post_type = 'font' AND post_title LIKE '%" . $guess . "%'"; //var_dump( $sql );
    $res    = $wpdb->get_results( $sql );
    $o      = []; 
    if( !empty( $res ) )
    {
        $parent = get_term_by( 'slug', 'classification', 'variant' );
        
        foreach( $res as $r )
        {
            // Get corresponding classifications
            $o[$r->ID]  = [
                'title'             => $r->post_title,
                'classifications'   => getPostClassifications( $r->ID, $parent->term_id )
            ];
        }
        
    }
    return $o;
}

/**
 * Display font variants in upload director 
 * @param (int) $fontParentId
 * @param (string) upload path
 * @return (bool) TRUE/FALSE
 */
function writeFonts( $parentId )
{
    $files  = scandir( FS_UPLOAD . 'upload/' );
    $files  = array_diff( $files, ['.', '..'] );
    $o      = [];
    foreach( $files as $f )
    {  
        if( is_dir( FS_UPLOAD . 'upload/' . $f ) )
        {
            delete_files( FS_UPLOAD . 'upload/' . $f );
        }
        else
        {
            $o[]    = $f;
        }
    }
    $files  = $o;

    include_once( FS_TEMPLATES . '/partials/admin/displayFonts.php' ); 
}

/**
 * Get Font Category from filename
 * [FIX] I'm sure there are better ways than this
 */
function getFontCategory( $filename )
{
    $filestr    = reset( explode( '.', $filename ) );
    $filestr    = explode( '-', $filestr );
    return $filestr[1];
}

/**
 * Write Font Category
 * @param (string) Category
 * @param (int) FontId
 * 
 */
function writeFontCategory( $category, $postId )
{
    // Does variant exist?
    if( $term = term_exists( strtolower( $category ), 'variant' ) )
    {
        $term   = get_term( strtolower( $category ), 'variant' );
        $termId = $term['term_id'];
    }
    else
    {
        $termId = wp_insert_term( $category, 'variant' );
    }
    wp_set_object_terms( $postId, $category, 'variant' );
}

/**
 * Loop through array to delete directories
 */
function deleteDirectories( $dirs )
{
    foreach( $dirs as $d )
    {
        \Fontseller\Admin\delete_files( FS_UPLOAD . 'upload/' . $d );
    }
}

/* 
 * php delete function that deals with directories recursively
 */
function delete_files( $target ) {
    if(is_dir($target)){
        $files = glob( $target . '*', GLOB_MARK ); //GLOB_MARK adds a slash to directories returned

        foreach( $files as $file ){
            delete_files( $file );      
        }

        rmdir( $target );
    } elseif(is_file($target)) {
        unlink( $target );  
    }
}

/**
 * Get Classifications post where Parent Id is known
 * 
 */
function getPostClassifications( $postId, $parentId )
{
    $terms  = get_the_terms( $postId, 'variant' );
    $o      = [];
    foreach( $terms as $t )
    {
        if( $parentId <> $t->parent ) continue;
        $o[]    = $t;
    }
    return $o;
}

/**
 * Get all Classifications
 */
function getClassifications()
{
    $parent     = get_term_by( 'slug', 'classification', 'variant' ); 
    $children   = get_term_children( $parent->term_taxonomy_id, 'variant' );
    $o          = [];
    foreach( $children as $c )
    {
        $term   = get_term_by( 'id', $c, 'variant' );
        $o[$c]  = $term->name;
    }
    return $o;
}

/**
 * Set the Classifications for the new Font Family
 */
function setFamilyClassifications( $familyId )
{
    if( !empty( $_POST['class'] ) )
    {
        wp_set_post_terms( $familyId, $_POST['class'], 'variant' );
    }
    if( !empty( $_POST['newClass'] ) )
    {
        $parent = get_term_by( 'slug', 'classification', 'variant' );
        $term   = wp_insert_term( $_POST['newClass'], 'variant', ['parent' => $parent->term_taxonomy_id] );
        wp_set_post_terms( $familyId, $term->term_id, 'variant' );
    }
}
?> 