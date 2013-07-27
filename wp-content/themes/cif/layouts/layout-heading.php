<?php

/**
 * Contains the markup for displaying a level 2 heading.
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 *
 * @author Nate Hart (and future CIFers)
 */

if ( get_sub_field( 'heading' ) ): ?>

<h2><?php the_sub_field( 'heading' ); ?></h2>

<?php endif;
