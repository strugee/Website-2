<?php

/**
 * Contains the markup for displaying two columns of content.
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 *
 * @author Nate Hart (and future CIFers)
 */

// Check if additional design options were specified and
// add the appropriate classes.
$classes = '';
if ( get_sub_field( 'design_options' ) ) {
	$options = get_sub_field( 'design_options' );

	if ( in_array( 'is_callout', $options) )
		$classes .= ' callout';
}

?>
	<div class="two-columns <?php echo $classes; ?>">
	<?php // TODO Only output columns if they have content (requires column pushing styles) ?>
	<div class="column-1">
		<?php the_sub_field( 'col_one' ); ?>
	</div>
	<div class="column-2">
		<?php the_sub_field( 'col_two' ); ?>
	</div>
</div>
