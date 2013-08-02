<?php

/**
 * Defines constants for use in the CIF theme.
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 *
 * @author Nate Hart (and future CIFers)
 */

/**
 * STYLESHEET_URL: The location of the theme's stylesheet.
 *                 We don't use WordPress's style.css for more
 *                 portability and caching from our CDN.
 * FAVICON_URL: The location of the theme's favicon.
 */

// The location of the theme directory
define( 'TEMPLATE_DIRECTORY', get_template_directory_uri() );

// Whether special debugging settings should be used
define( 'THEME_DEBUG', true );

if ( THEME_DEBUG ) {
	// Theme debugging/development settings

	define( 'STYLESHEET_URL', TEMPLATE_DIRECTORY . '/../../../../Website/cdn/css/style.css' );

	define( 'FAVICON_URL', TEMPLATE_DIRECTORY . '/../../../../Website/cdn/favicon.ico' );
} else {
	// Settings for the live site
	
	define( 'STYLESHEET_URL', 'cdn.cif.rochester.edu/style.css' );

	define( 'FAVICON_URL', 'cdn.cif.rochester.edu/favicon.ico' );
}

