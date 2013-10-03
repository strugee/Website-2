<?php

/**
 * Shortcode for creating a button from a link in the WordPress editor.
 *
 * This shortcode should be wrapped around a link that has been
 * created normally in the WordPress post editor. Only a single link
 * is allowed within this shortcode.
 *
 * Arguments:
 *   string align How to align the button. Either "left" or "right".
 *                Defaults to "", which behaves much like "left".
 *
 * Example usage:
 *   [button]This is a link in the WordPress editor[/button]
 *   [button align="right"]I am a right aligned button[/button]
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 */
function cif_button_shortcode( $atts, $content = null ) {
	// Do nothing if there is no content
	if ( $content === null )
		return $content;

	// Convert the arguments to PHP variables of the same name
	extract( shortcode_atts( array(
		'align' => '',
	), $atts ) );

	// If an alignment was specified, add a class for it
	$align_class = '';
	if ( $align != '' )
		$align_class = 'align-' . strtolower( $align );

	// Set a button class on the contents of the button shortcode
	if ( strpos( $content, 'class="' ) !== false ) {
		// Case: modify existing class attribute (using double quotes to wrap attribute value)
		$content = str_replace( 'class="', 'class="button ' . $align_class . ' ', $content );
	} else if ( strpos( $content, "class='" ) !== false ) {
		// Case: modify existing class attribute (using single quotes to wrap attribute value)
		$content = str_replace( "class='", "class='button " . $align_class . ' ', $content );
	} else {
		// Case: add class attribute
		$content = str_replace( '<a', '<a class="button ' . $align_class . '"', $content );
	}

	return $content;
}
