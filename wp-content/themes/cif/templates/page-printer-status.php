<?php

/**
 * Displays the status of the printers in the lab.
 *
 * This file can be used for a page in WordPress by setting the
 * page template to "Lab Printer Status".
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 */

// Template Name: Lab Printer Status

get_header();

while ( have_posts() ):
	the_post(); ?>

	<div class="content">
		<h1><?php the_title(); ?></h1>

		<?php require_once __DIR__ . '/../printer-status/generator.php'; ?>
	</div>

<?php

endwhile;

get_footer();
