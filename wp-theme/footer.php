<?php

/**
 * Footer for all site pages. This document includes the <footer>,
 * closing <body> tag, and closing <html> tag.
 *
 * This template also outputs the secondary navigation menu.
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 */

?>
	<footer id="footer" role="contentinfo">
		<div class="content">
			<?php
			
			wp_nav_menu( array(
				'theme_location' => 'secondary',
				'container'      => 'nav',
				'menu_class'     => 'inverted light menu',
			) );
			
			?>
			
			<p class="copyright">&copy; 2013 CIF</p>

			<?php

			// Get a random quote
			$quote_settings = get_option( 'cif-quotes' );
			$quotes = explode( "\n", $quote_settings['quotes'] );

			$quote = $quotes[ array_rand( $quotes ) ];

			?>

			<p class="quote"><?php echo $quote; ?></p>
		</div>
	</footer>
	
	<?php

	/**
	 * wp_footer is an important hook for loading assets
	 * which need to be output after the contents of the page.
	 */
	wp_footer();

	?>
</body>
</html>
