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
