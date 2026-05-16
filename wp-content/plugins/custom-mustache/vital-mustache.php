<?php
namespace Mandy;
use \Mustache_Engine;
use \Mustache_Loader_FilesystemLoader;
use \Mustache_Loader_StringLoader;
use \Mustache_Loader_CascadingLoader;

/*
Plugin Name: WordPress Mustache
Plugin URI:  https://wordpress.com
Description: Adds support for Mustache templates to WordPress
Version:     0.1
Author:      WordPress
Author URI:  https://wordpress.com
Text Domain: quick-build-mustache
*/

// Exit if accessed directly
if (! defined('ABSPATH')) {
	exit;
}

class MandyMustache {
	static $engine;
	static $render_index = 0;

	/**
	 * Initialize plugin
	 */
	function __construct() {
		$mustache_path = sprintf('%s/lib/mustache/mustache.php', __DIR__);

		if (!file_exists($mustache_path)) {
			MandyMustache::activate();
		} else {
			if (!class_exists('Mustache_Engine')) {
				require_once($mustache_path);
			}
		}

		MandyMustache::initialize();
	}

	private static function initialize() {
		$plugin_views = sprintf('%s/views', __DIR__);
		$plugin_partials = sprintf('%s/partials', $plugin_views);

		$view_path = [ $plugin_views ];
		$partials_path = [ $plugin_partials ];

		$theme_views = sprintf('%s/views', get_template_directory());
		if (is_dir($theme_views)) {
			array_unshift($view_path, $theme_views);

			$theme_partials = sprintf('%s/partials', $theme_views);

			if (is_dir($theme_partials)) {
				array_unshift($partials_path, $theme_partials);
			}
		}

		$view_path = apply_filters('mandy_mustache_view_path', $view_path);
		$partials_path = apply_filters('mandy_mustache_partial_path', $partials_path);

		$loaders = array_map([__CLASS__, 'path_to_filesystem_loader'], $view_path);
		$p_loaders = array_map([__CLASS__, 'path_to_filesystem_loader'], $partials_path);

		$loaders[] = new Mustache_Loader_StringLoader();

		MandyMustache::$engine = new Mustache_Engine([
			'loader'          => new Mustache_Loader_CascadingLoader($loaders),
			'partials_loader' => new Mustache_Loader_CascadingLoader($p_loaders),
		]);
	}

	private static function path_to_filesystem_loader($p) {
		return new Mustache_Loader_FilesystemLoader($p);
	}

	static function activate() {
		ob_start();
		MandyMustache::build_mustache();
		ob_end_clean();
	}

	private static function build_mustache() {
		require_once(sprintf('%s/lib/mustache/bin/build_bootstrap.php', __DIR__));
	}

	static function render($template, $data, $return_string = false) {
		$output = null;

		$data = apply_filters("before_render_{$template}", $data);

		// If data is cleared via a filter above, set it to null and nothing is returned
		if ($data === null) {
			return '';
		}

		$data['_dump'] = sprintf('<pre>%s</pre>', json_encode($data, JSON_PRETTY_PRINT));

		if ($template && static::$engine) {
			$output = static::$engine->render($template, $data);
		}

		$output = apply_filters("after_render_{$template}", $output, $data);

		if (!$return_string) {
			echo $output;
		}

		self::$render_index++;

		return $output;
	}
}

add_action('after_setup_theme', function() {
	new MandyMustache();
}, 9);
