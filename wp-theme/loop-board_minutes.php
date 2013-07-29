<?php

/**
 * Contains the basic markup for displaying meeting minutes.
 * This file also creates a secondary post query to retrieve
 * all floor meeting minutes as well.
 *
 * The $post_type variable is available when this template part
 * has been requested by index.php.
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 *
 * @author Nate Hart (and future CIFers)
 */

?>

<div class="column">
	<h2>Board Meeting Minutes</h2>

	<?php

	if ( have_posts() ) {
		while ( have_posts() ) {
			the_post();

			get_template_part( 'content', 'board_minutes' );
		}
	} else {
		get_template_part( 'no-content', 'board_minutes' );
	}

	// Query for floor meeting minutes
	$floor_query = new WP_Query( array (
		'post_type' => 'floor_minutes',
	) );

	?>
</div>
<div class="last column">
	<h2>Floor Meeting Minutes</h2>

	<?php

	// Run a second loop for floor minutes
	if ( $floor_query->have_posts() ) {
		while ( $floor_query->have_posts() ) {
			$floor_query->the_post();

			get_template_part( 'content', 'floor_minutes' );
		}
	} else {
		get_template_part( 'no-content', 'floor_minutes' );
	}

	// Restore the original post data from the primary query
	wp_reset_query();

	?>
</div>
