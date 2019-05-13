<?php
/**
 * Custom Post Types
 * cpt: fonts
 */

initialise();

function initialise()
{
    add_action( 'init', 'create_post_type' );
    add_action( 'init', 'create_custom_taxonomy' );
}

function create_post_type() {
    register_post_type( 'font',
      array(
        'labels' => array(
          'name' => __( 'Fonts' ),
          'singular_name' => __( 'Font' )
        ),
        'public' => true,
        'has_archive' => true,
      )
    );
}
function create_custom_taxonomy()
{
    $labels = array(
        'name' => _x( 'Variants', 'taxonomy general name' ),
        'singular_name' => _x( 'Variant', 'taxonomy singular name' ),
        'search_items' =>  __( 'Search Variants' ),
        'all_items' => __( 'All Variants' ),
        'parent_item' => __( 'Parent Variant' ),
        'parent_item_colon' => __( 'Parent Variant:' ),
        'edit_item' => __( 'Edit Variant' ), 
        'update_item' => __( 'Update Variant' ),
        'add_new_item' => __( 'Add New Variant' ),
        'new_item_name' => __( 'New Variant Name' ),
        'menu_name' => __( 'Variants' ),
    ); 	
    
    register_taxonomy('variant',array('font'), array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'variant' ),
    ));

    if( !term_exists( 'classification', 'variant' ) )
    {
        $term   = wp_insert_term( 'classification', 'variant' ); 
        $classes    = ['Serif', 'Sans Serif', 'Slab', 'Decorative', 'Script', 'Display', 'Slab'];
        foreach( $classes as $c )
        {
            if( !term_exists( $c, 'variant' ) )
            {
                wp_insert_term( $c, 'variant', ['parent' => $term['term_id']] );
            }
        }
    }
}