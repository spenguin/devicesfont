<?php
/**
 * Create the Shortcodes
 */

createShortcodes();

function createShortcodes()
{
   // add_shortcode( 'fontseller-upload', 'createFontsellerUpload' );
   add_shortcode( 'fontseller-display', 'displayFonts' );
   add_shortcode( 'fs-cart', 'displayCart' );
   add_shortcode( 'fs-checkout', 'displayCheckout' );
}

function createFontsellerUpload()
{
    
}

function displayFonts()
{
    if( isset( $_GET['fontFamily'] ) )
    {
        // Get child fonts
        $parent     = getParent( $_GET['fontFamily'] );
        $standard   = reset( getSetTerms( $parent->ID, 'standard' ) );
        $prices     = getPricing( $standard );
        $fonts      = getChildFonts( $parent->ID ); // use getFonts?
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
            'standard'  => reset( getSetTerms( $id, 'standard' ) )
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

/**
 * Get the parent font based on the Font Family slug
 * @param (string) $parentSlug
 * @return (array) Parent Font
 */
function getParent( $parentSlug )
{
    $args   = [
        'name'      => $parentSlug,
        'post_type' => 'font',
        'numberposts'   => 1,
        'post_parent'   => 0
    ];
    $o  = reset( get_posts( $args ) );
    return $o;
} 

/**
 * Get the children fonts based on the parent font Id
 * @param (int) $parentId
 * @return (array) ChildrenFonts
 */
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
            'description'   => get_post_field( 'post_content', $id ),
            'formats'       => get_post_meta( $id, 'formats', TRUE ) 
        ];
    endwhile; endif; wp_reset_query();
    return $o;    
}

/**
 * Get the taxonomy term(s) for the set id
 * @param (int) post id
 * @param (str) taxonomy term
 * @return (array) taxonomy terms
 */
function getSetTerms( $id, $taxonomy )
{   
    $terms  = wp_get_post_terms( $id, $taxonomy ); 
    $o      = [];
    foreach( $terms as $t )
    {
        $o[$t->term_id] = $t->name;
    }

    return $o;
}

function displayCart()
{
    echo 'Cart';
}

function displayCheckout()
{
    echo 'Checkout';
}
