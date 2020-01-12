<?php
/**
 * Create the Shortcodes
 */

createShortcodes();

function createShortcodes()
{
   // add_shortcode( 'fontseller-upload', 'createFontsellerUpload' );
   add_shortcode( 'fs-display', 'displayFonts' );
   add_shortcode( 'fs-cart', 'displayCart' );
   add_shortcode( 'fs-checkout', 'displayCheckout' );
   add_shortcode( 'fs-reset', 'resetSession' );
   add_shortcode( 'fs-confirmed', 'confirmOrder' );
   add_shortcode( 'fs-order', 'downloadOrder' );
}

function resetSession()
{
    unset( $_SESSION['cart'] );
}

function createFontsellerUpload()
{
    
}

function displayFonts()
{
    if( isset( $_GET['fontFamily'] ) )
    {
        //do_action( 'fontseller_display_fontset' );
        
        // Get child fonts
        $parent     = getParent( $_GET['fontFamily'] );
        $standard   = reset( getSetTerms( $parent->ID, 'standard' ) );
        $prices     = getPricing( $standard ); 
        $fonts      = getChildFonts( $parent->ID ); // use getFonts?
        $fontIds    = extractFontIds( $fonts, $prices[strtolower( $standard )] );
        $repFont    = getRepFont( $parent->ID );
        include_once( FS_TEMPLATES . 'partials/display-children.php' );

    }
    else
    {   
        do_action( 'fontseller_display_fontsets' );
        
        /*$pageNo = isset( $_REQUEST['pageNo'] ) ? filter_var( $_REQUEST['pageNo'], FILTER_VALIDATE_INT ) : 1;
        $pages  = getFontPages();
        // Get Parent fonts
        $fonts   = getFonts( 0, $pageNo );
        ob_start();
            include_once( FS_TEMPLATES . 'display-parents.php' );
        return ob_get_clean();*/
    }


}

/**
 * Get Fonts by Parent Id
 */
function getFonts( $parentId = 0, $pageNo = 1 )
{
    $perPage    = get_option( 'showFontSets', 20 );
    if( empty( $perPage ) ) $perPage    = 20;
    
    $args = [
        'post_type'         => 'font',
        'post_parent'       => $parentId,
        'posts_per_page'    => $perPage, // [FIX]
        'offset'            => $perPage * ($pageNo - 1 ),
        'orderby'           => 'title',
        'order'             => 'ASC'
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
/*
No longer needed */
function getRepFont( $id )
{   
    $repFontStr    = get_post_meta( $id, 'repFont', TRUE ); //var_dump( $repFontStr );
    if( is_numeric( $repFontStr ) )
    {
        $repFont    = get_post( $repFontStr ); //var_dump( $repFont );
        return $repFont->post_title . ".otf"; //[FIX - need to determine what font format]
    }

    if( is_string( $repFontStr ) )
    {
        $the_slug = reset( explode( '.', $repFontStr ) );
        $args = array(
        'name'        => $the_slug,
        'post_type'   => 'font',
        'post_status' => 'publish',
        'numberposts' => 1
        );
        $my_posts = get_posts($args);
        return $my_posts[0]->post_title . '.otf'; //[FIX - need to determine what font format]
    }


    //if( !is_numeric( $repFontStr ) ) return $repFontStr . ".otf"; //[FIX - need to determine what font format]
    $repFont    = get_post( $repFontStr ); //var_dump( $repFont );
    return $repFont->post_title . ".otf"; //[FIX - need to determine what font format]
    
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
        if( in_array( 'otf', $formats ) )
        {
            $repFontStr = $repFont->post_title . '.otf';
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

function extractFontIds( $fonts, $price )
{   
    $o  = [];
    foreach( $fonts as $f )
    {
        $o[]    = $f['id'] . ':' . $price;
    }
    return $o;
}

function displayCart()
{
    if( isset( $_POST['addToCart'] ) )  // Adding an item to the shopping cart
    {
        $fontIds = stringToArray( $_POST['fontId'] ); 
        if( !isset( $_SESSION['cart'] ) ) $_SESSION ['cart']    = [];
        $_SESSION['cart']   = $_SESSION['cart'] + $fontIds;
    }
    if( isset( $_REQUEST['remove'] ) )
    {
        unset( $_SESSION['cart'][$_REQUEST['remove']] );
        //removeFont( $_REQUEST['remove'] );
    }
    $fonts  = organiseFonts( $_SESSION['cart'] );
    
    include_once( FS_TEMPLATES . 'partials/display/cart.php' );
}

function displayCheckout()
{
    // Need the Orders file to generate things
    include_once( 'FontsellerOrders.class.php' );
    $Order  = new FontsellerOrders; 
    $order  = organiseFonts( $_SESSION['cart'] ); 
    $Order->writeOrder( $order ); 

    // Also need to know if this is Live or Test
    $payPalStatus   = get_option( 'paypalStatus', TRUE ); 
    if( 1 == $payPalStatus )
    {
        $ppUrl      = "https://www.paypal.com/cgi-bin/webscr";
        $account    = get_option( 'liveAccount', TRUE );
    }
    else
    {
        $ppUrl      = "https://www.sandbox.paypal.com/cgi-bin/webscr";
        $account    = get_option( 'testAccount', TRUE );        
    }
    
    include_once( FS_TEMPLATES . 'partials/display/checkout.php' );
}

/**
 * Writes the order to the db, generates an unique key, sends the email and presents the download link
 */
function confirmOrder()
{   
    $orderId    = $_POST['custom'];
    $verifySign = $_POST['verify_sign'];
    $email      = $_POST['payer_email'];
    include_once( 'FontsellerOrders.class.php' );
    $customerId = 0;    // [FIX] Need to do something about customers
    $Order  = new FontsellerOrders;     
    $key        = $Order->generateOrder( $orderId, $verifySign, $customerId ); 
    $Order->emailOrder( $email, $key );

    include_once( FS_TEMPLATES . 'partials/display/order.php' );
}

function organiseFonts( $cart )
{   
    $o  = [];
    //$cart   = stringToArray( $cart );
    foreach( $cart as $k => $c )
    {
        $font   = get_post( $k ); //var_dump( $font );
        $o[$k]  = [
            'name'  => $font->post_title,
            'price' => $c
        ];
    } //var_dump( $o );
    return $o;
}

function removeFont( $fontId )
{
    $cart   = explode( ',', $_SESSION['cart'] );
    $o      = [];
    foreach( $cart as $c )
    {
        $c  = explode( ':', $c );
        if( $fontId == $c[0] ) continue;
        $o[]    = join( ':', $c );
    }
    $_SESSION['cart']   = join( ',', $o );
}

function arrayToString( $array )
{
    $o  = [];
    foreach( $array as $k => $v )
    {
        $o[]    = $k . ':' . $v;
    }
    return $o;
}

function stringToArray( $str )
{   
    $str    = explode( ',', $str );
    $o  = [];
    foreach( $str as $s )
    {
        $s  = explode( ':', $s );
        $o[$s[0]]   = $s[1];
    } 
    return $o;
}

