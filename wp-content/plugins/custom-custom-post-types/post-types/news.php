<?php
namespace Mandy\Posttypes;

class News extends \Mandy\Custom_Post_Type {
	/** @var string */
	static $name = 'news';

	/** @var string */
	static $placeholder_text = 'Enter News name here';

	/** @var array */
	static $labels = [
		'menu_name' => 'News',
		'singular'  => 'News',
		'plural'    => 'News',
		'all_items' => 'All News',
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
			'slug'       => 'news',
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
			'key'   => 'field_news_opts_lptab',
			'label' => 'Landing Page',
			'type'  => 'tab',
		],
		[
			'key'           => 'field_news_opts_section_title',
			'label'         => 'Latest news title',
			'name'          => 'archive_latest_title',
			'type'          => 'text',
		],
		[
			'key'          => 'field_news_opts_archive_header',
			'type'         => 'post_object',
			'name'         => 'archive_header',
			'label'        => 'News header banner',
			'post_type'    => 'wp_block',
		],
		[
			'key'           => 'field_news_opts_grid_title',
			'label'         => 'Grid title',
			'name'          => 'archive_grid_title',
			'type'          => 'text',
		],
		[
			'key'          => 'field_news_opts_single_end_cta',
			'type'         => 'post_object',
			'name'         => 'single_end_cta',
			'label'        => 'News single end CTA',
			'post_type'    => 'wp_block',
		],
	];
	/**
	 * Passed into acf_add_local_field_group() during the acf/init action.
	 * Leave the location paramter out, it will automatically be set for you!
	 *
	 * @var array
	 */
	static $field_group = [
		'key'                   => 'group_67a50115b13c888',
		'title'                 => 'News Fields',
		'menu_order'            => 0,
		'position'              => 'acf_after_title',
		'style'                 => 'seamless',
		'label_placement'       => 'top',
		'instruction_placement' => 'label',
		'fields'                => [
			[
				'key'           => 'field_ac_source_cta_link',
				'label'         => 'Source Type',
				'name'          => 'source_cta_link',
				'type'          => 'link',
				'wrapper'       => ['width' => '100%'],
			],
		],
		'location'              => [
			[
				[
					'param'     => 'post_type',
					'operator'  => '==',
					'value'     => 'news',
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
		if (in_array('single-news', $classes)) {
			$classes[] = 'news-landing-page';
		}

		return $classes;
	}

	static function initialize() {
		parent::initialize();
		add_filter('body_class', [__CLASS__, 'body_class']);
	}

}

add_action('after_setup_theme', ['\\Mandy\\Posttypes\\News', 'initialize']);
