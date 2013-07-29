<?php

/**
 * Contains the basic markup for displaying posts and pages.
 *
 * The $post_type variable is available when this template part
 * has been requested by index.php.
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 *
 * @author Nate Hart (and future CIFers)
 */

if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();

		get_template_part( 'content', $post_type );
	}
} else {
	get_template_part( 'no-content', $post_type );
}
