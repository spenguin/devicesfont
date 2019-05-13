<?php
/**
 *
 * @package WordPress
 * @subpackage Rian Hughes
 * @since Rian Hughes 1.0
 */

get_header(); ?>

<div class="container">
    <div class="row">
    
    <?php
		if ( have_posts() ) {

			// Load posts loop.
			while ( have_posts() ) {
				the_post();
				get_template_part( 'templates/partials/content' );
			}

		} else {

			// If no content, include the "No posts found" template.
			get_template_part( 'template/partials/content', 'none' );

		}
		?>
    </div>
</div>
<?php get_footer(); ?>