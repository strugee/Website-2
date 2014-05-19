<?php

/**
 * Contains functions that provide common functionality for semesterly post types.
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 */

/**
 * Creates an SQL WHERE clause for retriving posts that occurred during a
 * spring semester. The year of the spring semester comes from calling
 * get_query_var( 'year' ).
 *
 * This function is intended to be used with the posts_where filter.
 * Usage: add_filter( 'posts_where', 'filter_posts_by_spring' );
 *
 * A post is deemed to be from the spring semester if it occured between
 * January 1st and July 1st, inclusive.
 *
 * @param string $where The WHERE clause for the current query.
 * @return string A WHERE clause which limits the post date to the spring semester.
 */
function filter_posts_by_spring( $where ) {
	global $wpdb;

	// Escape the year query var for safe use in SQL
	$year = $wpdb->escape( get_query_var( 'year' ) );
	$where .= " AND post_date >= '$year-01-01'";
	$where .= " AND post_date <= '$year-07-01'";
	
	return $where;
}

/**
 * Creates an SQL WHERE clause for retriving posts that occurred during a
 * fall semester. The year of the fall semester comes from calling
 * get_query_var( 'year' ).
 *
 * This function is intended to be used with the posts_where filter.
 * Usage: add_filter( 'posts_where', 'filter_posts_by_fall' );
 *
 * A post is deemed to be from the fall semester if it occured between
 * July 2nd and December 31st, inclusive.
 *
 * @param string $where The WHERE clause for the current query.
 * @return string A WHERE clause which limits the post date to the fall semester.
 */
function filter_posts_by_fall( $where ) {
	global $wpdb;

	// Escape the year query var for safe use in SQL
	$year = $wpdb->escape( get_query_var( 'year' ) );
	$where .= " AND post_date >= '$year-07-02'";
	$where .= " AND post_date <= '$year-12-31'";

	return $where;
}



/**
 * Modifies the main WordPress query to retrieve posts of the specified post type
 * by semester on their archives pages.
 *
 * The semester is specified by setting the "semester" and "year" query variables.
 * This can be done through URL rewrites or explicitely by calling
 * $wp_query->set( 'year', 2013 ); on the global WordPress query object $wp_query.
 *
 * If the semester and year are not specified, the semester of the latest post for
 * the post type is used.
 *
 * This function is intended for use with the pre_get_posts filter but should
 * not be added as a filter directly. Instead, a wrapper function should be
 * created which accepts $query and makes a call to this function specifying
 * the $post_type argument. The wrapper function should then be added as a filter
 * by calling
 * add_filter( 'pre_get_posts', 'my_modify_semesterly_archives_query_wrapper' );
 *
 * Because this is a filter, don't forget to return the results of the call to
 * this function from your wrapper!
 *
 * @param WP_Query $query The WordPress query object for the current query.
 * @param string $post_type The name of the post type to limit results by semester for.
 */
function modify_semesterly_archives_query( $query, $post_type ) {
	if ( is_array( $post_type ) )
		$is_post_type = in_array( $query->query['post_type'], $post_type);
	else
		$is_post_type = $post_type == $query->query['post_type'];
	
	// If this is the main query and it's the post type's archive
	$is_post_type = ( isset( $query->query['post_type'] ) && $is_post_type );
	if ( $is_post_type && $query->is_post_type_archive && $query->is_main_query() ) {
		if ( isset( $query->query_vars['semester'] ) )
			$semester = strtolower( $query->query_vars['semester'] );
		else
			$semester = '';

		if ( $semester ) {
			// Make the semester query var lowercase if it was specified
			$query->set( 'semester', $semester );
		} else {
			// Otherwise, use the latest semester with content for this post type
			
			// Get the lastest post
			$latest_post = get_posts( array(
				'post_type'   => $post_type,
				'post_status' => 'publish',
				'orderby'     => 'post_date',
				'order'       => 'DESC',
				'numberposts' => 1,
			) );

			// Use the semester of the latest post, if a post was found
			if ( count( $latest_post ) ) {
				// get_posts always returns an array, even if we asked for 1 post
				$latest_post = $latest_post[0];
				$latest_year = get_year_from_timestamp( $latest_post->post_date_gmt );

				$mid_year = strtotime( $latest_year . '-07-02' );
				$latest_date_time = strtotime( $latest_post->post_date_gmt );

				$semester = ( $latest_date_time < $mid_year ) ? 'spring' : 'fall';

				$query->set( 'semester', $semester );
				$query->set( 'year', $latest_year );
			// Otherwise, use the semester of the current date
			} else {
				$mid_year = strtotime( date( 'Y' ) . '-07-02' );
				$today = strtotime( date( 'Y-m-d' ) );

				$semester = ( $today < $mid_year ) ? 'spring' : 'fall';

				$query->set( 'semester', $semester );
				$query->set( 'year', date( 'Y' ) );
			}
		}

		if ( is_valid_semester( $semester ) ) {
			// Limit the results from the database to the current semester
			if ( 'spring' == $semester )
				add_filter( 'posts_where', 'filter_posts_by_spring' );
			else
				add_filter( 'posts_where', 'filter_posts_by_fall' );
		} else {
			// If semester value was invalid, return a 404 status
			$query->set_404();
			status_header( 404 );
		}
	}
	
	return $query;
}



/**
 * Filters the page title (<title> element) on a semesterly archive page to
 * always display as "{Semester} {Year} $post_type_title". Without this filter
 * the archives may simply display the year as the title.
 *
 * This method is intended to be used with the wp_title hook. As such, the $title,
 * $sep, and $seplocation parameters are to maintain consistency with wp_title().
 *
 * This function should not be added as a filter directly. Instead, a wrapper
 * function should be created with the signature
 * ( $title, $sep = '&raquo;', $seplocation = 'right' ) that makes a call to
 * this function specifying $post_type and $post_type_title. The wrapper function
 * should then be added as a filter by calling
 * add_filter( 'wp_title', 'my_semesterly_archive_title_filter_wrapper', 10, 3);
 *
 * Because this is a filter, don't forget to return the results of the call to
 * this function from your wrapper!
 *
 * @param string $title Title of the page.
 * @param mixed $post_type A string or array of strings specifying which post types this filter should apply to.
 * @param string $post_type_title The text to display in the title for the post type. See the documentation for this function on how the title is formatted for a better idea of where this will appear.
 * @param string $sep (optional) How to separate the various items within the page title. Default is '»'.
 * @param string $seplocation (optional) Direction to display title, 'right'.
 */
function semesterly_archive_title_filter( $title, $post_type, $post_type_title, $sep = '&raquo;', $seplocation = 'right' ) {
	global $wp_query;

	// Only filter the title if this is the archive page for the post type
	if ( $wp_query->query['post_type'] == $post_type && $wp_query->is_post_type_archive ) {
		// Set the title as "{semester} {year} $post_type_title"
		$semester = ucfirst( get_query_var( 'semester' ) );
		$year = get_query_var( 'year' );
		$title = "$semester $year $post_type_title";

		// Return the separator on the correct side
		if ( 'right' == $seplocation )
			return "$title $sep ";
		return " $sep $title";
	}

	// The title will be set to the value returned by this filter,
	// so we must return the title even if we did nothing to it.
	return $title;
}
