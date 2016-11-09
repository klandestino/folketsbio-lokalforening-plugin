<?php

/**
 * Register all custom fields for use with the Advanced Custom Fields plugin
 */
if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array (
	'key' => 'group_55c09d1be5a89',
	'title' => 'Importera filminfo',
	'fields' => array (
		array (
			'key' => 'field_55c33c2957199',
			'label' => 'Filmnummer (32-siffrigt)',
			'name' => 'filmnummer',
			'type' => 'text',
			'instructions' => 'Fyll i och klicka på spara utkast så hämtas information från Folketsbio.se ifall filmen distribueras av Folkets Bio. Visningsinformation hämtas från Bioguiden.',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '0010-000000002601-000000002601-0000',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'film',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'acf_after_title',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
));

acf_add_local_field_group(array (
	'key' => 'group_55c4831311b52',
	'title' => 'Filminfo',
	'fields' => array (
		array (
			'key' => 'field_55c4838e639f8',
			'label' => 'Originaltitel',
			'name' => 'originaltitel',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_55c4831ccec17',
			'label' => 'Längd',
			'name' => 'langd',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
			'prepend' => '',
			'append' => '',
			'maxlength' => '',
			'readonly' => 0,
			'disabled' => 0,
		),
		array (
			'key' => 'field_55c4833d5893b',
			'label' => 'Länk till filmen på folketsbio.se',
			'name' => 'fbse_url',
			'type' => 'text',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'default_value' => '',
			'placeholder' => '',
		),
		array (
			'key' => 'field_55c4836b5893e',
			'label' => 'Trailer',
			'name' => 'trailer',
			'type' => 'oembed',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'width' => '',
			'height' => '',
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'film',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
));

endif;

if( function_exists('acf_add_local_field_group') ):

acf_add_local_field_group(array (
	'key' => 'group_57b58fad36314',
	'title' => 'Visningar som inte finns i Bioguiden',
	'fields' => array (
		array (
			'key' => 'field_57b58fbb7fb06',
			'label' => 'Visning',
			'name' => 'fb_visning',
			'type' => 'repeater',
			'instructions' => '',
			'required' => 0,
			'conditional_logic' => 0,
			'wrapper' => array (
				'width' => '',
				'class' => '',
				'id' => '',
			),
			'collapsed' => '',
			'min' => '',
			'max' => '',
			'layout' => 'table',
			'button_label' => 'Lägg till visning',
			'sub_fields' => array (
				array (
					'key' => 'field_57b58fd27fb07',
					'label' => 'Datum och tid',
					'name' => 'fb_visning_datum',
					'type' => 'date_time_picker',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'display_format' => 'Y-m-d H:i:s',
					'return_format' => 'Y-m-d H:i:s',
					'first_day' => 1,
				),
				array (
					'key' => 'field_57b705b0b4df5',
					'label' => 'Bokningslänk',
					'name' => 'fb_visning_booking_url',
					'type' => 'url',
					'instructions' => '',
					'required' => '',
					'conditional_logic' => '',
					'wrapper' => array (
						'width' => '',
						'class' => '',
						'id' => '',
					),
					'default_value' => '',
					'placeholder' => '',
				),
			),
		),
	),
	'location' => array (
		array (
			array (
				'param' => 'post_type',
				'operator' => '==',
				'value' => 'film',
			),
		),
	),
	'menu_order' => 0,
	'position' => 'normal',
	'style' => 'default',
	'label_placement' => 'top',
	'instruction_placement' => 'label',
	'hide_on_screen' => '',
	'active' => 1,
	'description' => '',
));

endif;

if( function_exists('acf_add_options_page') ) :

	acf_add_options_page(array(
		'page_title' 	=> 'Bioguiden',
		'menu_title'	=> 'Bioguiden',
		'menu_slug' 	=> 'bioguiden',
		'capability'	=> 'update_core',
		'redirect'	=> false,
		'parent_slug' => 'options-general.php',
	));

endif;
