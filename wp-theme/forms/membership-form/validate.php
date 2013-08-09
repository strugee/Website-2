<?php

/**
 * Ensures that the submitted form data is valid and sets $form_valid accordingly.
 *
 * Please ensure that appropriate validation error messages are set in form.php.
 *
 * form/functions.php uses this file to determine whether the form data is valid
 * by checking the value of the $form_valid variable.
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 */



Validator::validate_required( array(
	'cif-name',
	'cif-year',
	'netid',
	'email',
	'howhear',
	'interest',
	'whenvisit',
	'liveonfloor',
) );

// Helps protect against automated spam and and improves security
ensure_form_is_protected( 'membership-form' );

$form_valid = ! Validator::found_validation_errors();
