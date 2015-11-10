<?php

/**
 * Registers custom post types which are archived by semester.
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 */

/**
 * Allow WordPress to recognize the "semester" query variable.
 * Without this line, WordPress would be unable to retrieve the
 * value of "semester", regardless of whether it's set explicitely
 * in a URL (like page.php?semester=fall).
 */
add_rewrite_tag( '%semester%', '([^/]*)' );

// Require semesterly post functions
require_once 'functions.php';

// Register these semesterly custom post types
require_once 'types/announcements.php';
require_once 'types/meeting-minutes.php';
