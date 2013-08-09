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
	
	<!-- Allows HTML5 elements to be styled in older versions of IE -->
	<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->

	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,700,400italic,700italic|Lora:400,400italic,700,700italic|Inconsolata:400,700" />
	<?php // STYLESHEET_URL is defined in constants.php ?>
	<link rel="stylesheet" href="<?php echo STYLESHEET_URL; ?>" />
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
	<header id="header" role="banner">
		<div class="content">
			<h1 class="header-logo"><a href="<?php echo home_url(); ?>" rel="home">CIF</a></h1>

			<?php
			
			wp_nav_menu( array(
				'theme_location' => 'primary',
				'container'      => 'nav',
				'menu_class'     => 'inverted light menu',
			) );
			
			?>
		</div>
	</header>
