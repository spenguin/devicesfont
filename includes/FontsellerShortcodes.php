<?php
/**
 * Create the Shortcodes
 */

createShortcodes();

function createShortcodes()
{
   // add_shortcode( 'fontseller-upload', 'createFontsellerUpload' );
   add_shortcode( 'fontseller-display', 'displayFonts' );
}

function createFontsellerUpload()
{
    
}

function displayFonts()
{
    if( isset( $_GET['fontFamily'] ) )
    {
        // Get child fonts
        $parent = getParent( $_GET['fontFamily'] );
        $fonts  = getChildFonts( $parent->ID );
        include_once( FS_TEMPLATES . 'partials/display-children.php' );

    }
    else
    {   
        // Get Parent fonts
        $fonts   = getFonts();
        include_once( FS_TEMPLATES . 'display-parents.php' );
    }


}

/**
 * Get Fonts by Parent Id
 */
function getFonts( $parentId = 0 )
{
    $args = [
        'post_type'       => 'font',
        'post_parent'     => $parentId,
        'posts_per_page'  => -1
    ];

    $query   = new WP_Query( $args ); 
    $o       = [];
    if( $query->have_posts() ): while( $query->have_posts() ): $query->the_post(); 
        $id     = get_the_ID();  
        //$terms  = get_the_terms( $id, 'variant' );
        $o[$id] = [
            'title' => get_the_title(),
            'slug'  => get_post_field( 'post_name', $id ),
            'repFont'   => getRepFont( $id ),
            'standard'  => '' //getSetStandard( $id )
        ];
    endwhile; endif; 
    return $o;
}

/**
 * Get the font that represents this font
 * If the font has a woff format, use that;
 * else use the representative font
 */
function getRepFont( $id )
{
    $repFontStr    = get_post_meta( $id, 'repFont', TRUE ); 
    if( !empty( $repFontStr ) )
    {
        $args   = [
            'post_type' => 'font',
            'name'      => $repFontStr,
            'numberposts'   => 1
        ];
        $posts = get_posts( $args ); 
        $repFont    = reset( $posts ); 
        $formats    = explode( ',', get_post_meta( $repFont->ID, 'formats', TRUE ) );
        if( in_array( 'woff', $formats ) )
        {
            $repFontStr = $repFont->post_title . '.woff';
        }
    }

    return $repFontStr;
}
