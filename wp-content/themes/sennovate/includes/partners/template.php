<?php
namespace Mandy\Skeletor\Partners;

use WP_Query;

class Template {

	const POSTS_PER_PAGE = 9;

	/**
	 * Boot class
	 */
	public static function setup(): void {
		add_action('init', [__CLASS__, 'register_post_type']);
		add_action('init', [__CLASS__, 'register_taxonomy']);

		add_action('acf/init', [__CLASS__, 'register_acf_fields']);
        add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue_assets']);

        /**
		 * Single Page Hooks
		 */
		add_action(
			'pre_get_posts',
			[__CLASS__, 'register_single_actions']
		);
	}

	/**
	 * Register CPT
	 */
	public static function register_post_type(): void {

		$labels = [
			'name'               => __('Partners'),
			'singular_name'      => __('Partner'),
			'add_new'            => __('Add New'),
			'add_new_item'       => __('Add New Partner'),
			'edit_item'          => __('Edit Partner'),
			'new_item'           => __('New Partner'),
			'view_item'          => __('View Partner'),
			'search_items'       => __('Search Partners'),
			'not_found'          => __('No Partners Found'),
			'menu_name'          => __('Partners'),
		];

		$args = [
			'labels'             => $labels,
			'public'             => true,
			'menu_icon'          => 'dashicons-groups',
			'has_archive'        => true,
            'rewrite'            => [
                'slug'           => 'partner',
                'with_front'     => false,
            ],
			'supports'           => ['title', 'editor', 'thumbnail'],
			'show_in_rest'       => true,
			'menu_position'      => 20,
		];

		register_post_type('partners', $args);
	}

	/**
	 * Register Taxonomy
	 */
	public static function register_taxonomy(): void {

		register_taxonomy(
			'partner_type',
			['partners'],
			[
				'label'             => __('Partner Types'),
				'public'            => true,
				'hierarchical'      => true,
				'show_in_rest'      => true,
				'rewrite'           => [
					'slug' => 'partner-type'
				],
			]
		);
	}

	/**
	 * Register ACF Fields
	 */
	public static function register_acf_fields(): void {

		if (!function_exists('acf_add_local_field_group')) {
			return;
		}

		acf_add_local_field_group([
			'key'    => 'group_partner_fields',
			'title'  => 'Partner Details',
			'fields' => [

				[
					'key'   => 'field_partner_logo',
					'label' => 'Partner Logo',
					'name'  => 'partner_logo',
					'type'  => 'image',
					'return_format' => 'array',
					'preview_size'  => 'medium',
				],

                [
					'key'   => 'field_partner_board_text',
					'label' => 'Partner Title',
					'name'  => 'partner_title',
					'type'  => 'text',
				],

                [
					'key'   => 'field_partner_short_desc',
					'label' => 'Partner Short Description',
					'name'  => 'partner_short_description',
					'type'  => 'textarea',
				],

				[
					'key'   => 'field_partner_website',
					'label' => 'Partner URL',
					'name'  => 'partner_website_url',
					'type'  => 'url',
				],

			],

			'location' => [
				[
					[
						'param'    => 'post_type',
						'operator' => '==',
						'value'    => 'partners',
					],
				],
			],
		]);
	}

	/**
	 * Enqueue Assets
	 */
	public static function enqueue_assets(): void {

		$filepath = get_stylesheet_directory() . '/src/scripts/partners.js';

		wp_enqueue_script(
			'partners-script',
			get_stylesheet_directory_uri() . '/src/scripts/partners.js',
			['jquery'],
			filemtime($filepath),
			true
		);

		wp_localize_script('partners-script', 'partners_ajax', [
			'ajax_url' => admin_url('admin-ajax.php'),
			'nonce'    => wp_create_nonce('partners_nonce'),
		]);
	}

    /**
	 * Register Single Hooks
	 */
	public static function register_single_actions(WP_Query $query): void {

		if (
			is_admin() ||
			!$query->is_main_query() ||
			!$query->is_singular()
		) {
			return;
		}

		$post_type = $query->get('post_type');

		if ($post_type === 'partners') {

			add_action(
				'before_post_content',
				[__CLASS__, 'partner_hero']
			);

			add_action(
				'before_post_content',
				[__CLASS__, 'partner_content']
			);

		}
	}

}

add_action( 'after_setup_theme', ['\Mandy\Skeletor\Partners\Template', 'setup']);