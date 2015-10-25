<?php

/**
 * Template for a meeting minutes posts archive.
 * Also creates a secondary post query to retrieve floor minutes.
 * Since this template is called for board minutes, those posts
 * have already been retrieved.
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 */

?>
<div class="column-1">
	<h2>Board Meeting Minutes</h2>

	<?php

	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();

			get_template_part( 'templates/content', 'board_minutes' );
		}
	} else {
		get_template_part( 'templates/no-content', 'board_minutes' );
	}

	// Query for floor meeting minutes
	$floor_query = new WP_Query( array(
		'post_type' => 'floor_minutes',
	) );

	?>
</div>

<div class="column-2">
	<h2>Floor Meeting Minutes</h2>

	<?php

	// Run a secondary query for floor minutes
	if ( $floor_query->have_posts() ) {
		while ( $floor_query->have_posts() ) {
			$floor_query->the_post();

			get_template_part( 'templates/content', 'floor_minutes' );
		}
	} else {
		get_template_part( 'templates/no-content', 'floor_minutes' );
	}

	// Restore the original post data from the primary query
	wp_reset_query();

	?>
</div>
