<?php
namespace Mandy\Posttypes;

class Event extends \Mandy\Custom_Post_Type {
	/** @var string */
	static $name = 'event';

	/** @var string */
	static $placeholder_text = 'Enter event name here';

	/** @var array */
	static $labels = [
		'menu_name' => 'Events',
		'singular'  => 'Event',
		'plural'    => 'Events',
		'all_items' => 'All Events',
	];


	/** @var array */
	static $options = [
		'has_archive'        => true,
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_rest'       => true,
		'menu_position'      => 20,
		'menu_icon'         => 'dashicons-portfolio',
		'rewrite'           => [
			'slug'       => 'events',
			'with_front' => false,
		],
		'supports'           => [
			'title',
			'editor',
			'custom-fields',
			'thumbnail',
			'excerpt',
		],
	];


	/** @var array */
	static $admin_columns_to_remove = ['wpseo-score', 'wpseo-score-readability'];

	/** @var array */
	static $options_field_group = [
		[
			'key'   => 'field_event_opts_lptab',
			'label' => 'Landing Page',
			'type'  => 'tab',
		],
		[
			'key'           => 'field_event_opts_section_title',
			'label'         => 'Latest event title',
			'name'          => 'archive_latest_title',
			'type'          => 'text',
		],
		[
			'key'          => 'field_event_opts_archive_header',
			'type'         => 'post_object',
			'name'         => 'archive_header',
			'label'        => 'Event header banner',
			'post_type'    => 'wp_block',
		],
				[
			'key'           => 'field_event_opts_grid_title',
			'label'         => 'Grid title',
			'name'          => 'archive_grid_title',
			'type'          => 'text',
		],
	];
	/**
	 * Passed into acf_add_local_field_group() during the acf/init action.
	 * Leave the location paramter out, it will automatically be set for you!
	 *
	 * @var array
	 */
	static $field_group = [
		'key'                   => 'group_events_cpt',
		'title'                 => 'Event Properties',
		'menu_order'            => 0,
		'position'              => 'acf_after_title',
		'style'                 => 'seamless',
		'label_placement'       => 'top',
		'instruction_placement' => 'label',
		'fields'                => [
			[
				'key'     => 'field_event_location',
				'label'   => 'Location',
				'name'    => 'location',
				'type'    => 'text',
				'wrapper' => ['width' => '100%'],
			],
			[
				'key'           => 'field_event_start_date',
				'label'         => 'Start Date',
				'name'          => 'start_date',
				'type'          => 'date_picker',
				'display_format'=> 'j M, Y', // Display in admin
				'return_format' => 'j M, Y', // Value returned
				'wrapper'       => ['width' => '50%'],
			],
			[
				'key'           => 'field_event_end_date',
				'label'         => 'End Date',
				'name'          => 'end_date',
				'type'          => 'date_picker',
				'display_format'=> 'Y-m-d',
				'return_format' => 'Y-m-d',
				'wrapper'       => ['width' => '50%'],
			],
			[
				'key'     => 'field_event_link',
				'label'   => 'Event Link',
				'name'    => 'event_link',
				'type'    => 'url',
				'wrapper' => ['width' => '100%'],
			],
		],
		'location'              => [
			[
				[
					'param'    => 'post_type',
					'operator' => '==',
					'value'    => 'event',
				],
			],
		],
	];

	/**
	 * on a single resource page
	 * that is gated
	 * and that is not a thank you page
	 * --- add the landing-page class
	 *
	 * @param array $classes
	 * @return array
	 */
	public static function body_class($classes) {
		if (in_array('single-event', $classes)) {
			$classes[] = 'event-landing-page';
		}

		return $classes;
	}

	static function initialize() {
		parent::initialize();
		add_filter('body_class', [__CLASS__, 'body_class']);
	}

}

add_action('after_setup_theme', ['\\Mandy\\Posttypes\\Event', 'initialize']);
