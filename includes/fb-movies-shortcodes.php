<?php

/**
 * Shortcode for showing a table of all upcoming screenings
 */
function fb_members_upcoming_screenings() {

	ob_start();

	$screenings = new WP_Query( [
		'post_type' => 'visning',
		'posts_per_page' => -1,
		'post_status' => [ 'publish', 'future' ],
		'order' => 'ASC',
		'date_query' => [
			[
				'after' => 'today',
			],
		],
	] );

	if ( $screenings->have_posts() ) :
		echo '<table class="table fb-movies-all-screenings">';
		echo '<thead>';
		echo '<tr>';
		echo '<td>Film</td>';
		echo '<td>Tid</td>';
		echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
		while ( $screenings->have_posts() ) : $screenings->the_post();
			echo '<tr>';
			echo '<td>' . get_the_date( 'j F H:i', get_the_ID() ) . '</td>';
			echo '<td><a href="' . esc_url( get_the_permalink( wp_get_post_parent_id( get_the_ID() ) ) ) . '">' . get_the_title( wp_get_post_parent_id( get_the_ID() ) ) . '</a></td>';
			echo '</tr>';
		endwhile;
		wp_reset_postdata();
		echo '</tbody>';
		echo '</table>';
	endif;

	return ob_get_clean();

}
add_shortcode( 'fb_coming_screenings', 'fb_members_upcoming_screenings' );
