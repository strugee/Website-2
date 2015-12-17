## Forms
To make working with forms a bit simpler, Nate Hart went ahead and wrote a little framework for retrieving and handling forms.

### How it works
Each form should be a collection of files in their own subdirectory of the forms directory. Those forms can then be used from anywhere in WordPress by calling `get_form( 'form-subdirectory-name' )`, and their validation and submission will be automatically handled from there. It might be strange to work with at first, but once you get the hang of it creating well-made forms should be much simpler.

Here's a rundown of necessary files for a form:

##### form.php
This file should contain the markup for the form, along with validation error messages and preferably a call to `protect_form` to prevent automated spam and make the form more secure.

##### was-submitted.php
This file simply needs to determine whether the form was submitted and set `$form_submitted` to `true` if it was, or `false` if it wasn't.

##### validate.php
This file needs to perform the appropriate validation measures for the form and set `$form_valid` to `true` if the form is valid or `false` if it isn't. The `Validator` class is useful for validating fields. See below for information on how to use it. If you called `protect_form` in `form.php`, you need to call `ensure_form_is_protected` here.

##### on-submit.php
This file needs to do something with the valid form data. Store it, email it, whatever. It should also output markup for a form submission success message (or a failure message, in case an email failed to send or something).

### Validator
`Validator` is a class for validating form data. Here are the static methods anyone writing a form should know about:

* `validate_required( $fields )`
* `validate_zip( $fields )`
* `validate_date( $fields )`
* `validate_phone( $fields )`
* `validate_email( $fields) ` (please don't use this one if you can)
* `validate_equality( $fields, $value, $case = 'insensitive')`

These can be called on a single field or multiple fields at once.

	Validator::validate_required( array(
		'field_1',
		'field_2',
		'field_3',
	) );

	Validator::validate_zip( 'field_2' );

An error message can be output for any validation errors in `form.php` using `Validator`. Either a single message can be given, or a message can be specified for each error code that a field may experience.

	Validator::error_messages( 'field_1', 'Field 1 is required' );

	Validator::error_messages( 'field_2', array(
		ERROR_REQUIRED   => 'Field 2 is required',
		ERROR_ZIP_FORMAT => 'Field 2 is not a valid zip code',
	) );

Error codes:
* `ERROR_REQUIRED`
* `ERROR_ZIP_FORMAT`
* `ERROR_DATE_FORMAT`
* `ERROR_PHONE_FORMAT`
* `ERROR_EMAIL_FORMAT`
* `ERROR_EQUALITY`
* `ERROR_NONCE`
