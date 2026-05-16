<?php

namespace ChildTheme;

class Page
{
	public static function setup()
	{
		add_action('init', [__CLASS__, 'add_excerpt_to_pages']);
		add_action('acf/init', [__CLASS__, 'register_acf_fields']);
		add_filter('acf/location/rule_match/post_parent_products', [__CLASS__, 'match_products_child_pages'], 10, 3);
		add_filter('acf/location/rule_types', [__CLASS__, 'add_custom_rule_type']);
		add_filter('acf/location/rule_values/post_parent_products', [__CLASS__, 'set_products_rule_value']);
	}

	public static function add_excerpt_to_pages()
	{
		add_post_type_support('page', 'excerpt');
	}

	public static function add_custom_rule_type($choices)
	{
		$choices['Page']['post_parent_products'] = 'Page is child of "Products"';
		return $choices;
	}

	public static function set_products_rule_value($choices)
	{
		$products_page = get_page_by_path('products');
		if ($products_page) {
			$choices[$products_page->ID] = 'Products';
		}
		return $choices;
	}

	public static function match_products_child_pages($match, $rule, $screen)
	{
		if (!isset($screen['post_id'])) {
			return false;
		}

		$post = get_post($screen['post_id']);
		if (!$post || $post->post_type !== 'page') {
			return false;
		}

		$parent = get_post_field('post_parent', $post->ID);
		$match = intval($parent) === intval($rule['value']);
		return $match;
	}

	public static function register_acf_fields()
	{
		if (!function_exists('acf_add_local_field_group')) {
			return;
		}

		$products_page = get_page_by_path('products');
		if (!$products_page) return;

		acf_add_local_field_group(array(
			'key' => 'group_page_products',
			'title' => 'Products Extra Fields',
			'fields' => array(
				array(
					'key' => 'field_pages_products_list_fields',
					'label' => 'Product List Fields',
					'name' => 'products_list_fields',
					'type' => 'repeater',
					'sub_fields' => array(
						array(
							'key' => 'field_product_image',
							'label' => 'List Icon',
							'name' => 'product_list_icon',
							'type' => 'image',
							'return_format' => 'url',
							'preview_size' => 'medium',
						),
						array(
							'key' => 'field_product_name',
							'label' => 'List Name',
							'name' => 'product_list_name',
							'type' => 'text',
						),
					),
					'min' => 0,
					'layout' => 'block',
					'button_label' => 'Add Icon List',
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'post_parent_products',
						'operator' => '==',
						'value' => $products_page->ID,
					),
				),
			),
		));
	}
}

add_action('after_setup_theme', ['ChildTheme\Page', 'setup']);
