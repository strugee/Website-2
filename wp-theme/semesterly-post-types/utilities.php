<?php

/**
 * Functions which may be useful in semestery post type templates.
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 */

/**
 * Outputs HTML markup for a list of links to the semesterly archives of the
 * specified post type.
 *
 * @TODO Option to allow "filling in" the space for semesters with no content.
 *
 * @param mixed $post_type Either a string of the name of the post type to output semesterly archive links for, or an array of strings listing the post types to output semesterly links for (useful for archives which display posts from multiple post types at once).
 * @param array $markup An array of options for the generated markup.
 *                      string list_tag The tag to use for the generated list. Defaults to 'ul'.
 *                      string list_class The classes to apply to the list element. This is added to the list_tag element. Defaults to ''.
 *                      string link_base A base for the generated archive urls. Defaults to the $post_type argument.
 * @param string $output_empty Whether to output a list item for semesters with no content. Either "output empty" or "skip empty". Defaults to "skip empty". Empty list items will have an "empty" class on them.
 */
function semesterly_archive_menu( $post_type, $markup = array(), $output_empty = 'skip empty' ) {
	// Default markup properties
	$default_markup = array(
		'list_tag'   => 'ul',
		'list_class' => '',
		'link_base'  => $post_type,
	);

	// Merge the given markup arguments with our defaults
	$markup = wp_parse_args( $markup, $default_markup );

	$output_empty = ($output_empty === 'output empty');


	// Get years with semesterly posts of the specified type
	$spring_years = get_years_with_semesterly_posts( $post_type, 'spring' );
	$fall_years = get_years_with_semesterly_posts( $post_type, 'fall' );

	// Number of years with posts for each semester
	$spring_count = count( $spring_years );
	$fall_count = count( $fall_years);

	
	// Do nothing if there are no posts for any years
	if ( ! $spring_count && ! $fall_count )
		return;

	
	// Determine the semester of the first year to loop from
	if ( ! $spring_count ) {
		// If there are no spring posts, the first semester is fall.
		$current_semester = 'fall';
	} else if ( ! $fall_count ) {
		// If there are no fall posts, the first semester is spring.
		$current_semester = 'spring';
	} else {
		// Otherwise, the first semester is fall if the most recent year has a fall semester,
		// or spring if it doesn't.
		$current_semester = ($spring_years[0] <= $fall_years[0]) ? 'fall' : 'spring';
	}

	// Determine the year to loop backwards from
	if ( $current_semester === 'fall' )
		$current_year = $fall_years[0];
	else
		$current_year = $spring_years[0];

	// Year array indices
	$spring_index = 0;
	$fall_index = 0;

	// Whether there are still semesters with posts
	$no_more_spring = ! $spring_count;
	$no_more_fall = ! $fall_count;


	// Opening list tag
	echo "<{$markup['list_tag']} class='{$markup['list_class']}'>";

	do {
		// Print a link for the current semester and year

		$class = ''; // Additional classes to add to the <li>
		if ( $current_year == get_query_var( 'year' ) && $current_semester == get_query_var( 'semester' ) )
			$class .= ' current-menu-item';

		echo "<li class='$class'>";

		echo '<a href="' . home_url( "{$markup['link_base']}/$current_semester/$current_year" ) . '">';
		echo ucfirst( $current_semester ) . " $current_year</a>";

		echo "</li>";


		// Determine the next semester and year to display a link for

		if ( $current_semester === 'spring' ) {
			$spring_index++; // Move on to the next spring year

			// Determine if that was the last spring semester with posts
			if ( $spring_index === $spring_count )
				$no_more_spring = true;

			// If there's no fall with posts between this spring and the next spring
			if ( $no_more_fall || (! $no_more_spring && $spring_years[$spring_index] > $fall_years[$fall_index]) ) {
				// Display the next spring semester
				$current_year = $spring_years[$spring_index];
			} else {
				// If there's a fall with posts between this spring and the next spring,
				// display the next fall semester.
				$current_semester = 'fall';
				$current_year = $fall_years[$fall_index];
			}
		} else {
			$fall_index++; // Move on to the next fall year

			// Determine if that was the last fall semester with posts
			if ( $fall_index === $fall_count )
				$no_more_fall = true;

			// If there's no fall with posts between this spring and the next spring
			if ( $no_more_fall || (! $no_more_spring && $spring_years[$spring_index] > $fall_years[$fall_index]) ) {
				// Display the next spring semester
				$current_semester = 'spring';
				$current_year = $spring_years[$spring_index];
			} else {
				// If there's a fall with posts between this spring and the next spring,
				// display the next fall semester.
				$current_year = $fall_years[$fall_index];
			}
		}
	// Continue looping until there are no more spring and fall semesters to display
	} while ( ! ($no_more_spring && $no_more_fall) );

	// Closing list tag
	echo "</{$markup['list_tag']}>";
}
