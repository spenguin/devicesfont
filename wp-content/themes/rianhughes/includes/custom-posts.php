<?php

namespace CustomPosts;

use WP_Query;


\CustomPosts\register();

function register()
{
	add_action( 'init', '\CustomPosts\custom_post_type', 0 );
	add_action( 'init', '\CustomPosts\custom_taxonomy_type', 0 );
	
}


function custom_post_type() {
    
	
	// Set UI labels for Custom Post Type Testimonials
	$labels = array(
		'name'                => _x( 'Work', 'Post Type General Name', 'rianhughes' ),
		'singular_name'       => _x( 'Work', 'Post Type Singular Name', 'rianhughes' ),
		'menu_name'           => __( 'Work', 'rianhughes' ),
		'parent_item_colon'   => __( 'Parent Work', 'rianhughes' ),
		'all_items'           => __( 'All Work', 'rianhughes' ),
		'view_item'           => __( 'View Work', 'rianhughes' ),
		'add_new_item'        => __( 'Add New Work', 'rianhughes' ),
		'add_new'             => __( 'Add New', 'rianhughes' ),
		'edit_item'           => __( 'Edit Work', 'rianhughes' ),
		'update_item'         => __( 'Update Work', 'rianhughes' ),
		'search_items'        => __( 'Search Work', 'rianhughes' ),
		'not_found'           => __( 'Not Found', 'rianhughes' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'rianhughes' ),
	);
	
// Set other options for Custom Post Type
	
	$args = array(
		'label'               => __( 'work', 'rianhughes' ),
		'description'         => __( 'Work listings', 'rianhughes' ),
		'labels'              => $labels,
		// Features this CPT supports in Post Editor
		'supports'            => array( 'title', 'editor', 'thumbnail', 'excerpt' ),
		'rewrite' => array('slug' => 'blog','with_front' => false),
		/* A hierarchical CPT is like Pages and can have
		* Parent and child items. A non-hierarchical CPT
		* is like Posts.
		*/	
		'hierarchical'        => true,
		'public'              => true,
		'show_ui'             => true,
		'show_in_menu'        => true,
		'show_in_nav_menus'   => true,
		'show_in_admin_bar'   => true,
		'menu_position'       => 25,
		'can_export'          => true,
		'has_archive'         => true,
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'page',
		'taxonomies' 		  => array( 'post_tag' ),
	);
	// Registering Custom Post Type Blogs
	register_post_type( 'work', $args );	
}

function custom_taxonomy_type()
{
    register_taxonomy(
        'work_type',
        'work',
        array(
            'labels'    => array(
                'name'  => 'Work Type',
                'add_new_item'  => 'Add New Work Type',
                'new_item_name' => 'New Work Type'
            ),
            'show_ui'   => TRUE,
            'show_tagcloud' => FALSE,
            'hierarchical'  => TRUE
        )
	);
	\CustomPosts\init_tax_terms();
}


function init_tax_terms()
{
	$terms	= [
		'Logos',
		'Illustration',
		'Design',
		'Photography'
	];
	foreach( $terms as $term )
	{
		set_tax_term( $term, "work_type" );
	}
}
/**
 * Set terms for taxonomy
 */
function set_tax_term( $value = NULL, $taxonomy = "category" )
{
	$term = wp_insert_term(
		$value,
		$taxonomy,
		array(
			'slug' => strtolower( str_ireplace( ' ', '-', $value ) )
		)
	);
}