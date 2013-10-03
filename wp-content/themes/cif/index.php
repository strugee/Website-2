<?php

/**
 * Consolidates the contents for each post on the site.
 * The markup for each template part can be found in the templates directory.
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 */

get_header();

// Determine the post type this way because if no posts were found,
// get_post_type may return an empty string.
// This is a method we defined in functions.php
$post_type = get_post_type_outside_loop();

?>
<div class="content" role="main">
	<?php

	get_template_part( 'templates/content-header', $post_type );
	get_template_part( 'templates/loop',           $post_type );
	get_template_part( 'templates/content-footer', $post_type );

	?>
</div>
<?php get_footer();
