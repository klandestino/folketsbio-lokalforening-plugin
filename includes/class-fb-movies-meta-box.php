<?php

/**
 * Add a metabox on the post_type 'film'
 * to show all screenings
 */

class Fb_Movies_Meta_Box {

	public static function add_meta_box() {

		add_meta_box(
			'fb_movies_screenings_meta_box',
			'Visningar',
			array( 'Fb_Movies_Meta_Box', 'render_meta_box_content' ),
			'film',
			'normal',
			'low'
		);

	}

	public static function render_meta_box_content( $post ) {

		$screenings = new WP_Query( array(
			'post_type' => 'visning',
			'posts_per_page' => -1,
			'post_status' => array( 'publish', 'future' ),
			'post_parent' => $post->ID,
			'order' => 'ASC',
		) );

		if ( $screenings->have_posts() ) :

			echo '<ul>';

			while ( $screenings->have_posts() ) : $screenings->the_post();

				echo '<li>';
				the_time( 'j F, Y H:i' );
				echo '</li>';

			endwhile;

			echo '</ul>';

		else :

			echo 'Inga visningar inlagda i Bioguiden...';

		endif;

	}
}
