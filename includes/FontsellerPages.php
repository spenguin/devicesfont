<?php
/**
 * Create the pages required for the plugin to work
 */


add_action( 'wp_loaded', 'create_pages' );

function create_pages()
{
    $check_page_exist = get_page_by_title( 'Cart', 'OBJECT', 'page' );
    if( empty( $check_page_exist ) )
    {
        $post_details = [
            'post_title'    => 'Cart',
            'post_content'  => '[fs-cart]',
            'post_status'   => 'publish',
            'post_author'   => 1,
            'post_type'     => 'page'
        ];
        
        wp_insert_post( $post_details );
    }
    $check_page_exist = get_page_by_title( 'Checkout', 'OBJECT', 'page' );
    if( empty( $check_page_exist ) )
    {
        $post_details = [
            'post_title'    => 'Checkout',
            'post_content'  => '[fs-checkout]',
            'post_status'   => 'publish',
            'post_author'   => 1,
            'post_type'     => 'page'
        ];
        wp_insert_post( $post_details ); 
    }   

}

