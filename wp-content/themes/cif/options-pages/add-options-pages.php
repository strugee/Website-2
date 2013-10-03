<?php

/**
 * Registers and adds custom options pages to the
 * Settings page in WordPress.
 *
 * This class follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 */

/**
 * Adds custom options pages to the Settings page in WordPress.
 *
 * @see http://codex.wordpress.org/Function_Reference/add_options_page
 */
function cif_add_options_pages() {
	// Options page for editing CIF quotes
	add_options_page( 'CIF Quotes', 'CIF Quotes', 'manage_options', 'cif-quotes', 'cif_quotes_option_page');
}
add_action( 'admin_menu', 'cif_add_options_pages' );


/**
 * Registers settings and settings sections/fields for custom options pages.
 */
function cif_register_option_settings() {
	// Options for editing CIF quotes
	register_setting( 'cif-quotes', 'cif-quotes' );
	add_settings_section( 'cif-quotes-main', 'Quotes', 'cif_quotes_section_text', 'cif-quotes' );
	add_settings_field( 'cif-quotes', 'Quotes', 'cif_quotes_field_markup', 'cif-quotes', 'cif-quotes-main' );
}
add_action( 'admin_init', 'cif_register_option_settings' );



// CIF Quotes options page callback functions

/**
 * Outputs the options page for CIF quotes.
 */
function cif_quotes_option_page() {
	require 'pages/cif-quotes.php';
}

/**
 * Outputs text for the CIF quotes options page section.
 */
function cif_quotes_section_text() {
	echo '<p>Please place one quote per line.</p>';
}

/**
 * Outputs markup for the CIF quotes field.
 */
function cif_quotes_field_markup() {
	$options = get_option( 'cif-quotes' );
	echo "<textarea id='cif-quotes' name='cif-quotes[quotes]' cols='80' rows='10'>{$options['quotes']}</textarea>";
}
