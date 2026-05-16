<?php

/**
 * Plugin Name:     WordPress Count Up
 * Plugin URI:      https://wordpress.com
 * Description:     Enqueue Javascript to process .count-up elements
 * Author:          WordPress
 * Author URI:      https://wordpress.com
 * Text Domain:     quickbuild
 * Version:         0.1.0
 *
 */

namespace Skeletor;

class Count_Up
{
	public static function plugins_loaded()
	{
		if (is_admin()) {
			return;
		}

		add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue_scripts']);
	}

	public static function enqueue_scripts()
	{
		$asset = require_once(__DIR__ . '/build/index.asset.php');
		wp_enqueue_script(
			'count_up',
			plugin_dir_url(__DIR__ . '/build/index.js') . 'index.js',
			$asset['dependencies'],
			$asset['version'],
			true
		);
	}
}

add_action('plugins_loaded', ['Skeletor\Count_Up', 'plugins_loaded']);
