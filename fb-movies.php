<?php

/**
 * Plugin Name:       Folkets bios filmer
 * Plugin URI:        http://folketsbio.se
 * Description:       Funktionalitet för filmer.
 * Version:           1.0.0
 * Author:            Klandestino AB
 * Author URI:        http://klandestino.se
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * The code that runs during plugin activation.
 */
function activate_fb_movies() {

	// Register the film post_type to be able to flush the rewrite rules for it
	Fb_Movies_CPT::add_post_type_film();

	// Register all custom taxonomies to be able to flush rewrite rules for them
	fb_movies_taxonomies();

	// Flush the rewrite rules
	flush_rewrite_rules();

	// Schedule our cron
	wp_schedule_event( time(), 'twicedaily', 'fb_movies_cron' );

}
register_activation_hook( __FILE__, 'activate_fb_movies' );

/**
 * The code that runs during plugin deactivation.
 */
function deactivate_fb_movies() {

	// Flush the rewrite rules
	flush_rewrite_rules();

	// Deschedule our cron
	wp_clear_scheduled_hook( 'fb_movies_cron' );

}
register_deactivation_hook( __FILE__, 'deactivate_fb_movies' );

/**
 * Functions hooked to WP actions
 */
require plugin_dir_path( __FILE__ ) . 'includes/fb-movies-functions.php';

/**
 * Register all custom post types
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-fb-movies-cpt.php';

/**
 * Register all custom post types
 */
require plugin_dir_path( __FILE__ ) . 'includes/fb-movies-ct.php';

/**
 * Functionality for importing from Bioguiden
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-fb-movies-import.php';

/**
 * Add meta box
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-fb-movies-meta-box.php';

/**
 * Register ACF fields
 */
require plugin_dir_path( __FILE__ ) . 'includes/fb-movies-acf.php';

/**
 * Register all hooks
 */
require plugin_dir_path( __FILE__ ) . 'includes/fb-movies-hooks.php';
