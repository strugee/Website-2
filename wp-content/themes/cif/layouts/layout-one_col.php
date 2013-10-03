<?php

/**
 * Markup for a single column of content.
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 */

// Check if additional design options were specified and
// add the appropriate classes.
$classes = '';
if ( get_sub_field( 'design_options' ) ) {
	$options = get_sub_field( 'design_options' );

	if ( in_array( 'is_callout', $options ) )
		$classes .= ' callout';
}

// Only wrap the contents if additional design options were specified
if ( $classes ): ?>
	<div class="<?php echo $classes; ?>">
<?php endif; ?>

<?php the_sub_field( 'col_one' ); ?>

<?php if ( $classes ): ?>
	</div>
<?php endif;
