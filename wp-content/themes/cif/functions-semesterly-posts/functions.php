<?php

/**
 * Contains functions that are useful for semesterly post types.
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 *
 * @author Nate Hart (and future CIFers)
 */

require_once 'common.php';


/**
 * Returns an integer representation of the year from a timestamp string.
 *
 * The timestamp is expected to begin in the format Y-m-d. Most standard
 * timestamp formats begin this way.
 *
 * @param string $timestamp A timestamp begining in the format Y-m-d.
 * @return int The year represented by the timestamp.
 */
function get_year_from_timestamp( $timestamp ) {
	$date_components = explode( '-', $timestamp );

	return intval( $date_components[0] );
}


/**
 * Determines whether a date occured during a spring semester.
 *
 * The spring semester is considered to last from
 * January 1st until July 1st, inclusive.
 *
 * @param string $date The date.
 * @return boolean True if the date occured during a spring semester, false otherwise.
 */
function date_is_in_spring_semester( $date ) {
	$mid_year = strtotime( get_year_from_timestamp( $date ) . '-07-02' );
	$date_time = strtotime( $date );

	return $date_time < $mid_year;
}


/**
 * Determines whether a date occured during a fall semester.
 *
 * The fall semester is considered to last from
 * July 2nd until December 31st, inclusive.
 *
 * @param string $date The date.
 * @return boolean True if the date occured during a fall semester, false otherwise.
 */
function date_is_in_fall_semester( $date ) {
	return ! date_is_in_spring_semester( $date );
}


/**
 * Determines whether the given value is a valid semester name.
 *
 * Valid semester values are 'spring' and 'fall'.
 *
 * @param string $value The value to validate.
 * @return boolean True if the value is a valid semester name, false otherwise.
 */
function is_valid_semester( $value ) {
	$value = strtolower( $value );

	return ( 'spring' == $value || 'fall' == $value );
}





/**
 * Outputs HTML markup for a list of links to the semesterly archives of the
 * specified post type.
 *
 * @param mixed $post_type Either a string of the name of the post type to output semesterly archive links for, or an array of strings listing the post types to output semesterly links for (useful for archives which display posts from multiple post types at once).
 * @param array $markup An array of options for the generated markup.
 *                      string list_tag The tag to use for the generated list. Defaults to 'ul'.
 *                      string list_class The classes to apply to the list element. This is added to the list_tag element. Defaults to ''.
 *                      string link_base A base for the generated archive urls. Defaults to the $post_type argument.
 */
function semesterly_archive_menu( $post_type, $markup = array() ) {
	// Default markup properties
	$default_markup = array(
		'list_tag'   => 'ul',
		'list_class' => '',
		'link_base'  => $post_type,
	);

	// Merge the given markup array with our defaults
	$markup = wp_parse_args( $markup, $default_markup );


	// Get the latest post of the post type
	$latest_post = get_posts( array(
		'post_type'   => $post_type,
		'post_status' => 'publish',
		'orderby'     => 'post_date',
		'order'       => 'DESC',
		'numberposts' => 1,
	) );

	// Get the first post of the post type
	$first_post = get_posts( array(
		'post_type'   => $post_type,
		'post_status' => 'publish',
		'orderby'     => 'post_date',
		'order'       => 'ASC',
		'numberposts' => 1,
	) );

	// Do nothing if there are no posts
	if ( ! count( $first_post ) )
		return;

	// get_posts returns an array, so get the actual post objects
	$first_post = $first_post[0];
	$latest_post = $latest_post[0];

	// Start looping backwards from the latest post
	$current_semester_is_spring = date_is_in_spring_semester( $latest_post->post_date_gmt );
	$current_year = get_year_from_timestamp( $latest_post->post_date_gmt );
	$first_year = get_year_from_timestamp( $first_post->post_date_gmt );

	// When querying for posts, the time span is in terms of years. This variable
	// is used to determine whether the last query returned a result from the
	// spring or fall semester. This is used to ensure that the links are output
	// in the correct order, and that links are only output for semesters that
	// have posts.
	$queried_semester_is_spring = $current_semester_is_spring;

	echo '<' . $markup['list_tag'] . ' class="'. $markup['list_class'] . '">';
	do {
		// Output a link if the last query gave results for the current semester
		// Using === because $queried_semester_is_spring could be null
		if ( $queried_semester_is_spring === $current_semester_is_spring ) {
			$class = '';
			if ( $current_year == get_query_var( 'year' ) && $current_semester_is_spring == ( get_query_var( 'semester' ) == 'spring' ) )
				$class .= ' current-menu-item';
			echo "<li class='$class'>";

			if ( $current_semester_is_spring ) {
				echo '<a href="' . home_url( $markup['link_base'] . '/spring/' . $current_year ) . '">';
				echo 'Spring ' . $current_year . '</a>';
			} else {
				echo '<a href="' . home_url( $markup['link_base'] . '/fall/' . $current_year ) . '">';
				echo 'Fall ' . $current_year . '</a>';
			}

			echo '</li>';
		}

		// Change the current semester
		$current_semester_is_spring = ! $current_semester_is_spring;

		// Change to the previous year if we went from a spring to fall semester
		if ( ! $current_semester_is_spring )
			$current_year--;

		// Query if there were any posts for the current semester and year.
		// This queries for posts by year, ordering the results ascending if we're
		// looking for a spring post, and descending if we're looking for a fall
		// post. Only a single post is returned by the query. If that post is from
		// the semester we're looking for, there was a post for that semester.
		// Otherwise, there are no posts for that semester of the year and the 
		// returned result is for a different semester than we want.
		$queried_post = get_posts( array(
			'post_type'   => $post_type,
			'post_status' => 'publish',
			'orderby'     => 'post_date',
			'order'       => ( $current_semester_is_spring ? 'ASC' : 'DESC' ),
			'year'        => $current_year,
			'numberposts' => 1,
		) );

		if ( ! count( $queried_post ) ) {
			$queried_semester_is_spring = null;
		} else {
			// Results were found, but we're not sure what semester they're for
			$queried_post = $queried_post[0];
			$queried_semester_is_spring = date_is_in_spring_semester( $queried_post->post_date_gmt );
		}
	
	// Continue finding semesters with posts until we've processed all years after the first year
	} while ( $current_year >= $first_year );

	echo '</' . $markup['list_tag'] . '>';
}
