<?php

/**
 * Handles the submission of valid form data.
 * A success message is displayed if the submission email was sent successfully.
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 */

$email_to = MEMBERSHIP_FORM_EMAIL_TO;
$email_headers = MEMBERSHIP_FORM_EMAIL_HEADERS;
$subject = str_replace( '{{name}}', $_POST['cif-name'], MEMBERSHIP_FORM_EMAIL_SUBJECT );



// Start the output buffer
ob_start();

// Get the parsed contents of the email template file
require 'templates/email.php';
$message = ob_get_contents();

// End the output buffer
ob_end_clean();



// Send the email

if ( mail( $email_to, $subject, $message, $email_headers ) ) {
	// Display a message that the email was sent successfully
	require 'templates/success-message.php';
} else {
	// Sending the email failed, display an error message and the form
	echo "<p class='form-error'>Sorry, your application couldn't be emailed to our Executive Board. Try <a href='mailto:board@cif.rochester.edu'>contacting board</a> with your application manually and let them know that something went wrong here. We're sorry!</p>";

	// Not using our get_form() function because we only want the markup,
	// not the automatic form handling as well
	require 'form.php';
}
