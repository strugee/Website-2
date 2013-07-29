<?php

/**
 * Registers custom post types which are archived by semester.
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 *
 * @author Nate Hart (and future CIFers)
 */

// Allow WordPress to recognize the "semester" query variable.
// Without this line, WordPress would be unable to retrieve the
// value of "semester", regardless of whether it's set explicitely
// in a URL (page.php?semester=fall).
add_rewrite_tag( '%semester%', '([^/]*)' );


// Install these semesterly custom post types
require_once 'announcements.php';
require_once 'meeting-minutes.php';
