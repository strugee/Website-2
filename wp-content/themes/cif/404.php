<?php

/**
 * Template for the 404 error page.
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 */

get_header(); ?>
<div class="content" role="main">
	<h1>Error 404</h1>

	<div class="two-columns">
		<p class="column">Sorry, we couldn't find what you were looking for. You could try starting from our <a href="<?php echo home_url(); ?>" rel="home">homepage</a>, or maybe what you're looking for is part of <a href="<?php echo PANEL_URL; ?>">panel</a>.</p>
		<div class="column">
		<img src="<?php echo TEMPLATE_DIRECTORY; ?>/images/computer-cat.jpg" alt="A cat playing inside of an empty computer" />
		</div>
	</div>
</div>
<?php get_footer();
