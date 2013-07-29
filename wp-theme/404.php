<?php

/**
 * Contains the markup for displaying a 404 message.
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 *
 * @author Nate Hart (and future CIFers)
 */

?>
<?php get_header(); ?>
<div class="content" role="main">
	<h1>Error 404</h1>
	<div class="two-columns">
		<p class="column">Sorry, we couldn't find what you were looking for. You could try starting from our <a href="<?php echo home_url(); ?>">homepage</a>, or maybe what you're looking for is part of <a href="https://panel.cif.rochester.edu">panel</a>.</p>
	</div>
</div>
<?php get_footer(); ?>
