<?php

/**
 * Markup for a level 2 heading.
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 */

if ( get_sub_field( 'heading' ) ): ?>

<h2><?php the_sub_field( 'heading' ); ?></h2>

<?php endif;
