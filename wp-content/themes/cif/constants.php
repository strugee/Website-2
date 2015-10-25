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


require_once 'theme-config.php';

// The location of the theme directory
define( 'TEMPLATE_DIRECTORY', get_template_directory_uri() );


if ( THEME_DEBUG || USE_DEBUG_CONSTANTS ) {
	// Theme debugging/development constants

	define( 'STYLESHEET_URL', 'https://cdn-webplusplus.cif.rochester.edu/css/style.css' );

	define( 'IE_STYLESHEET_URL', 'https://cdn-webplusplus.cif.rochester.edu/css/ie.css' );

	define( 'FAVICON_URL', 'https://cdn-webplusplus.cif.rochester.edu/favicon.ico' );

	define( 'PANEL_URL', 'https://webplusplus.cif.rochester.edu/panel/' );

	define( 'MEMBERSHIP_FORM_EMAIL_TO', 'board@cif.rochester.edu' );

	define( 'MEMBERSHIP_FORM_EMAIL_HEADERS', 'From: "Membership Application" <root@webplusplus.cif.rochester.edu>' );

	define( 'MEMBERSHIP_FORM_EMAIL_SUBJECT', 'CIF Membership Application for {{name}}' );
} else {
	// Constants for the live site
	
	define( 'STYLESHEET_URL', 'https://cif.rochester.edu/cdn/css/style.css' );

	define( 'IE_STYLESHEET_URL', 'https://cdn.cif.rochester.edu/css/ie.css' );

	define( 'FAVICON_URL', 'https://cif.rochester.edu/cdn/favicon.ico' );
	
	define( 'PANEL_URL', 'https://cif.rochester.edu/panel/' );

	define( 'MEMBERSHIP_FORM_EMAIL_TO', 'board@cif.rochester.edu' );

	define( 'MEMBERSHIP_FORM_EMAIL_HEADERS', 'From: "Membership Application" <web1@web1.cif.rochester.edu>' );

	define( 'MEMBERSHIP_FORM_EMAIL_SUBJECT', 'CIF Membership Application for {{name}}' );
}
