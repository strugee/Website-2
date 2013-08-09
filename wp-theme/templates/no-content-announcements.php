<?php

/**
 * Template for when there are no announcement posts to display.
 * Commonly used when there are no announcements for the requested semester.
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 */

?>
<article>
	<p>There are no announcements for the <?php echo get_query_var( 'semester' );?> semester of <?php echo get_query_var( 'year' ); ?>.</p>
</article>
