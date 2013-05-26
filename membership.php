<?php

session_start();

$dest_email_addr = 'board@cif.rochester.edu';

if (isSet($_POST['submission'])) {

  $subject = 'CIF Membership Application for '.$_POST['name'];

  // Old reporting system. Just lists every field and its contents.
  /*	foreach ($_POST as $key=>$value) {
   if ($key == 'submission') {
   continue;
   }
   $body .= "$key: $value\n";
   }
  */

  // New reporting system. Lists fields in a friendly, human-readable format

  // Save all field values, just in case the user's browser doesn't
  foreach($_POST as $key=>$value) {
    if ($key != 'submission') {
      $_SESSION[$key] = $value;
    }
  }

  // Make sure important fields are actually filled in
  if (empty($_POST['name']) ||
      empty($_POST['netid']) ||
      empty($_POST['email']) ||
      empty($_POST['year']) ||
      empty($_POST['howhear']) ||
      empty($_POST['interest']) ||
      empty($_POST['whenvisit']) ||
      empty($_POST['livingonfloor'])) {

    ?>
    <html>
      <head>
      <title>Error</title>
      <link rel="stylesheet" href="membership.css" />
      </head>
      <body>
    <p>Please <a href="#" onClick="history.go(-1)">go back</a> and fill in all fields.</p>
      <body>
      </html>
      <?php
    } else {

    $rows = array(
		  $_POST['name']." (".$_POST['netid'].")",
		  $_POST['email'],
		  "Class of ".$_POST['year']."\n",
		  "Major: ".$_POST['major'],
		  "Residence: ".$_POST['residence'],
		  "Heard about CIF: ".$_POST['howhear'],
		  "Interest in CIF: ".$_POST['interest'],
		  "On-floor help:\n".
		  (!empty($_POST['techstaff'])?"\tTech staff\n":"").
		  (!empty($_POST['helpatcif'])?"\thelp@cif\n":"").
		  (!empty($_POST['fundraising'])?"\tFundraising\n":"").
		  (!empty($_POST['advertising'])?"\tAdvertising\n":"").
		  (!empty($_POST['planning'])?"\tEvent planning\n":"").
		  (!empty($_POST['otherhelpdetail'])?"\tOther: ".$_POST['otherhelpdetail']:""),
		  "Visited: ".
		  ($_POST['whenvisit']=="already"?"Already":
		   ($_POST['whenvisit']=="thisweek"?"No, this week":
		    ($_POST['whenvisit']=="nextweekend"?"No, next weekend":
		     ($_POST['whenvisit']=="nextmonth"?"No, next month":"")))),
		  "Living on floor: ".$_POST['livingonfloor'],
		  "Extras: ".$_POST['othercomments']);
	
    $body = implode("\n", $rows);

    if (!mail($dest_email_addr,$subject,$body,'From: "Membership Application" <root@web1.cif.rochester.edu>')) {
      echo 'MAIL FAILURE: please contact board@cif.rochester.edu for assistance.';
    } else {

      // Clear the temporary field data
      foreach($_POST as $key=>$value) {
	unset($_SESSION[$key]);
      }
    ?>

    <html>
       <head>
       <title>Thank You!</title>
       <link rel="stylesheet" href="membership.css" />
       </head>
       <body>

       Thanks for submitting your application to CIF! You should hear from the Executive Board soon. In the mean time, come and visit us up on Anderson 3!
			       
			       </body></html>
			       
			       <?php
			       }
  }
 } else {
  ?>

  <html>
    <head>
    <title>CIF Membership Application</title>
    <link rel="stylesheet" href="membership.css" />
      </head>

      <body>
		
      <form method="POST">
      <input type="hidden" name="submission" value="1" />

      <table>
      <tr>
      <td class="label">
      <label for="name">Name: </label>
			  </td>
			  <td>
			  <input type="text" name="name" id="name" value="<?php echo $_SESSION['name'];?>" />
				</td>
			</tr>
			<tr>
				<td class="label">
					<label for="year">Year: </label>
				</td>
				<td>
					<input type="text" name="year" id="year" value="<?php echo $_SESSION['year'];?>" />
				</td>
			</tr>
			<tr>
				<td class="label">
					<label for="netid">NetID: </label>
				</td>
				<td>
					<input type="text" name="netid" id="netid" value="<?php echo $_SESSION['netid'];?>" />
				</td>
			</tr>
			<tr>
				<td class="label">
					<label for="residence">Current residence: </label>
				</td>
				<td>
					<input type="text" name="residence" id="residence" value="<?php echo $_SESSION['residence'];?>" />
				</td>
			</tr>
			<tr>
				<td class="label">
					<label for="email">Email: </label>
				</td>
				<td>
					<input type="text" name="email" id="email" value="<?php echo $_SESSION['email'];?>" />
				</td>
			</tr>
			<tr>
				<td class="label">
					<label for="major">Major: </label>
				</td>
				<td>
					<input type="text" name="major" id="major" value="<?php echo $_SESSION['major'];?>" />
				</td>
			</tr>
			<tr>
				<td class="label">
					<label for="howhear">How did you hear about CIF? </label>
				</td>
				<td>
    <textarea name="howhear" id="howhear" rows="4" cols="40"><?php echo $_SESSION['howhear'];?></textarea>
				</td>
			</tr>
			<tr>
				<td class="label">
					<label for="interest">Why do you want to be a CIF member? </label>
				</td>
				<td>
    <textarea name="interest" id="interest" rows="4" cols="40"><?php echo $_SESSION['interest'];?></textarea>
				</td>
			</tr>
			<tr>
				<td class="label">
					How can you contribute to CIF?
				</td>
				<td>
    <input class="check" type="checkbox" name="techstaff" id="techstaff" <?php echo ($_SESSION['techstaff']?"checked":"");?> /> 
						<label for="techstaff">Tech Staff</label><br />
					<input class="check" type="checkbox" name="helpatcif" id="helpatcif" <?php echo ($_SESSION['helpatcif']?"checked":"");?> /> 
						<label for="helpatcif">Help@CIF</label><br />
					<input class="check" type="checkbox" name="fundraising" id="fundraising" <?php echo ($_SESSION['fundraising']?"checked":"");?> /> 
						<label for="fundraising">Fundraising</label><br />
					<input class="check" type="checkbox" name="advertising" id="advertising" <?php echo ($_SESSION['advertising']?"checked":"");?> /> 
						<label for="advertising">Advertising</label><br />
					<input class="check" type="checkbox" name="planning" id="planning" <?php echo ($_SESSION['planning']?"checked":"");?> /> 
						<label for="planning">Event Planning</label><br />
					<input class="check" type="checkbox" name="otherhelp" id="otherhelp" <?php echo ($_SESSION['otherhelp']?"checked":"");?> /> 
						<label for="otherhelp">Other:</label><br/>
						<input name="otherhelpdetail" /value="<?php echo $_SESSION['otherhelpdetail'];?>" />
				</td>
			</tr>
			<tr>
				<td class="label">
					When will you visit CIF?
				</td>
				<td>
					<input class="check" type="radio" name="whenvisit" id="already" value="already" /> 
					<label for="already">I already have</label><br />
					<input class="check" type="radio" name="whenvisit" id="thisweek" value="thisweek" /> 
					<label for="thisweek">This week</label><br />
					<input class="check" type="radio" name="whenvisit" id="nextweekend" value="nextweekend" /> 
					<label for="nextweekend">Next weekend</label><br />
					<input class="check" type="radio" name="whenvisit" id="thismonth" value="thismonth" /> 
					<label for="thismonth">This month</label>
				</td>
			</tr>
			<tr>
				<td class="label">
					Would you like to live on floor?
				</td>
				<td>
					<input class="check" type="radio" name="livingonfloor" id="flooryes" value="yes" /> 
					<label for="flooryes">Yes</label><br />
					<input class="check" type="radio" name="livingonfloor" id="floorno" value="no" /> 
					<label for="floorno">No</label><br />
					<input class="check" type="radio" name="livingonfloor" id="floormaybe" value="uncertain" /> 
					<label for="floormaybe">Uncertain</label>
				</td>
			</tr>
			<tr>
				<td class="label">
					<label for="othercomments">Anything else you wish to say?</label>
				</td>
				<td>
										   <textarea name="othercomments" id="othercomments" rows="8" cols="40"><?php echo $_SESSION['othercomments'];?></textarea>
				</td>
			</tr>
			<tr>
				<td></td>
				<td>
					<input id="submit" type="submit" value="Submit Application"/>
				</td>
			</tr>
		</table>
	</form>
</body>
</html>
<?php } ?>
