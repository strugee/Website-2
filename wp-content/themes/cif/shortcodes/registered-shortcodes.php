<?php

/**
 * Imports and registers custom shortcodes.
 *
 * Custom shortcodes should be documented in Wiki so that editors know
 * what's available to them.
 *
 * A shortcode is used in the WordPress post editor as [shortcode-name]
 * to trigger special functionality. Some shortcodes work by wrapping
 * around content, like [shortcode-name]content[/shortcode-name], and some
 * even accept arguments, like [shortcode-name arg="accepted"].
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 */

// Require necessary shortcode files here
require_once 'button.php';
require_once 'membership-form.php';


/**
 * Registers all of CIF's custom shortcodes.
 *
 * When creating a custom shortcode, be sure to document it througroughly.
 */
function cif_register_shortcodes(){
	// add_shortcode( 'shortcode_name', 'shortcode_callback_function' );
	add_shortcode( 'button', 'cif_button_shortcode' );
	add_shortcode( 'membership-form', 'cif_membership_form_shortcode' );
}
add_action( 'init', 'cif_register_shortcodes' );
