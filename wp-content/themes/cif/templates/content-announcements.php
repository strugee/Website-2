<?php

/**
 * Markup for an announcement post.
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 */

?>
<article class="article">
	<h3><?php the_title(); ?></h3>

	<footer class="article-metadata">
		<p>Posted on <?php the_time( 'F jS, Y' ); ?></p>
		<p>Posted by <?php the_author(); ?></p>
	</footer>

	<?php the_content(); ?>
</article>
