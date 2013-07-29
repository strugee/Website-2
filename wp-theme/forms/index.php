<?php

/**
 * Returns a 404 when a client requests the parent directory of this file.
 * We don't need visitors prying around this directory.
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 *
 * @author Nate Hart (and future CIFers)
 */

global $wp_query;

$wp_query->set_404();
