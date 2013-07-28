<?php

/**
 * Contains functions that provide common functionality for forms.
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 *
 * @author Nate Hart (and future CIFers)
 */



/**
 * Helps secure a form and protect it from automated spam.
 * ensure_form_is_protected() should be called in the form's
 * validate.php file with the same arguments to ensure that
 * no funny stuff occurred.
 *
 * This method should be called within the <form> element
 * in the form's form.php file.
 *
 * {$form_name}-extra is the name of the anti-spam form field.
 *
 * @param string $form_name A unique name for the form.
 */
function protect_form( $form_name ) {
	// Output a nonce field
	Validate::error_messages(
		$form_name . '_nonce',
		"Sorry, a security measure expired or was invalid. Try submitting the form again and see if it works. If not, <a href='mailto:board@cif.rochester.edu'>contact board</a>."
	);

	wp_nonce_field( $form_name, $form_name . '_nonce' );

	// Output an accessibly hidden field which some spam bots will fill out
	echo "<style>.$form_name-extra {position:absolute;top:-9999px;left:-9999px}</style>";

	echo "<label class='$form_name-extra'>";

	Validate::error_messages( $form_name . '-extra', "Please don't fill out this field." );

	echo "Leave this field blank to confirm your humanity.";
	echo "<input type='text' tabindex='-1' "; // Disallow tabbing to the form field
	text_field( $form_name . '-extra' );

	echo " /></label>";
}

/**
 * Ensures that the form was (probably) submitted by a human,
 * and that the human (probably) intended to submit the data they submitted.
 *
 * This should be called in the validate.php file of a form.
 * This method only works if protect_form() is called with the
 * same arguments in the form's form.php file.
 *
 * @param string $form_name A unique name for the form.
 */
function ensure_form_is_protected( $form_name ) {
	// Ensure that the nonce is valid
	Validate::validate_nonce( $form_name . '_nonce', $form_name );

	// Ensure the anti-spam field is empty
	Validate::validate_equal( $form_name . '-extra', '' );
}



// Functions for populating fields after the form has been submitted.
// These are useful in cases where the form needs to be displayed again.

/**
 * Sets the name attribute for an input element.
 * Also sets the value of the input element after the form has been submitted.
 *
 * @param string $name The name attribute of the field.
 */
function text_field( $name ) {
	echo ' name="' . esc_attr( $name ) . '" ';

	if ( isset( $_POST[$name] ) )
		echo ' value="' . $_POST[$name] . '" ';
}

/**
 * Sets the contents of a textarea after the form has been submitted.
 *
 * @param string $name The name attribute of the field.
 */
function textarea_contents( $name ) {
	if ( isset( $_POST[$name] ) )
		echo $_POST[$name];
}

/**
 * Sets the value attribute for an option element.
 * Also sets the selection of the select element after the form has been submitted.
 * This method should be called on each option element.
 *
 * @param string $name The name attribute of the select element.
 * @param string $value The value attribute of the option element.
 */
function option_field( $name, $value ) {
	echo ' value="' . esc_attr( $value ) . '" ';

	if ( isset( $_POST[$name] ) && $_POST[$name] === $value )
		echo ' selected ';
}

/**
 * Sets the type and name attributes for a checkbox input element.
 * Also sets the selection of the checkbox after the form has been submitted.
 *
 * @param string $name The name attribute of the field.
 */
function checkbox_field( $name ) {
	echo ' type="checkbox" name="' . esc_attr( $name ) . '" ';

	if ( isset( $_POST[$name] ) )
		echo ' checked ';
}

/**
 * Sets the type, name, and value attributes for a radio input element.
 * Also sets the selection of the radio button after the form has been submitted.
 *
 * @param string $name The name attribute of the field.
 * @param string $value The value attribute of the field.
 */
function radio_field( $name, $value ) {
	echo ' type="radio" name="' . esc_attr( $name ) . '" value="' . esc_attr( $value ) . '" ';

	if ( isset( $_POST[$name] ) && $_POST[$name] === $value )
		echo ' checked ';
}



// Functions for validating submitted form data.

/**
 * Ensures that the specified fields have been filled out.
 *
 * @param array $field_names An array of the names of the required form fields.
 * @return boolean True if the fields were filled out, false if one or more was not.
 */
function validate_required_fields( $field_names ) {
	foreach ( $field_names as $field_name )
		if ( ! isset( $_POST[$field_name] ) || $_POST[$field_name] === '' )
			return false;
	
	return true;
}
