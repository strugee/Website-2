<?php

/**
 * Announcements template for when there is no content to display.
 * This is used when no announcements have been posted for the
 * requested semester.
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 *
 * @author Nate Hart (and future CIFers)
 */

?>
	<article>
		<p>There are no announcements for the <?php echo get_query_var( 'semester' );?> semester of <?php echo get_query_var( 'year' ); ?>.</p>
	</article>
