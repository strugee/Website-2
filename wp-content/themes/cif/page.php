<?php

/**
 * Consolidates the contents for each page on the site.
 * The various layout components are loaded from the layouts/ directory.
 *
 * The layout components depend on using the Advanced Custom Fields plugin with
 * the fluid layout extension.
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 */

get_header();

while ( have_posts() ):
	the_post(); ?>

<div class="content" role="main">
	<h1><?php the_title(); ?></h1>

	<?php

	while ( has_sub_field( 'page_content' ) )
		get_template_part( 'layouts/layout', get_row_layout() );
	
	?>

<?php endwhile; ?>

</div>

<?php get_footer();
