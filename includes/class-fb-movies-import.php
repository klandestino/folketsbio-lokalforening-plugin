<?php

/**
 * Imports films and screenings from bioguiden
 */

class Fb_Movies_Import {

	// Cinema name
	private $cinema_name;

	public function __construct() {

		$this->cinema_name = 'hagabion';

	}

	/**
	 * Hooks into the 'save_post_film' action
	 * @uses Fb_Movies_Import::fetch_film_info
	 * @uses Fb_Movies_Import::fetch_film_screenings
	 * @param $post_id int
	 */
	public function save_post( $post_id ) {

		if ( ! get_post_meta( $post_id, 'bioguiden_fetched_info' ) ) :

			//We check if content has been added to the post,
			// if so we only want to fetch screenings
			if ( ! empty( get_post( $post_id )->post_content ) ) :

				$this->fetch_film_screenings( $post_id );

			else :

				if ( $this->fetch_film_info( $post_id ) ) :

					update_post_meta( $post_id, 'bioguiden_fetched_info', true );

					$this->fetch_film_screenings( $post_id );

				else :

					if ( get_post_meta( $post_id, 'filmnummer', true ) != '' ) :

						add_filter( 'redirect_post_location', array( $this, 'add_error_query_arg' ), 10, 1 );

					endif;

					delete_post_meta( $post_id, 'filmnummer' );

				endif;

			endif;

		endif;

	}

	/**
	 * Add query arg to be able to later display an admin_notice
	 * @param $location str
	 */
	public function add_error_query_arg( $location ) {

		return add_query_arg( 'bioguiden_error', true, $location );

	}

	/**
	 * Adds 'bioguiden_error' to WP's list of removable query args
	 * @param $removable_query_args array
	 */
	public static function remove_error_query_arg( $removable_query_args ) {

		$removable_query_args[] = 'bioguiden_error';

		return $removable_query_args;

	}

	/**
	 * Adds an admin notice if we're not able to fetch info from bioguiden
	 */
	public static function admin_notice() {

		echo '<div class="error"><p>Något gick fel i kopplingen till folketsbio.se, var vänlig att kontrollera att du skrivit in rätt filmnummer och att folketsbios server fungerar.</p></div>';

	}

	/**
	 * Fetches film info for a single movie from bioguiden
	 * @uses Fb_Movies_Import::create_film_info_xml
	 * @uses Fb_Movies_Import::remote_post
	 * @uses Fb_Movies_Import::save_movie_info
	 * @param $post_id int
	 */
	private function fetch_film_info( $post_id ) {

		$filmnummer = get_post_meta( $post_id, 'filmnummer', true );

		if ( $film = $this->remote_post( 'https://www.folketsbio.se/wp-json/wp/v2/film?filter[meta_key]=filmnummer&filter[meta_value]=', $filmnummer ) ) :

			return $this->save_movie_info( $film, $post_id );

		else :

			return false;

		endif;

	}

	/**
	 * Fetches screenings for a single movie if $post_id is passed to it
	 * otherwise fetches screenings for all movies premiered withing the last 6 months
	 * runs via cron twice daily and when a new film is posted
	 * @uses Fb_Movies_Import::create_film_screenings_xml
	 * @uses Fb_Movies_Import::remote_post
	 * @uses Fb_Movies_Import::save_screenings
	 * @param $post_id int
	 */
	public function fetch_film_screenings( $post_id = false ) {

		if ( $post_id ) :

			$movie_id = get_post_meta( $post_id, 'fbse_id', true );

			$url = 'https://www.folketsbio.se/wp-json/wp/v2/visning?filter[post_parent]=' . $movie_id . '&filter[biograf]=' . $this->cinema_name . '&filter[date_query][after]=today';

			if ( $json = $this->remote_post( $url, '' ) ) :

				$this->save_screenings( $json, $post_id );

			else :

				return;

			endif;

		else :

			$movies = new WP_Query( array(
				'post_type' => 'film',
				'posts_per_page' => -1,
				'post_status' => array( 'publish', 'future' ),
				'date_query' => array( 'after' => '-12 month' ),
				'meta_key' => 'fbse_id',
				'meta_compare' => 'EXISTS',
			) );

			while ( $movies->have_posts() ) : $movies->the_post();

				$movie_id = get_post_meta( $post_id, 'fbse_id', true );

				$url = 'https://www.folketsbio.se/wp-json/wp/v2/visning?filter[post_parent]=' . $movie_id . '&filter[biograf]=' . $this->biograf_namn . '&filter[date_query][after]=today';

				if ( $json = $this->remote_post( $url, '' ) ) :

					$this->save_screenings( $json, $post_id );

				else :

					return;

				endif;

			endwhile;

		endif;

	}

	/**
	 * Saves movie info to the database
	 * @param $json string
	 * @param $post_id int
	 * @uses Fb_Movie_Import::save_term
	 * @uses Fb_Movie_Import::fix_acf
	 */
	private function save_movie_info( $json, $post_id ) {

		$json = json_decode( $json );
		$json = $json[0];

		$saved = false;

		if ( get_post_status( $post_id ) === 'publish' ) :

			$post_status = 'publish';

		else :

			$post_status = 'draft';

		endif;

		$post_arr = array(
			'ID' => $post_id,
			'post_title' => $json->title->rendered,
			'post_name' => $json->slug,
			'post_content' => $json->content->rendered,
			'post_date' => $json->date,
			'post_date_gmt' => $json->date,
			'post_status' => $post_status,
			'edit_date' => true,
		);

		$post = wp_update_post( $post_arr );

		if ( $post ) :

			$saved = true;

			if ( $json->featured_media !== 0 ) {
				$this->add_featured_image( $json->featured_media, $post );
			}

			$taxonomies = array( 'regissor', 'manus', 'producent', 'skadespelare', 'sprak', 'land', 'genre', 'aldersgrans', 'produktionsar', 'undertexter' );

			foreach ( $taxonomies as $taxonomy ) {
				if ( isset( $json->terms->$taxonomy ) ) {
					$terms = $json->terms->$taxonomy;
					foreach ( $terms as $term ) {
						$sanitized_term = sanitize_title( $term );

						if ( ! term_exists( $sanitized_term, $taxonomy )  ) :

							wp_insert_term( $term, $taxonomy, array( 'slug' => $sanitized_term ) );

						endif;

						wp_set_object_terms( $post, $sanitized_term, $taxonomy, true );
					}
				}
			}

			update_post_meta( $post, 'org_originaltitel', $json->originaltitle );

			update_post_meta( $post, 'org_langd', $json->length );

			update_post_meta( $post, 'org_trailer', $json->trailer_url );

			update_post_meta( $post, 'org_fbse_url', $json->link );

			update_post_meta( $post, 'fbse_id', $json->id );

			add_action( 'acf/save_post', array( $this, 'fix_acf' ), 99, 1 );

		endif;

		return $saved;

	}

	/**
	 * Fetch and save the featured image from folketsbio.se
	 *
	 * @param $image_id int
	 * @param $post_id int
	 */
	public function add_featured_image( $image_id, $post_id ) {
		$image = $this->remote_post( 'https://www.folketsbio.se/wp-json/wp/v2/media/', $image_id );
		$image = json_decode( $image );
		$image_url = $image->media_details->sizes->full->source_url;
		$image_name = $image->media_details->sizes->full->file;
		$image_mime_type = $image->media_details->sizes->full->mime_type;
		$image_title = $image->title->rendered;

		$upload_dir = wp_upload_dir();

		if ( ! $image_data = file_get_contents( $image_url ) ) :
			error_log( $post_id );
			return false;
		endif;

		if ( wp_mkdir_p( $upload_dir['path'] ) ) :
			$file = $upload_dir['path'] . '/' . $image_name;
		else :
			$file = $upload_dir['basedir'] . '/' . $image_name;
		endif;

		if ( ! file_put_contents( $file, $image_data ) ) :
			return false;
		endif;

		$attachment = array(
			'post_mime_type' => $image_mime_type,
			'post_title'     => $image_name,
			'post_status'    => 'inherit',
		);

		$attach_id = wp_insert_attachment( $attachment, $file, $post_id );

		require_once( ABSPATH . 'wp-admin/includes/image.php' );

		$attach_data = wp_generate_attachment_metadata( $attach_id, $file );

		wp_update_attachment_metadata( $attach_id, $attach_data );

		set_post_thumbnail( $post_id, $attach_id );

		return $attach_id;

	}

	/**
	 * Advanced custom fields saves the post_meta after WP
	 * this means when we try to save the metadata from bioguiden
	 * it gets overwritten by ACF. Therefore we save the metadata
	 * with the prefix org_ and then hook into ACF and updates the
	 * metadata without the prefix
	 * @param $post_id int
	 */
	public function fix_acf( $post_id ) {

		update_post_meta( $post_id, 'originaltitel', get_post_meta( $post_id, 'org_originaltitel', true ) );
		update_post_meta( $post_id, 'langd', get_post_meta( $post_id, 'org_langd', true ) );
		update_post_meta( $post_id, 'trailer', get_post_meta( $post_id, 'org_trailer', true ) );
		update_post_meta( $post_id, 'fbse_url', get_post_meta( $post_id, 'org_fbse_url', true ) );

		delete_post_meta( $post_id, 'org_originaltitel' );
		delete_post_meta( $post_id, 'org_langd' );
		delete_post_meta( $post_id, 'org_trailer' );
		delete_post_meta( $post_id, 'org_fbse_url' );

	}

	/**
	 * Saves screenings to the database
	 * and relates them with the correct film
	 * and taxonomies
	 * @param $xml string
	 * @param $parent_id int
	 */
	private function save_screenings( $json, $parent_id ) {

		$json = json_decode( $json );
		if ( empty( $json ) ) {
			return;
		}

		foreach ( $json as $screening ) :

			$booking_url = $screening->booking_url;
			$screening_time = $screening->date;
			$screening_id = $screening->id;

			if ( ! get_page_by_title( wp_strip_all_tags( $screening_id . $screening_time ), OBJECT, 'visning' ) ) :
				$post = wp_insert_post( array(
					'post_title' => wp_strip_all_tags( $screening_id . $screening_time ),
					'post_status' => 'future',
					'post_author' => 1,
					'post_type' => 'visning',
					'post_parent' => $parent_id,
					'post_date' => $screening_time,
				) );
				if ( $post ) :
					if ( $booking_url ) :
						update_post_meta( $post, 'booking_url', $booking_url );
					endif;
				endif;
			endif;
		endforeach;

	}

	/**
	 * Wrapper for wp_remote_get()
	 * does a remote post to specified url
	 * @param $url string
	 * @param $xml string
	 * @returns $str | bool
	 */
	private function remote_post( $url, $filmnummer ) {
		$post = wp_remote_get(
			$url . $filmnummer
		);

		if ( ! is_wp_error( $post ) && wp_remote_retrieve_response_code( $post ) == '200' ) :

			return wp_remote_retrieve_body( $post );

		else :

			error_log( wp_remote_retrieve_response_message( $post ) );
			return false;

		endif;

	}
}
