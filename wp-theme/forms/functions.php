<?php

/**
 * Contains functions that are useful for forms.
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 */

require_once 'Validator.class.php';
require_once 'common.php';

/**
 * Returns the markup for the form of the given name, automatically handling form
 * submission and validation. The name of the form is the name of the
 * subdirectory of the forms directory which it resides in.
 *
 * The form directory must have a form.php file which outputs the
 * markup for the form.
 *
 * @param string $name The name of the form.
 * @return string The markup for the form, or a submission success message if it was submitted successfully.
 */
function get_form( $name ) {
	if ( form_was_submitted( $name ) ) {
		if ( form_is_valid( $name ) ) {
			// If the form data is valid, submit it
			return get_submitted_form( $name );
		} else {
			// Otherwise, display the form again
			return get_form_markup( $name );
		}
	} else {
		// Display the form if it was not submitted
		return get_form_markup( $name );
	}
}



/**
 * Returns the markup for the form of the given name.
 * The name of the form is the name of the subdirectory of the forms directory
 * which it resides in.
 *
 * @param string $name The name of the form.
 * @return string The markup for the form.
 */
function get_form_markup( $name ) {
	// Start the output buffer
	ob_start();

	// Get the parsed contents of the form template file
	require "$name/form.php";
	$markup = ob_get_contents();

	// End the output buffer
	ob_end_clean();

	return $markup;
}



/**
 * Determines whether the form with the given name was submitted.
 * The name of the form is the name of the subdirectory of the forms directory
 * which it resides in.
 *
 * The form directory must have a was-submitted.php file which sets the
 * $form_submitted variable accordingly.
 *
 * @param string $name The name of the form.
 * @return boolean True if the form was submitted, false otherwise.
 */
function form_was_submitted( $name ) {
	require "$name/was-submitted.php";

	return $form_submitted;
}



/**
 * Determines whether the form with the given name was submitted with valid data.
 * The name of the form is the name of the subdirectory of the forms/ directory
 * which it resides in.
 *
 * The form directory must have a validate.php file which sets the
 * $form_valid variable accordingly.
 *
 * @param string $name The name of the form.
 * @return boolean True if the form was submitted with valid data, false otherwise.
 */
function form_is_valid( $name ) {
	require "$name/validate.php";

	return $form_valid;
}



/**
 * Handles the submission of valid for data for the form with the specified name.
 * The name of the form is the name of the subdirectory of the forms directory
 * which it resides in.
 *
 * The form directory must have an on-submit.php file.
 *
 * @param string $name The name of the form.
 * @return string The markup for the submitted form template.
 */
function get_submitted_form( $name ) {
	// Start the output buffer
	ob_start();

	// Get the parsed contents of the success template file
	require "$name/on-submit.php";
	$markup = ob_get_contents();

	// End the output buffer
	ob_end_clean();

	return $markup;
}
