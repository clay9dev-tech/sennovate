<?php
namespace Mandy;

/*
Plugin Name: WordPress Image Focal Point
Plugin URI:  https://wordpress.com
Description: Add a FocalPointPicker field to Image blocks
Version:     0.1
Author:      WordPress
Author URI:  https://wordpress.com
Text Domain: quickbuild
*/

// Exit if accessed directly
if (! defined('ABSPATH')) {
	exit;
}

class SkeletorImageFocalPoint {
	public static function setup() {
		add_filter('enqueue_block_editor_assets', [__CLASS__, 'enqueue_block_editor_assets']);
	}

	public static function enqueue_block_editor_assets() {
		wp_enqueue_script(
			'skeletor-image-focal-point',
			plugin_dir_url(__FILE__) . '/build/index.js',
			[],
			filemtime(__DIR__ . '/build/index.js'),
			true
		);
	}
}


add_action('after_setup_theme', ['\\Mandy\\SkeletorImageFocalPoint', 'setup']);
