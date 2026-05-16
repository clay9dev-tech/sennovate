<?php
namespace Mandy;

/*
Plugin Name: WordPress Blocks
Plugin URI:  https://wordpress.com
Description: Extend Skeletor_Block to quickly build custom Blocks. Powered by Advanced Custom Fields and Mandy Mustache.
Version:     0.1.2
Author:      WordPress
Author URI:  https://wordpress.com
Text Domain: quickbuild
*/

// Exit if accessed directly
if (! defined('ABSPATH')) {
	exit;
}

require_once(__DIR__ . '/lib/skeletor-block.php');

/**
 * Controller class to manage setup and operations for Skeletor Blocks.
 * Requires Advanced Custom Fields and Mandy Mustache
 */
class Skeletor_Blocks {
	/**
	 * Called on `after_setup_theme`
	 *
	 * Bind actions here.
	 *
	 * @return void
	 */
	public static function setup() {
		self::_check_requirements();
		add_filter('block_categories_all', [__CLASS__, 'block_categories'], 10, 2);
		add_action('acf/init', [__CLASS__, 'initialize_blocks']);
		add_filter('skeletor_block_data', [__CLASS__, 'skeletor_block_classname'], 1, 2);
		add_filter('skeletor_block_data', [__CLASS__, 'skeletor_block_id'], 1, 2);
	}

	/**
	 * get info about this plugin
	 */
	public static function get_info() {
		if( ! function_exists('get_plugin_data') ){
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
		}
		return \get_plugin_data(__FILE__);
	}

	/**
	 * check if any required plugins, et al
	 * are available/accessible
	 */
	private static function _check_requirements() {
		if (!class_exists('\Mandy\MandyMustache')) {
			\error_log(__CLASS__ . ' : Missing the MandyMustache plugin');

			\add_action( 'admin_notices', function(){
				$plugin_data = self::get_info();
				?>
				<div class="error notice">
					<p><?php printf('🚨 <strong>%s</strong> requires the Mandy Mustache plugin to render blocks from the Block Editor.', $plugin_data['Name']); ?></p>
				</div>
				<?php

			});
			return;
		}


	}

	/**
	 * Filter on `block_categories`
	 *
	 * Registers the “Skeletor Blocks” category
	 *
	 * @param array $categories
	 * @param WP_Post $post
	 * @return void
	 */
	public static function block_categories($categories, $post) {
		return array_merge($categories, [
			[
				'slug'  => 'skeletor-blocks',
				'title' => __('Skeletor Blocks', 'mandy'),
			],
		]);
	}

	/**
	 * Called during `acf/init`
	 *
	 * Registers a Block for each class extending Skeletor_Block via
	 * acf_register_block_type()
	 *
	 * @return void
	 * @see https://www.advancedcustomfields.com/resources/acf_register_block_type/
	 */
	public static function initialize_blocks() {
		if (!function_exists('acf_register_block_type')) {
			return;
		}

		foreach (Skeletor_Block::get_blocks() as $name=>$class_name) {
			$block_default_settings = [
				'name'            => $name,
				'title'           => $class_name::$title,
				'skeletor_class'  => $class_name,
				'render_callback' => [__CLASS__, 'render_block'],
				'category'        => 'skeletor-blocks',
				'supports'        => [ 'jsx' => true ],
			];

			$block_settings = array_merge(
				$block_default_settings,
				$class_name::$block_settings
			);

			$block = \acf_register_block_type($block_settings);
		}
	}

	/**
	 * Render callback function used by ACF. This function takes the props and
	 * inner content from the block editor, parses them into an array via the
	 * `skeletor_block_data` filter, then echoes and return the result of
	 * passing that data into the Skeletor_Block's render function
	 *
	 * @param array $params
	 * @param string $content
	 * @param boolean $is_preview
	 * @param int $post_id
	 * @return string
	 */
	static function render_block($params, $content = '', $is_preview = false, $post_id = null) {
		$data = [];
		if (function_exists('get_fields')) {
			$data = get_fields();
		}

		if ($is_preview) {
			$data['is_preview'] = true;
		}

		$data['innerBlocksContent'] = $content;
		$data = apply_filters('skeletor_block_data', $data, $params, $post_id);

		if (isset($params['skeletor_class'])) {
			$out = $params['skeletor_class']::render($data);
		}

		echo $out;
		return $out;
	}

	/**
	 * Filter on `skeletor_block_data`
	 *
	 * Turns the Skeletor_Block's Class name into its className
	 *
	 * @param array $block_data
	 * @param array $props
	 * @return array
	 * @see https://www.youtube.com/watch?v=Ycu434kkk14
	 */
	static function skeletor_block_classname($block_data, $props) {
		$block_class = [];

		if (isset($block_data['_block_class'])) {
			$block_class[] = $block_data['_block_class'];
		}

		if (isset($props['className'])) {
			$block_class[] = $props['className'];
		}

		if (isset($props['align']) && $props['align']) {
			$block_class[] = sprintf('align%s', $props['align']);
		}

		$block_data['_block_class'] = implode(' ', $block_class);

		return $block_data;
	}

	/**
	 * Filter on `skeletor_block_data`
	 *
	 * Parses the Block Editor props and adds the block id to the block data
	 *
	 * @param [type] $block_data
	 * @param [type] $props
	 * @return void
	 */
	static function skeletor_block_id($block_data, $props) {
		if (isset($props['id'])) {
			$block_data['_layout_id'] = $props['id'];
		}

		return $block_data;
	}
}

add_action('after_setup_theme', ['\\Mandy\\Skeletor_Blocks', 'setup'], 9);
