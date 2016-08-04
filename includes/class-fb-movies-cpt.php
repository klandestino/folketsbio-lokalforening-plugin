<?php

/**
 * Register the custom post types for the plugin.
 */

class Fb_Movies_CPT {

	/**
	 * Register custom post type 'film'.
	 */
	public static function add_post_type_film() {

		$labels = array(
			'name'                => 'Filmer',
			'singular_name'       => 'Film',
			'menu_name'           => 'Filmer',
			'name_admin_bar'      => 'Filmer',
			'parent_item_colon'   => '',
			'all_items'           => 'Alla',
			'add_new_item'        => 'Lägg till',
			'add_new'             => 'Lägg till',
			'new_item'            => 'Ny',
			'edit_item'           => 'Redigera',
			'update_item'         => 'Uppdatera',
			'view_item'           => 'Visa',
			'search_items'        => 'Sök',
			'not_found'           => 'Inget hittades',
			'not_found_in_trash'  => 'Inget hittades',
		);
		$args = array(
			'label'               => 'film',
			'description'         => 'Film',
			'labels'              => $labels,
			'supports'            => array( 'title', 'editor', 'thumbnail', ),
			'hierarchical'        => false,
			'public'              => true,
			'show_ui'             => true,
			'show_in_menu'        => true,
			'menu_position'       => 5,
			'menu_icon'           => 'dashicons-format-video',
			'show_in_admin_bar'   => true,
			'show_in_nav_menus'   => true,
			'can_export'          => true,
			'has_archive'         => true,
			'exclude_from_search' => false,
			'publicly_queryable'  => true,
			'capability_type'     => 'page',
		);
		register_post_type( 'film', $args );

	}

	/**
	 * Register custom post type 'visning'.
	 */
	public static function add_post_type_visning() {

		$labels = array(
			'name'                => 'Visningar',
			'singular_name'       => 'Visning'
		);
		$args = array(
			'label'               => 'visning',
			'description'         => 'Visning',
			'labels'              => $labels,
			'supports'            => array( ),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => false,
			'show_in_menu'        => false,
			'menu_position'       => 5,
			'show_in_admin_bar'   => false,
			'show_in_nav_menus'   => false,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'capability_type'     => 'page',
		);
		register_post_type( 'visning', $args );

	}

}