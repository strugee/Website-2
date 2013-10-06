<?php

/**
 * Defines constants for use in the CIF theme.
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 */

/**
 * Important variables:
 *
 * STYLESHEET_URL: The location of the theme's stylesheet.
 *                 We don't use WordPress's style.css for more
 *                 portability and caching from our CDN.
 * FAVICON_URL: The location of the theme's favicon.
 * MEMBERSHIP_FORM_EMAIL_TO: To field of the email sent when
 *                           the membership form is submitted.
 * MEMBERSHIP_FORM_EMAIL_HEADERS Additional headers for the email sent
 *                               when the membership form is submitted.
 * MEMBERSHIP_FORM_EMAIL_SUBJECT Subject field of the email sent when
 *                               the membership form is submitted.
 *                               {{name}} will be replaced with the
 *                               student's name.
 */


// Whether special debugging settings should be used
// This may cause different behavoir in other parts of this theme
define( 'THEME_DEBUG', false );

// Whether the debugging constants should be used
// This will only change the values of the constants defined in this file
define( 'USE_DEBUG_CONSTANTS', false );


// The location of the theme directory
define( 'TEMPLATE_DIRECTORY', get_template_directory_uri() );


if ( THEME_DEBUG || USE_DEBUG_CONSTANTS ) {
	// Theme debugging/development constants

	define( 'STYLESHEET_URL', TEMPLATE_DIRECTORY . '/../../../../Website/cdn/css/style.css' );

	define( 'FAVICON_URL', TEMPLATE_DIRECTORY . '/../../../../Website/cdn/favicon.ico' );

	define( 'PANEL_URL', 'https://cif.rochester.edu/panel/' );

	define( 'MEMBERSHIP_FORM_EMAIL_TO', 'board@cif.rochester.edu' );

	define( 'MEMBERSHIP_FORM_EMAIL_HEADERS', 'From: "Membership Application" <root@web1.cif.rochester.edu>' );

	define( 'MEMBERSHIP_FORM_EMAIL_SUBJECT', 'CIF Membership Application for {{name}}' );
} else {
	// Constants for the live site
	
	define( 'STYLESHEET_URL', 'https://cif.rochester.edu/cdn/css/style.css' );

	define( 'FAVICON_URL', 'https://cif.rochester.edu/cdn/favicon.ico' );
	
	define( 'PANEL_URL', 'https://cif.rochester.edu/panel/' );

	define( 'MEMBERSHIP_FORM_EMAIL_TO', 'board@cif.rochester.edu' );

	define( 'MEMBERSHIP_FORM_EMAIL_HEADERS', 'From: "Membership Application" <web1@web1.cif.rochester.edu>' );

	define( 'MEMBERSHIP_FORM_EMAIL_SUBJECT', 'CIF Membership Application for {{name}}' );
}
