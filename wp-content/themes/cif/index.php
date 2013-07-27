<?php

/**
 * Consolidates the contents for each post on the site.
 * The actual post markup is loaded from the loop template part.
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 *
 * @author Nate Hart (and future CIFers)
 */

get_header();

// Determine the post type this way because if no posts were found,
// get_post_type may return an empty string.
$post_type = get_post_type_outside_loop();

?>
<div class="content" role="main">
	<?php

	get_template_part( 'content-header', $post_type );
	get_template_part( 'loop', $post_type );
	get_template_part( 'content-footer', $post_type );

	?>
</div>
<?php get_footer();
