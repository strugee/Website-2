<?php

/**
 * Options page for managing CIF quotes.
 *
 * This class follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 */

?>
<div class="wrap">
	<?php screen_icon(); ?>

	<h2>CIF Quotes</h2>

	<p>Quotes that are worth posting on the fridge! Except instead of the kitchen fridge they're going on our site.</p>

	<form method="post" action="options.php"> 
		<?php
		
		settings_fields( 'cif-quotes' );
		do_settings_sections( 'cif-quotes' );

		submit_button( 'Save Quotes' );

		?>
	</form>
</div>
