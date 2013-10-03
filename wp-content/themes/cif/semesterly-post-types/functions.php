<?php

/**
 * Contains functions that are useful for semesterly post types.
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 */


require_once 'common.php';
require_once 'utilities.php';


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
 * Returns an array of years which have posts of the specified type from the specified semester.
 * The years will be in descending order.
 *
 * The spring semester is considered to last from
 * January 1st until July 1st, inclusive.
 *
 * The fall semester is considered to last from
 * July 2nd until December 31st, inclusive.
 *
 * @param mixed $post_type Either a string or an array of strings.
 * @param string $semester Either "spring" or "fall". Defaults to "spring".
 * @return array An array of years which have posts of the specified type from the specified semester, in descending order.
 */
function get_years_with_semesterly_posts( $post_type, $semester = 'spring' ) {
	global $wpdb; // We'll need this to perform a custom database query

	// Normalize the semester value
	$semester = strtolower( $semester );
	if ( ! is_valid_semester( $semester ) )
		$semester = 'spring'; // Default to "spring" if invalid

	// Create a SQL array from the post types given
	if ( is_array( $post_type ) )
		$post_types = '"' . implode( '","' , $post_type ) . '"';
	else
		$post_types = '"' . $post_type . '"';


	// Form the query
	$query  = "SELECT YEAR(post_date) AS `year` FROM $wpdb->posts ";
	$query .= "WHERE post_type IN ($post_types) AND post_status='publish' ";

	if ( $semester === 'spring' )
		$query .= "AND DATEDIFF(DATE(post_date), CONCAT(YEAR(post_date), '-07-02')) < 0 ";
	else
		$query .= "AND DATEDIFF(DATE(post_date), CONCAT(YEAR(post_date), '-07-02')) >= 0 ";

	$query .= "GROUP BY YEAR(post_date) ORDER BY post_date DESC";

	
	// Prepare to cache the query
	$query_hash = md5( $query );
	$cache_key = "cif_{$semester}_years";
	$cache = wp_cache_get( $cache_key , 'general');

	// Store the results in the cache if they haven't been already
	if ( ! isset( $cache[ $query_hash ] ) ) {
		// Query the database
		$results = $wpdb->get_results( $query );

		// Get the results as an array of integers.
		// get_results returns an array of objects with a year property, so we're using
		// an anonymous function in array_map to convert it to an array of ints.
		$results = array_map( function($obj) { return (int) $obj->year; }, $results );

		// Cache our processed database results
		$cache[ $query_hash ] = $results;
		wp_cache_set( $cache_key, $cache, 'general' );
	}
	
	// Return the query results
	return $cache[ $query_hash ];
}
