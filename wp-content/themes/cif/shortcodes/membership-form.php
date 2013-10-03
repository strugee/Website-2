<?php

/**
 * Shortcode for displaying the CIF membership form.
 * The displayed form will already handle submission and validation.
 * You only need to call this shortcode for the full functionality.
 *
 * This shortcode is self-closing, and does not accept contents or attributes.
 *
 * Example usage:
 *   [membership-form]
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 */
function cif_membership_form_shortcode( $atts ) {
	return get_form( 'membership-form' );
}
