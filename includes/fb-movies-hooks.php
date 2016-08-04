<?php

/**
 * Register all hooks for the plugin
 */

/**
 * Register Custom Post Types
 * @see includes/class-fb-movies-cpt.php
 */

// Add post type film
add_action( 'init', array( 'Fb_Movies_CPT', 'add_post_type_film' ) );
// Add post type visning
add_action( 'init', array( 'Fb_Movies_CPT', 'add_post_type_visning' ) );

/**
 * Register Custom Taxonomies
 */
add_action( 'init', 'fb_movies_taxonomies' );

/**
 * Import data from Bioguiden
 * @see includes/class-fb-movies-import.php
 */

// Import info about movie
add_action( 'added_post_meta', 'fb_movies_save_post', 999, 4 );

// Adds error message if $_GET['bioguiden_error'] is true
add_action( 'admin_notices', 'fb_movies_admin_notice' );

// Adds our error message to the list of $removable_query_args
add_filter( 'removable_query_args', array( 'Fb_Movies_Import', 'remove_error_query_arg' ), 10, 1 );

// Run the importer via cron twicedaily
add_action( 'fb_movies_cron', 'fb_movies_import_schedule' );

/**
 * Register meta box
 */
add_action( 'add_meta_boxes_film', array( 'Fb_Movies_Meta_Box', 'add_meta_box' ) );

/**
 * Remove ACF menu
 */
add_action( 'admin_menu', 'fb_movies_remove_menu_items', 9999 );

/**
 * Change post_status to publish when it's changed to future
 */
add_action( 'future_film', 'fb_movies_publish_instead_of_future_post_status', 10, 2 );

/**
* Delete screenings connected to film when film is trashed
*/
add_action( 'wp_trash_post', 'fb_movies_delete_connected_screenings', 10, 1 );

/**
 * Delete shows older than one month
 */
add_action( 'wp_version_check', 'fb_movies_delete_old_shows' );
