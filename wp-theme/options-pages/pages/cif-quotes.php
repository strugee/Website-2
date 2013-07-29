<?php

/**
 * Options page for managing CIF quotes.
 *
 * @author Nate Hart (and future CIFers)
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
