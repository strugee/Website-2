<?php

/**
 * Outputs the markup for this form.
 * 
 * PLEASE NOTE: WordPress reserves certain POST values for itself,
 * so any form fields with those names may causes a 404 to be returned
 * instead of the form functioning properly. This includes common names
 * like "name" and "year". See http://codex.wordpress.org/WordPress_Query_Vars#Query_variables for a list of reserved names.
 *
 * forms/functions.php uses this file to display the form.
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 *
 * @author Nate Hart (and future CIFers)
 */

?>
<form action="" method="post">
	<?php

	// Helps protect against automated spam and and improves security
	protect_form( 'membership-form' );

	?>

	<fieldset>
		<div class="four-columns">
			<label class="column">
				Name
				<?php Validate::error_messages( 'cif-name', "Please tell us your name, we'd like to get to know you!" ); ?>
				<input type="text" <?php text_field( 'cif-name' ); ?> />
			</label>

			<label class="column">
				Year
				<?php Validate::error_messages( 'cif-year', "Please tell us your class year." ); ?>
				<input type="text" <?php text_field( 'cif-year' ); ?> />
			</label>

			<label class="column">
				NetID
				<?php Validate::error_messages( 'netid', "We'll need your NetID before we can add you as a member." ); ?>
				<input type="text" <?php text_field( 'netid' ); ?> />
			</label>

			<label class="last column">
				Residence
				<input type="text" <?php text_field( 'residence' ); ?> />
			</label>
		</div>
		<div class="four-columns">
			<label class="column">
				Email
				<?php Validate::error_messages( 'email', "We'll need your email address to get back to you." ); ?>
				<input type="text" <?php text_field( 'email' ); ?> />
			</label>

			<label class="column">
				Major
				<input type="text" <?php text_field( 'major' ); ?> />
			</label>
		</div>
	</fieldset>

	<fieldset class="two-columns">
		<label class="column">
			How did you hear about CIF?
			<?php Validate::error_messages( 'howhear', "Please tell us how you heard about CIF." ); ?>
			<textarea name="howhear"><?php textarea_contents( 'howhear' ); ?></textarea>
		</label>
		<label class="last column">
			Why do you want to be a CIF member?
			<?php Validate::error_messages( 'interest', "Please tell us why you'd like to join CIF." ); ?>
			<textarea name="interest"><?php textarea_contents( 'interest' ); ?></textarea>
		</label>
	</fieldset>

	<div class="four-columns">
		<fieldset class="column">
			<legend>How can you contribute to CIF?</legend>

			<label>
			<input <?php checkbox_field( 'contrib-techstaff' ); ?> />
				Tech Staff
			</label>

			<label>
				<input <?php checkbox_field( 'contrib-helpatcif' ); ?> />
				Help@CIF
			</label>

			<label>
				<input <?php checkbox_field( 'contrib-fundraising' ); ?> />
				Fundraising
			</label>

			<label>
				<input <?php checkbox_field( 'contrib-advertising' ); ?> />
				Advertising
			</label>

			<label>
				<input <?php checkbox_field( 'contrib-planning' ); ?> />
				Event Planning
			</label>

			<label>
				Other
				<input type="text" <?php text_field( 'contrib-other' ); ?> />
			</label>
		</fieldset>

		<fieldset class="column">
			<legend>When will you visit CIF?</legend>

			<?php Validate::error_messages( 'whenvisit', "Please tell us when you plan to visit CIF." ); ?>

			<label>
			<input <?php radio_field( 'whenvisit', 'Already' ); ?> />
				I already have
			</label>

			<label>
				<input <?php radio_field( 'whenvisit', 'No, this week' ); ?> />
				This week
			</label>

			<label>
				<input <?php radio_field( 'whenvisit', 'No, next weekend' ); ?> />
				Next weekend
			</label>

			<label>
				<input <?php radio_field( 'whenvisit', 'No, within the next month' ); ?> />
				Within the next month
			</label>
		</fieldset>

		<fieldset class="column">
			<legend>Would you like to live on floor?</legend>

			<?php Validate::error_messages( 'liveonfloor', "Please tell us how you feel about living with us on CIF." ); ?>

			<label>
				<input <?php radio_field( 'liveonfloor', 'Yes' ); ?> />
				Yes
			</label>

			<label>
				<input <?php radio_field( 'liveonfloor', 'No' ); ?> />
				No
			</label>

			<label>
				<input <?php radio_field( 'liveonfloor', 'Uncertain' ); ?> />
				Uncertain
			</label>
		</fieldset>
	</div>

	<div class="two-columns">
		<label class="column">
			Anything else you'd like to say?
			<textarea name="other-comments"><?php textarea_contents( 'other-comments' ); ?></textarea>
		</label>
	</div>

	<input type="submit" value="Apply for Membership" />
</form>
