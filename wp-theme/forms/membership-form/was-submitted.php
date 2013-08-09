<?php

/**
 * Determines whether the form was submitted and sets $form_submitted accordingly.
 *
 * forms/functions.php uses this file to determine whether the form has been
 * submitted by checking the value of the $form_submitted variable.
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 */

// Determine this by checking if the 'cif-name' field was set
$form_submitted = isset ( $_POST['cif-name'] );
