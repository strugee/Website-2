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
 *
 * @author Nate Hart (and future CIFers)
 */



Validate::validate_required( array(
	'cif-name',
	'cif-year',
	'netid',
	'email',
	'howhear',
	'interest',
	'whenvisit',
	'liveonfloor',
) );

$form_valid = ! Validate::has_errors();
