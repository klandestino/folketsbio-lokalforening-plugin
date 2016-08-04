<?php

/**
 * Register all custom taxonomies
 */
function fb_movies_taxonomies() {

	$taxonomies = array(
		'regissor' => array(
			'post_type' => array( 'film' ),
			'slug' => 'regissor',
			'name' => 'Regissörer',
			'singular_name' => 'Regissör',
			'public' => true,
			'show_in_admin' => true
		),
		'manus' => array(
			'post_type' => array( 'film' ),
			'slug' => 'manus',
			'name' => 'Manusförfattare',
			'singular_name' => 'Manusförfattare',
			'public' => true,
			'show_in_admin' => true
		),
		'producent' => array(
			'post_type' => array( 'film' ),
			'slug' => 'producent',
			'name' => 'Producenter',
			'singular_name' => 'Producent',
			'public' => true,
			'show_in_admin' => true
		),
		'skadespelare' => array(
			'post_type' => array( 'film' ),
			'slug' => 'skadespelare',
			'name' => 'Med',
			'singular_name' => 'Med',
			'public' => true,
			'show_in_admin' => true
		),
		'sprak' => array(
			'post_type' => array( 'film' ),
			'slug' => 'sprak',
			'name' => 'Språk',
			'singular_name' => 'Språk',
			'public' => true,
			'show_in_admin' => true
		),
		'land' => array(
			'post_type' => array( 'film' ),
			'slug' => 'land',
			'name' => 'Länder',
			'singular_name' => 'Land',
			'public' => true,
			'show_in_admin' => true
		),
		'genre' => array(
			'post_type' => array( 'film' ),
			'slug' => 'genre',
			'name' => 'Genrer',
			'singular_name' => 'Genre',
			'public' => true,
			'show_in_admin' => true
		),
		'aldersgrans' => array(
			'post_type' => array( 'film' ),
			'slug' => 'aldersgrans',
			'name' => 'Åldergräns',
			'singular_name' => 'Åldersgräns',
			'public' => true,
			'show_in_admin' => true
		),
		'produktionsar' => array(
			'post_type' => array( 'film' ),
			'slug' => 'produktionsar',
			'name' => 'Produktionsår',
			'singular_name' => 'Produktionsår',
			'public' => true,
			'show_in_admin' => true
		),
		'produkt' => array(
			'post_type' => array( 'film' ),
			'slug' => 'produkt',
			'name' => 'Produkter',
			'singular_name' => 'Produkt',
			'public' => true,
			'show_in_admin' => false
		),
		'undertexter' => array(
			'post_type' => array( 'film' ),
			'slug' => 'undertexter',
			'name' => 'Undertexter',
			'singular_name' => 'Undertexter',
			'public' => true,
			'show_in_admin' => true
		),
	);

	foreach ( $taxonomies as $key => $taxonomy ) :

		$labels = array(
			'name'                       => $taxonomy['name'],
			'singular_name'              => $taxonomy['singular_name'],
			'menu_name'                  => $taxonomy['name'],
			'all_items'                  => 'Alla',
			'parent_item'                => 'Förälder',
			'parent_item_colon'          => 'Förälder:',
			'new_item_name'              => 'Ny',
			'add_new_item'               => 'Lägg till ny',
			'edit_item'                  => 'Redigera',
			'update_item'                => 'Uppdatera',
			'view_item'                  => 'Visa',
			'separate_items_with_commas' => 'Separera med komma',
			'add_or_remove_items'        => 'Lägg till eller ta bort',
			'choose_from_most_used'      => 'Välj bland de mest använda',
			'popular_items'              => 'Populära',
			'search_items'               => 'Sök',
			'not_found'                  => 'Inget hittades',
		);
		$args = array(
			'labels'                     => $labels,
			'hierarchical'               => false,
			'public'                     => $taxonomy['public'],
			'show_in_menu'  			 => $taxonomy['show_in_admin'],
			'show_ui'					 => $taxonomy['show_in_admin']
		);
		register_taxonomy( $taxonomy['slug'], $taxonomy['post_type'], $args );

	endforeach;

}