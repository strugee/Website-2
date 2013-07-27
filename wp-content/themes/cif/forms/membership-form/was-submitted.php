<?php

/**
 * Determines whether this form was submitted and sets $form_submitted accordingly.
 *
 * forms/functions.php uses this file to determine whether the form has been
 * submitted by checking the value of the $form_submitted variable.
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 *
 * @author Nate Hart (and future CIFers)
 */

$form_submitted = isset ( $_POST['cif-name'] );
