<?php

/**
 * Displays the status of the printers in the lab.
 *
 * This file can be used for a page in WordPress by setting the
 * page template to "Lab Printer Status".
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 *
 * @author Nate Hart (and future CIFers)
 */

// Template Name: Lab Printer Status

get_header();

while ( have_posts() ): ?>
	<?php the_post(); ?>

	<div class="content">
		<h1><?php the_title(); ?></h1>

		<?php require_once 'printer-status/generator.php'; ?>
	</div>
<?php endwhile; ?>
<?php get_footer();
