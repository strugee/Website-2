<?php

/**
 * Markup for a board meeting minutes post.
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 */

?>
<article>
	<h3><?php the_time( 'F jS, Y' ); ?></h3>
	<?php the_content(); ?>
</article>
