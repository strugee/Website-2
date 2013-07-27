<?php

/**
 * Creates the Announcements post type.
 * This file also registers functions for filtering this
 * post type's archives by semester.
 *
 * This file also hooks a function to set any menu item with the title
 * "Announcements" as the current menu item on the announcements archive page.
 *
 * The Announcements archive can be found at
 * {home_url}/announcements
 * (This assumes that permalinks are "pretty" and set
 * to be by post name)
 *
 * The Announcements post type is registered as "announcements".
 *
 * This file follows the coding standards detailed here:
 * http://codex.wordpress.org/WordPress_Coding_Standards
 *
 * @author Nate Hart (and future CIFers)
 */

require_once 'common.php';


/**
 * Registers the Announcements post type.
 */
function register_announcements_post_type() {
	/**
	 * Register a custom post type for announcements.
	 * See http://codex.wordpress.org/Function_Reference/register_post_type
	 * for more information on how this works.
	 */
	$announcement_labels = array(
		'name'				 => 'Announcements',
		'singular_name'		 => 'Announcement',
		'add_new_item'		 => 'Add New Announcements',
		'edit_item'			 => 'Edit Announcements',
		'new_item'			 => 'New Announcements',
		'view_item'			 => 'View Announcements',
		'search_items'		 => 'Search Announcements',
		'not_found'			 => 'No announcements found.',
		'not_found_in_trash' => 'No announcements found in Trash.',
	);
	
	$announcement_args = array(
		'labels'		=> $announcement_labels,
		'description'	=> 'Announcements of upcoming CIF events and CIF news.',
		'public'		=> true,
		'menu_position'	=> 5, // Appears below Posts in the admin sidebar
		'has_archive'	=> true,
	);
	
	register_post_type( 'announcements', $announcement_args );
}
add_action( 'init', 'register_announcements_post_type' );



/**
 * Modifies the database query on the Announcements archive page to
 * return results on a semesterly basis.
 *
 * This function is a wrapper for modify_semesterly_archives_query() which
 * specifies the post type as "announcements".
 *
 * @param WP_Query $query The WordPress query object for the current query.
 */
function modify_announcements_semesterly_archives_query( $query ) {
	return modify_semesterly_archives_query( $query, 'announcements' );
}
// Don't apply this filter on admin pages
if ( ! is_admin() )
	add_filter( 'pre_get_posts', 'modify_announcements_semesterly_archives_query' );



/**
 * Filters the HTML title on the announcements page to always display as
 * "{Semester} {Year} Announcements".
 *
 * Without this filter the archives may simply display the year as the title.
 *
 * @param string $title Title of the page.
 * @param string $sep (optional) How to separate the various items within the page title. Default is '»'.
 * @param string $seplocation (optional) Direction to display title, 'right'.
 */
function announcements_semesterly_archive_title_filter( $title, $sep = '&raquo;', $seplocation = 'right' ) {
	return semesterly_archive_title_filter( $title, 'announcements', 'Announcements', $sep, $seplocation );
}
add_filter( 'wp_title', 'announcements_semesterly_archive_title_filter', 10, 3 );



/**
 * Allows WordPress to recognize the semesterly URL structure of the
 * Announcements post type's archive page.
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
function add_announcements_url_rewrite_rules( $wp_rewrite ) {
	$wp_rewrite->rules = array(

		/**
		 * Announcements archive URL structure.
		 * announcements/{string}/{year}/
		 * Example: announcements/spring/2013/
		 */
		'announcements/?([^/]*)/([0-9]{4})/?$' => $wp_rewrite->index . '?post_type=announcements&semester=' . $wp_rewrite->preg_index(1) . '&year=' . $wp_rewrite->preg_index(2),

	) + $wp_rewrite->rules;
}
add_filter( 'generate_rewrite_rules', 'add_announcements_url_rewrite_rules' );



/**
 * Applies the current-menu-item class to any menu item with the title "Announcements"
 * in a WordPress menu if the current page is an announcements archive.
 *
 * @param array $class The classes to apply to the menu item.
 * @param object $menu_item The menu item object.
 * @return array An array of classes to apply to the menu item.
 */
function add_announcements_current_menu_item_class( $classes = array(), $menu_item = false ) {
    if ( 'announcements' == get_post_type_outside_loop() && 'Announcements' == $menu_item->title && ! in_array( 'current-menu-item', $classes ) )
        $classes[] = 'current-menu-item';
    
    return $classes;
}
add_filter( 'nav_menu_css_class', 'add_announcements_current_menu_item_class', 10, 2 );
