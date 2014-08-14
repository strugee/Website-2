<?php

/**
 * Redirect attachment page requests to the actual attachment URI.
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 */

header( 'HTTP/1.1 301 Moved Permanently' );
header( 'Location: ' . $post->guid );
