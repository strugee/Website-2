<?php

/**
 * Page heading and sidebar for the announcements archive.
 * Also opens the .articles container element.
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 */

?>
<h1><?php echo ucfirst( get_query_var( 'semester' ) ) . ' ' . get_query_var( 'year' ); ?> Announcements</h1>

<nav class="sidebar" role="navigation">
	<h3>Announcements from other semesters</h3>

	<?php

	semesterly_archive_menu( 'announcements', array(
		'list_class' => 'grid-aligned secondary-menu',
	) );

	?>
</nav>

<div class="articles">
