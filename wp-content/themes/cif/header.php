<?php

/**
 * Header for all site pages. This document includes the <head>,
 * opening <body> tag, and <header> element for all pages.
 *
 * This template also outputs the primary navigation menu.
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 */

// We don't want any whitespace before the doctype, so excuse the formatting.
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8" />

	<title><?php wp_title( '&mdash;', true, 'right' ); bloginfo( 'name' ); ?></title>

	<?php // FAVICON_URL is defined in constants.php ?>
	<link rel="shortcut icon" href="<?php echo FAVICON_URL; ?>" />
	<link rel="apple-touch-icon" sizes="114x114" href="<?php echo CDN_URL; ?>cif-icon-114.png">
	<link rel="apple-touch-icon" sizes="144x144" href="<?php echo CDN_URL; ?>cif-icon-144.png">
	<link rel="apple-touch-icon" sizes="180x180" href="<?php echo CDN_URL; ?>cif-icon-180.png">
	
	<!-- Allows HTML5 elements to be styled in older versions of IE -->
	<!--[if lt IE 9]><script src="https://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->

	<?php // STYLESHEET_URL is defined in constants.php ?>
	<link rel="stylesheet" href="<?php echo STYLESHEET_URL; ?>" />
	<!--[if IE]><link rel="stylesheet" href="<?php echo IE_STYLESHEET_URL; ?>" /><![endif]-->
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<div id="page">

		<header id="header" class="condensed" role="banner">
			<div class="content">
				<h1><a class="header-logo" href="<?php echo home_url(); ?>" rel="home"><span class="screen-reader-text">CIF</span></a></h1>

				<?php
				
				wp_nav_menu( array(
					'theme_location' => 'primary',
					'container'      => 'nav',
					'menu_class'     => 'inverted light menu',
				) );
				
				?>
			</div>
		</header>
