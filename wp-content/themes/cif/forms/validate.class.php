<?php

// Define error code constants
define('REQUIRED',		0);
define('ZIP_FORMAT',	1);
define('DATE_FORMAT',	2);
define('PHONE_FORMAT',	3);
define('EMAIL_FORMAT',	4);



/**
 * Validates form data.
 * Check the comments on each method for notes on usage.
 *
 * This class follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 *
 * @author Nate Hart (and future CIFers)
 */
class Validate {
	/**
	 * Cache errors as we find them.
	 * If an error is found, an array index for the field name will be set to an error code.
	 * Otherwise, a valid field name will not have an index in this array.
	 */
	private static $errors;
	public static $error_class;
	
	
	
	/**
	 * Initializes class values.
	 * This method MUST be called directly after this class definition!
	 */
	public static function init() {
		self::$errors = array();
		self::$error_class = 'form-error';
	}
	

	
	/**
	 * Logs that the specified form field experienced a validation error.
	 *
	 * @param string $field_name The name of the field.
	 * @param int $code The error code.
	 */
	private function log_error( $field_name, $code ) {
		self::$errors[$field_name] = $code;
	}
	
	/**
	 * Determines whether the specified field is invalid.
	 * 
	 * @param string $field_name The name of the field from the form to check.
	 * @return boolean True if the field was not filled out correctly, false if it was filled out correctly.
	 */
	public static function is_invalid( $field_name ) {
		return isset( self::$errors[$field_name] );
	}
	
	/**
	 * Determines whether any errors were found when validating the form.
	 * 
	 * @return boolean True if no errors were encountered, false otherwise.
	 */
	public static function has_errors() {
		return ! empty( self::$errors );
	}
	
	
	
	/**
	 * Outputs an error message if the specified form field is invalid.
	 *
	 * @param string $field_name The name of the field to display an error message for.
	 * @param mixed $messages Either an error message, or an array of messages indexed by error codes.
	 */
	public static function error_messages( $field_name, $messages ) {
		if ( self::is_invalid( $field_name ) ) {
			// If error messages were provided for different errors,
			// get the message for this error
			if ( is_array( $messages ) )
				$message = $messages[ self::$errors[$field_name] ];
			else
				$message = $messages;
			
			echo "<p class='" . self::$error_class . "'>$message</p>";
		}
	}
	
	
	
	/**
	 * Determines whether the specified fields were sent and filled out.
	 * An error is logged if a field is invalid.
	 *
	 * @param mixed $field_names Either a string with the name of the field to validate, or an array of strings of field names.
	 */
	public static function validate_required( $field_names ) {
		if ( is_array( $field_names ) ) {
			foreach ( $field_names as $field )
				self::validate_required( $field );

			return; // We're done with this method at this point
		}
		
		// Check if the field was not sent or not filled out
		if ( ( ! isset( $_POST[$field_names] ) || trim( $_POST[$field_names] ) === '' ) && ! self::is_invalid( $field_names ) )
			self::log_error( $field_names, REQUIRED );
	}
	
	/**
	 * Determines whether the specified fields are properly formatted US zip codes.
	 * An error is logged if a field is invalid.
	 *
	 * @param mixed $field_names Either a string with the name of the field to validate, or an array of strings of field names.
	 */
	public static function validate_zip( $field_names ) {
		if ( is_array( $field_names ) ) {
			foreach ( $field_names as $field )
				self::validate_zip( $field );

			return; // We're done with this method at this point
		}
		
		// Don't try to validate blank data (this allows optional fields to be validated only if they've been filled out)
		if ( $_POST[$field_names] === '' )
			return;
		
		// Check if the formatting is valid
		if ( ! preg_match( '/^\d{5}(-\d{4})?$/', $_POST[$field_names] ) && ! self::is_invalid( $field_names ) )
			self::log_error( $field_names, ZIP_FORMAT );
	}
	
	/**
	 * Determines whether the specified fields are dates formatted as yyyy-mm-dd.
	 * An error is logged if a field is invalid.
	 *
	 * @param mixed $field_names Either a string with the name of the field to validate, or an array of strings of field names.
	 */
	public static function validate_date( $field_names ) {
		if ( is_array( $field_names ) ) {
			foreach ( $field_name as $field )
				self::validate_date( $field );

			return; // We're done with this method at this point
		}
		
		// Don't try to validate blank data (this allows optional fields to be validated only if they've been filled out)
		if ( $_POST[$field_names] === '' )
			return;
		
		// Check if the formatting is valid
		if ( ! preg_match('/^(\d{4})\D?(0[1-9]|1[0-2])\D?([12]\d|0[1-9]|3[01])$/', $_POST[$field_names] ) && ! self::is_invalid( $field_names ) )
			self::log_error( $field_names, DATE_FORMAT );
	}
	
	/**
	 * Determines whether the specified fields are properly formatted US phone numbers.
	 * An error is logged if a field is invalid.
	 *
	 * @param mixed $field_names Either a string with the name of the field to validate, or an array of strings of field names.
	 */
	public static function validate_phone( $field_names ) {
		if ( is_array( $field_names ) ) {
			foreach ( $field_names as $field )
				self::validate_phone( $field );

			return; // We're done with this method at this point
		}
		
		// Don't try to validate blank data (this allows optional fields to be validated only if they've been filled out)
		if ( $_POST[$field_names] === '' )
			return;
		
		// Check if the formatting is valid
		if ( ! self::is_invalid( $field_names ) && ! preg_match( '/^\(?([0-9]{3})\)?[-. ]?([0-9]{3})[-. ]?([0-9]{4})$/', $_POST[$field_names] ) )
			self::log_error( $field_names, PHONE_FORMAT );
	}
	
	/**
	 * Determines whether the specified fields are properly formatted email addresses.
	 * It is recommended not to use this validator because there will be edge cases where valid addresses don't validate.
	 * An error is logged if a field is invalid.
	 *
	 * @param mixed $field_names Either a string with the name of the field to validate, or an array of strings of field names.
	 */
	public static function validate_email($field_names) {
		if ( is_array( $field_names ) ) {
			foreach ( $field_names as $field )
				self::validate_email( $field );

			return; // We're done with this method at this point
		}
		
		// Don't try to validate blank data (this allows optional fields to be validated only if they've been filled out)
		if ( $_POST[$field_names] === '' )
			return;
		
		// Check if the formatting is likely to be valid
		// Regular expressions for email addresses are tricky and prone to mark the occassional strange address as invalid
		if ( ! self::is_invalid( $field_names ) && ! preg_match( "~[a-z0-9!#$%&'*+/=?^_`{|}\~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}\~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?~", $_POST[$field_names] ) )
			self::log_error( $field_names, EMAIL_FORMAT );
	}
}

Validate::init(); // Get this thing started
