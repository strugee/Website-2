<?php

/**
 * Contains the page heading and sidebar for the meeting minutes archive.
 * Also opens the two column layout container element.
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 *
 * @author Nate Hart (and future CIFers)
 */

?>
<h1>Meeting Minutes</h1>

<nav class="callout" role="navigation">
	<h3>Minutes from other semesters</h3>

	<?php
	
	semesterly_archive_menu( array( 'board_minutes', 'floor_minutes' ), array (
		'list_class' => 'secondary-menu',
		'link_base'  => 'minutes',
	) );
	
	?>
</nav>
<div class="two-columns">
