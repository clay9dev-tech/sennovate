<?php
namespace Mandy;

/*
Plugin Name: WordPress Theme Options
Plugin URI:  https://wordpress.com
Description: Create a global options page, with dev hooks for easy extensibility
Version:     0.1
Author:      WordPress
Author URI:  https://wordpress.com
Text Domain: quickbuild
*/

// Exit if accessed directly
if (! defined('ABSPATH')) {
	exit;
}

class SkeletorThemeOptions {
	const BASE_FIELD_GROUP = [
		[
			'key'   => 'field_theme_opts_tab_global',
			'label' => 'Global',
			'type'  => 'tab',
		],
		[
			'key'           => 'field_theme_opts_site_logo',
			'label'         => 'Site Logo',
			'name'          => 'site_logo',
			'type'          => 'image',
			'return_format' => 'url',
			'preview_size'  => 'medium',
			'library'       => 'all',
			'max_size'      => '25K',
			'mime_types'    => 'svg,png',
			'required'      => 1,
		],
		[
			'key'   => 'field_theme_opts_tab_announcement',
			'label' => 'Announcement',
			'type'  => 'tab',
		],
		[
			'key'          => 'field_theme_opts_announcement',
			'label'        => 'Announcement Content',
			'name'         => 'acqueon_announcement_content',
			'type'         => 'wysiwyg',
			'toolbar'      => 'basic',
			'media_upload' => 0,
		],
		[
			'key'   => 'field_theme_opts_tab_404',
			'label' => '404 Page',
			'type'  => 'tab',
		],
		[
			'key'          => 'field_theme_opts_404',
			'label'        => '404 Page Content',
			'name'         => 'site_404_content',
			'type'         => 'wysiwyg',
			'toolbar'      => 'basic',
			'media_upload' => 0,
		],
	];

	public static function setup() {
		add_action('acf/init', [__CLASS__, 'acf_init']);
		add_filter('theme_options_fields', [__CLASS__, 'force_tabs_to_left'], PHP_INT_MAX);
	}

	public static function force_tabs_to_left($fields) {
		foreach ($fields as &$field) {
			if ($field['type'] === 'tab') {
				$field['placement'] = 'left';
			}
		}
		return $fields;
	}

	public static function acf_init() {
		$fields = apply_filters('theme_options_fields', self::BASE_FIELD_GROUP);
		$title = apply_filters('theme_options_title', 'Other Content');
		self::add_skeletor_options_page($title, $fields);
	}

	/**
	 * Helper function to quickly setup an options page with a given set of fields
	 *
	 * @param String $title
	 * @param Array<Field> $fields
	 * @param string $post_id
	 * @param string $parent_slug
	 * @return void
	 */
	public static function add_skeletor_options_page($title, $fields, $post_id = 'options', $parent_slug = '') {
		if (!function_exists('acf_add_options_page')) {
			return;
		}
		if (!function_exists('acf_add_local_field_group')) {
			return;
		}

		$slug = sanitize_title($title);

		$icon = apply_filters('theme_options_icon', 'dashicons-admin-generic');

		$position = apply_filters('theme_options_menu_position', 50);

		\acf_add_options_page([
			'page_title'  => $title,
			'menu_title'  => $title,
			'menu_slug'   => $slug,
			'post_id'     => $post_id,
			'parent_slug' => $parent_slug,
			'capability'  => 'edit_posts',
			'icon_url'    => $icon,
			'redirect'    => false,
			'position'    => $position,
		]);

		\acf_add_local_field_group([
			'key'      => sprintf('group_%s_fields', $slug),
			'title'    => $title,
			'style'    => 'seamless',
			'fields'   => $fields,
			'location' => [
				[
					[
						'param'    => 'options_page',
						'operator' => '==',
						'value'    => $slug,
					],
				],
			],
		]);
	}
}

add_action('after_setup_theme', ['\Mandy\SkeletorThemeOptions', 'setup']);
