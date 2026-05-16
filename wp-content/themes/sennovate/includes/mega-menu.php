<?php
namespace Skeletor\Child_Theme\ReusableBlocks;
class WP_Block_Design_Customizations {
	const POSTTYPE_SLUG = 'wp_block';

	public static function setup() {
		remove_filter('register_taxonomy_args', ['Skeletor\\ReusableBlocks\\WP_Block_Design_Customizations', 'register_taxonomy']);
		add_filter('walker_nav_menu_start_el', [__CLASS__, 'render_reusable_block_nav_item'], 10, 4);
		add_filter('register_post_type_args', [__CLASS__, 'register_post_type'], 10, 2);
	}

	public static function render_reusable_block_nav_item($item_output, $item, $depth, $args) {
		if ($item->object !== self::POSTTYPE_SLUG) {
			return $item_output;
		}

		$content = get_the_content(null, false, $item->object_id);
		return apply_filters('the_content', $content);
	}

	public static function register_post_type($args, $post_type) {
		if ($post_type === 'wp_block') {
			$args['show_in_nav_menus'] = 1;
		}

		return $args;
	}
}

add_action('after_setup_theme', ['Skeletor\\Child_Theme\\ReusableBlocks\\WP_Block_Design_Customizations', 'setup'], 10);
