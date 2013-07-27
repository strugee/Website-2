<?php

/**
 * Contains functions that provide common functionality for forms.
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 *
 * @author Nate Hart (and future CIFers)
 */



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
