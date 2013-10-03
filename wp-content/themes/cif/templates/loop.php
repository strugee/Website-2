<?php

/**
 * Loops through all queried posts, utilizing the appropriate template for
 * that post type.
 *
 * The $post_type variable is available when this template part
 * has been requested by index.php.
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 */

if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();

		get_template_part( 'templates/content', $post_type );
	}
} else {
	get_template_part( 'templates/no-content', $post_type );
}
