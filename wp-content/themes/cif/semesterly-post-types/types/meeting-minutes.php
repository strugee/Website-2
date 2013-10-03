<?php

/**
 * Creates the Board and Floor Minutes post types.
 * This file also registers functions for filtering these
 * post types' archives by semester.
 *
 * This file also hooks a function to set any menu item with the title
 * "Meeting Minutes" as the current menu item on the minutes archive page.
 *
 * The combined Board and Floor Minutes archive can be found at
 * {home_url}/minutes
 * (This assumes that permalinks are "pretty" and set to be by post name)
 *
 * The Board Minutes post type is registered as "board_minutes".
 * The Floor Minutes post type is registered as "floor_minutes".
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 */

/**
 * Registers the Board and Floor Minutes post types.
 */
function register_minutes_post_type() {
	/**
	 * Register a custom post type for board meeting minutes.
	 * See http://codex.wordpress.org/Function_Reference/register_post_type
	 * for more information on how this works.
	 */
	$board_minute_labels = array(
		'name'				 => 'Board Minutes',
		'singular_name'		 => 'Board Minutes',
		'add_new_item'		 => 'Add New Board Minutes',
		'edit_item'			 => 'Edit Board Minutes',
		'new_item'			 => 'New Board Minutes',
		'view_item'			 => 'View Board Minutes',
		'search_items'		 => 'Search Board Minutes',
		'not_found'			 => 'No board minutes found.',
		'not_found_in_trash' => 'No board minutes found in Trash.',
	);
	
	$board_minute_args = array(
		'labels'		=> $board_minute_labels,
		'description'	=> 'Minutes from CIF board meetings.',
		'public'		=> true,
		'menu_position'	=> 5, // Appears below Posts in the admin sidebar
		'has_archive'	=> true,
	);
	
	register_post_type( 'board_minutes', $board_minute_args );
	
	
	
	/**
	 * Register a custom post type for floor meeting minutes.
	 * See http://codex.wordpress.org/Function_Reference/register_post_type
	 * for more information on how this works.
	 */
	$floor_minute_labels = array(
		'name'				 => 'Floor Minutes',
		'singular_name'		 => 'Floor Minutes',
		'add_new_item'		 => 'Add New Floor Minutes',
		'edit_item'			 => 'Edit Floor Minutes',
		'new_item'			 => 'New Floor Minutes',
		'view_item'			 => 'View Floor Minutes',
		'search_items'		 => 'Search Floor Minutes',
		'not_found'			 => 'No floor minutes found.',
		'not_found_in_trash' => 'No floor minutes found in Trash.',
	);
	
	$floor_minute_args = array(
		'labels'		=> $floor_minute_labels,
		'description'	=> 'Minutes from CIF floor meetings.',
		'public'		=> true,
		'menu_position'	=> 5, // Appears below Posts in the admin sidebar
		'has_archive'	=> true,
	);
	
	register_post_type( 'floor_minutes', $floor_minute_args );
}
add_action( 'init', 'register_minutes_post_type' );



/**
 * Generates the post title for meeting minutes posts.
 *
 * The generated post title is in the format "Board/Floor Minutes for {date}".
 *
 * This function is called just before post data is saved because the
 * time of publication is required for generating the meeting minute titles.
 *
 * @param $post_id The id of the post which was just saved.
 */
function generate_minutes_post_title( $post_id ) {
	global $wpdb;
	
	$post_type = get_post_type( $post_id );

	// Only generate the title for board/floor minutes
	if ( ! ( 'board_minutes' == $post_type || 'floor_minutes' == $post_type ) )
		return;
	
	// Get a human readable meeting date string (Month dd{st/nd/rd/th}, YYYY)
	$meeting_date_string = get_the_time( 'F jS, Y', $post_id );
	
	// Create the new title in the format "Board/Floor Minutes for {date}"
	if ( 'board_minutes' == $post_type )
		$new_title = 'Board';
	else if ( 'floor_minutes' == $post_type )
		$new_title = 'Floor';

	$new_title .= ' Minutes for ' . $meeting_date_string;
	
	// Update the title and slug of the post
	$wpdb->update(
		// Table to UPDATE
		$wpdb->posts,
		// Data to UPDATE
		array(
			'post_title' => $new_title,
			/**
			 * Update the post slug (URL-friendly representation of the title)
			 * sanitize_title() converts non-Latin characters to Latin lookalikes
			 * and adds hyphens in place of spaces, amongst other things.
			 *
			 * post_name is the name of the field in the database where
			 * the post slugs are stored.
			 */
			'post_name' => sanitize_title( $meeting_date_string ),
		),
		// WHERE clause for the UPDATE query
		array( 'ID' => $post_id )
	);
}
add_action( 'save_post', 'generate_minutes_post_title' );



/**
 * Modifies the database query on the combined Board/Floor Minutes archive page to
 * return results on a semesterly basis.
 *
 * This function is a wrapper for modify_semesterly_archives_query() which
 * specifies the post type as "board_minutes" and "floor_minutes".
 *
 * @param WP_Query $query The WordPress query object for the current query.
 */
function modify_minutes_semesterly_archives_query( $query ) {
	return modify_semesterly_archives_query( $query, array( 'board_minutes', 'floor_minutes' ) );
}
// Don't apply this filter on admin pages
if ( ! is_admin() )
	add_filter( 'pre_get_posts', 'modify_minutes_semesterly_archives_query' );



/**
 * Filters the HTML title on the combined minutes archive page to always display
 * as "{Semester} {Year} Meeting Minutes".
 *
 * Without this filter the archives may simply display the year as the title.
 *
 * @param string $title Title of the page.
 * @param string $sep (optional) How to separate the various items within the page title. Default is '»'.
 * @param string $seplocation (optional) Direction to display title, 'right'.
 */
function minutes_semesterly_archive_title_filter( $title, $sep = '&raquo;', $seplocation = 'right' ) {
	$title = semesterly_archive_title_filter( $title, 'floor_minutes', 'Meeting Minutes', $sep, $seplocation );
	return semesterly_archive_title_filter( $title, 'board_minutes', 'Meeting Minutes', $sep, $seplocation );
}
add_filter( 'wp_title', 'minutes_semesterly_archive_title_filter', 10, 3 );



/**
 * Allows WordPress to recognize the semesterly URL structure of the
 * combined Floor and Board Minutes post types' archive page.
 *
 * All URL rewrites are documented inside this function's contents.
 *
 * When changing these rewrite rules, the .htaccess file will need to be updated.
 * This can be done by clicking the "Save" button on the Permalinks settings page
 * in WordPress, or by calling flush_rewrite_rules(), which should only
 * be called once. Do NOT make a call to flush_rewrite_rules() on every page load!
 * It's unnecessary and impacts performance.
 *
 * @param WP_Rewrite $wp_rewrite The global WP_Rewrite instance for managing rewrite rules.
 */
function add_minutes_url_rewrite_rules( $wp_rewrite ) {
	$wp_rewrite->rules = array(

		/**
		 * Meeting minutes archive URL structure.
		 * Shows minutes for the latest semester.
		 */
		'minutes/?$' => $wp_rewrite->index . '?post_type=board_minutes',

		/**
		 * Meeting minutes archive URL structure.
		 * minutes/{string}/{year}/
		 * Example: minutes/spring/2013/
		 */
		'minutes/?([^/]*)/([0-9]{4})/?$' => $wp_rewrite->index . '?post_type=board_minutes&semester=' . $wp_rewrite->preg_index(1) . '&year=' . $wp_rewrite->preg_index(2),

	) + $wp_rewrite->rules;
}
add_filter( 'generate_rewrite_rules', 'add_minutes_url_rewrite_rules' );




/**
 * Applies the current-menu-item class to any menu item with
 * the title "Meeting Minutes" in a WordPress menu if the current page
 * is a meeting minutes archive.
 *
 * @param array $class The classes to apply to the menu item.
 * @param object $menu_item The menu item object.
 * @return array An array of classes to apply to the menu item.
 */
function add_minutes_current_menu_item_class( $classes = array(), $menu_item = false ) {
    if ( 'board_minutes' == get_post_type_outside_loop() && 'Meeting Minutes' == $menu_item->title && ! in_array( 'current-menu-item', $classes ) )
        $classes[] = 'current-menu-item';
    
    return $classes;
}
add_filter( 'nav_menu_css_class', 'add_minutes_current_menu_item_class', 10, 2 );
