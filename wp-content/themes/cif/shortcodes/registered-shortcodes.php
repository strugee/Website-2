<?php

/**
 * Registers all of CIF's custom shortcodes.
 * A shortcode is used in the WordPress post editor as [shortcode-name]
 * to trigger special functionality. Some shortcodes work by wrapping
 * around content, like [shortcode-name]content[/shortcode-name], and some
 * even accept arguments, like [shortcode-name arg="true"].
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 *
 * @author Nate Hart (and future CIFers)
 */

// Require necessary shortcode files here
require_once 'button.php';
require_once 'membership-form.php';


/**
 * Registers all of CIF's custom shortcodes.
 * A shortcode is used in the WordPress post editor as [shortcode-name]
 * to trigger special functionality. Some shortcodes work by wrapping
 * around content, like [shortcode-name]content[/shortcode-name], and some
 * even accept arguments, like [shortcode-name arg="true"].
 *
 * When creating a custom shortcode, be sure to document it througroughly.
 */
function cif_register_shortcodes(){
	add_shortcode( 'button', 'cif_button_shortcode' );
	add_shortcode( 'membership-form', 'cif_membership_form_shortcode' );
}
add_action( 'init', 'cif_register_shortcodes' );
