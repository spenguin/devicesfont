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
        $terms  = get_the_terms( $id, 'variant' );
        $o[$id] = [
            'title' => get_the_title(),
            'slug'  => get_post_field( 'post_name', $id ),
            'sampleFont'    => getSampleFont( $id ),
            'standard'      => getFamilyStandard( $id )
        ];
    endwhile; endif;
    return $o;
}

function getSampleFont( $parentId )
{ 
    $search = "%woff%";
    $args = [
        'post_type'         => 'font',
        'post_parent'       => $parentId,
        'posts_per_page'    => 1,
    //    's'                 => $search
    ]; 
    $query   = new WP_Query( $args ); 
    if( $query->have_posts() ): while( $query->have_posts() ): $query->the_post();
        $o  = get_the_title();
        if( FALSE !== strpos( $o, 'woff' ) )
        {
            return $o;
        } 
    endwhile; endif; 
    return $o;   
}

/**
 * Get the standard variant value for the parent id
 */
function getFamilyStandard( $id )
{
    $terms  = get_the_terms( $id, 'variant' );
    $standards  = ["superior", "premium", "deluxe"];
    foreach( $terms as $t )
    {
        if( in_array( $t->slug, $standards) ) return $t->name;
    }
    return NULL;
}

/**
 * Get the children fonts based on the Font Family slug
 * @param (string) $parentSlug
 * @return (array) children Fonts
 */
function getParent( $parentSlug )
{
    $args   = [
        'name'      => $parentSlug,
        'post_type' => 'font',
        'numberposts'   => 1
    ];
    $o  = reset( get_posts( $args ) );
    return $o;
} 

function getChildFonts( $parentId )
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
        $title  = reset( explode( '.', get_the_title() ) );
        if( array_key_exists( $title, $o ) ) continue;
        $o[$title]    = [
            'id'    => $id,
            'slug'  => get_post_field( 'post_name', $id ),
        ];
    endwhile; endif;
    return $o;    
}