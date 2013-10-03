<?php

/**
 * Email template for the CIF membership form.
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 */

$lines = array(
	$_POST['cif-name'] . ' (' . $_POST['netid'] . ')',
	$_POST['email'],
	'Class of ' . $_POST['cif-year'] . "\n",
	'Major: ' . $_POST['major'],
	'Residence: ' . $_POST['residence'],
	'Heard about CIF: ' . $_POST['howhear'],
	'Interest in CIF: ' . $_POST['interest'],
	"On-floor help:\n" .
		( ! empty( $_POST['contrib-techstaff'] ) ? "\tTech staff\n" : "" ) .
		( ! empty( $_POST['contrib-helpatcif'] ) ? "\tHelp@CIF\n" : "" ) .
		( ! empty( $_POST['contrib-fundraising'] ) ? "\tFundraising\n" : "" ) .
		( ! empty( $_POST['contrib-advertising'] ) ? "\tAdvertising\n" : "" ) .
		( ! empty( $_POST['contrib-planning'] ) ? "\tEvent planning\n" : "" ) .
		( ! empty( $_POST['contrib-other'] ) ? "\tOther: " . $_POST['contrib-other'] : "" ),
	'Visited: ' . $_POST['whenvisit'],
	'Living on floor: ' . $_POST['liveonfloor'],
	'Other comments: ' . $_POST['other-comments'],
);

// Output each line of the message
echo implode( "\n", $lines );
