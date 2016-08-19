<?php

/**
 * Functions hooked to WP actions
 * @see includes/fb-movies-hooks.php
 */

/**
 * Import data from Bioguiden
 * @see includes/class-fb-movies-import.php
 */

// Import info about movie
function fb_movies_save_post( $meta_id, $object_id, $meta_key, $meta_value ) {

	if ( get_post_type( $object_id ) == 'film' && $meta_key == 'filmnummer' ) :

		$Fb_Movies_Import = New Fb_Movies_Import();
		$Fb_Movies_Import->save_post( $object_id );

	endif;

}

// Adds error message if $_GET['bioguiden_error'] is true
function fb_movies_admin_notice() {

	if ( isset( $_GET['bioguiden_error'] ) && $_GET['bioguiden_error'] == true ) :

		Fb_Movies_Import::admin_notice();

	endif;

}

// Run the importer via cron twicedaily
function fb_movies_import_schedule() {

	$Fb_Movies_Import = New Fb_Movies_Import();
	$Fb_Movies_Import->fetch_film_screenings();

}

/**
 * Change post_status to publish when it's changed to future
 */
function fb_movies_publish_instead_of_future_post_status( $id, $post ) {

	wp_publish_post( $id );

}

/**
* Delete screenings connected to film when film is trashed
*/
function fb_movies_delete_connected_screenings( $post_id ) {

	if ( get_post_type( $post_id ) == 'film' ) :

		$screenings = new WP_Query( array(
			'post_type' => 'visning',
			'post_parent' => $post_id,
			'posts_per_page' => -1,
			'post_status' => 'any'
		) );

		if ( $screenings->have_posts() ) : while ( $screenings->have_posts() ) : $screenings->the_post();

			wp_delete_post( get_the_ID(), true );

		endwhile; endif;

	endif;

}

/**
 * Delete shows older than one month
 */
function fb_movies_delete_old_shows() {

	$shows = new WP_Query( array(
		'post_type' => 'visning',
		'posts_per_page' => -1,
		'post_status' => 'any',
		'date_query' => array( 'before' => '1 month ago' ),
	) );

	if ( $shows->have_posts() ) : while ( $shows->have_posts() ) : $shows->the_post();

		wp_delete_post( get_the_ID(), true );

	endwhile; endif; wp_reset_postdata();

}

/**
 * Saves ACF meta about screenings as the 'visning' post type
 */
function acf_screening_to_post_type_screening( $value, $post_id, $field ) {

	$query = new WP_Query( array(
		'post_type' => 'visning',
		'post_status' => 'any',
		'post_parent' => $post_id,
		'meta_key' => 'not_from_bioguiden',
		'meta_value' => true,
	) );

	if ( $query->have_posts() ) :
		while ( $query->have_posts() ) :
			$query->the_post();
			wp_delete_post( get_the_ID(), true );
		endwhile;
		wp_reset_postdata();
	endif;

	if ( have_rows( 'fb_visning', $post_id ) ) :
		while ( have_rows( 'fb_visning', $post_id ) ) : the_row();
			$start_time = get_sub_field( 'fb_visning_datum' );
			$booking_url = get_sub_field( 'fb_visning_booking_url' );

			if ( ! $start_time ) :
				continue;
			endif;

			if ( ! get_page_by_title( wp_strip_all_tags( $post_id . $start_time ), OBJECT, 'visning' ) ) :
				$post = wp_insert_post( array(
					'post_title' => wp_strip_all_tags( $post_id . $start_time ),
					'post_status' => 'future',
					'post_author' => 1,
					'post_type' => 'visning',
					'post_parent' => $post_id,
					'post_date' => $start_time,
				) );
				if ( $post ) :
					if ( $booking_url ) :
						update_post_meta( $post, 'booking_url', $booking_url );
					endif;
					update_post_meta( $post, 'not_from_bioguiden', true );
				endif;
			endif;

		endwhile;
	endif;

	return $value;

}
add_filter( 'acf/update_value/name=fb_visning', 'acf_screening_to_post_type_screening', 10, 3 );
