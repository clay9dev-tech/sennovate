<?php
namespace Mandy;

/*
Plugin Name: WordPress Media Focal Point
Plugin URI:  https://wordpress.com
Description: Add a FocalPointPicker field to Media Library posts
Version:     0.1
Author:      WordPress
Author URI:  https://wordpress.com
Text Domain: quickbuild
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
	exit;
}

class Media_Focal_Point {
	const DEFAULT_VALUE = '50%,0%';

	public static function admin_enqueue_scripts() {
		wp_enqueue_script('focal_point_input', plugin_dir_url(__FILE__) . '/admin.js', null, '1', true);
		wp_enqueue_style('focal_point_input', plugin_dir_url(__FILE__) . '/media-focal-point.css');
	}

	private static function focal_point_input($value, \WP_Post $post) {
		$focal_point = explode(',', $value);
		$x = $focal_point[0];
		$y = $focal_point[1];
		$img = sprintf('<img src="%s" class="focal-point-input-image" />', $post->guid);
		$pin = sprintf('<div class="focal-point-pin" style="left: %s; top: %s"></div>', $x, $y);
		$hidden_input = sprintf('<input type="hidden" name="focal_point" value="%s" />', $value);

		$input = sprintf('<div class="focal-point-input-wrapper">%s %s %s</div>', $img, $pin, $hidden_input);
		return $input;
	}

	public static function add_focal_point_field_to_edit($fields, $post) {
		$value = get_post_meta($post->ID, 'focal_point', true) ?: self::DEFAULT_VALUE;

		$fields['focal_point'] = [
			'label'         => __('Focal Point'),
			'input'         => 'html',
			'html'          => self::focal_point_input($value, $post),
			'value'         => $value,
			'show_in_modal' => true,
		];

		return $fields;
	}

	public static function attachment_fields_to_save($post) {
		if (!isset($_POST['focal_point'])) {
			return $post;
		}

		update_post_meta($post['ID'], 'focal_point', $_POST['focal_point']);

		return $post;
	}

	public static function load_helpers() {
		require_once(__DIR__ . '/helpers.php');
	}
}

add_filter('attachment_fields_to_edit', [ 'Mandy\Media_Focal_Point', 'add_focal_point_field_to_edit'], 10, 2);
add_filter('attachment_fields_to_save', ['Mandy\Media_Focal_Point', 'attachment_fields_to_save'], 10);
add_action('admin_enqueue_scripts', ['Mandy\Media_Focal_Point', 'admin_enqueue_scripts']);
add_action('plugins_loaded', ['Mandy\Media_Focal_Point', 'load_helpers']);
