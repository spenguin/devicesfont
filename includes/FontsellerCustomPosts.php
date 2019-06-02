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
    $labels = array(
        'name' => _x( 'Classifications', 'taxonomy general name' ),
        'singular_name' => _x( 'Classification', 'taxonomy singular name' ),
        'search_items' =>  __( 'Search Classifications' ),
        'all_items' => __( 'All Classifications' ),
        'parent_item' => __( 'Parent Classification' ),
        'parent_item_colon' => __( 'Parent Classification:' ),
        'edit_item' => __( 'Edit Classification' ), 
        'update_item' => __( 'Update Classification' ),
        'add_new_item' => __( 'Add New Classification' ),
        'new_item_name' => __( 'New Classification Name' ),
        'menu_name' => __( 'Classifications' ),
    ); 	
    
    register_taxonomy('classification',array('font'), array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'classification' ),
    ));
    $labels = array(
        'name' => _x( 'Standards', 'taxonomy general name' ),
        'singular_name' => _x( 'Standard', 'taxonomy singular name' ),
        'search_items' =>  __( 'Search Standards' ),
        'all_items' => __( 'All Standards' ),
        'parent_item' => __( 'Parent Standard' ),
        'parent_item_colon' => __( 'Parent Standard:' ),
        'edit_item' => __( 'Edit Standard' ), 
        'update_item' => __( 'Update Standard' ),
        'add_new_item' => __( 'Add New Standard' ),
        'new_item_name' => __( 'New Standard Name' ),
        'menu_name' => __( 'Standard' ),
    ); 	
    
    register_taxonomy('standard',array('font'), array(
        'hierarchical' => true,
        'labels' => $labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array( 'slug' => 'standard' ),
    ));        
   
}