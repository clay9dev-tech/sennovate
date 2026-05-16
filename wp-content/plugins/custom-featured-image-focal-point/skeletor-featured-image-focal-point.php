<?php
namespace Mandy;

/*
Plugin Name: WordPress Featured Image Focal Point
Plugin URI:  https://wordpress.com
Description: Add a FocalPointPicker field to Document Settings to specify a point on the Featured Image.
Version:     0.1
Author:      WordPress
Author URI:  https://wordpress.com
Text Domain: quickbuild
*/

// Exit if accessed directly
if (! defined('ABSPATH')) {
	exit;
}

class SkeletorFeaturedImageFocalPoint {
	const FOCAL_POINT = 'featuredImageFocalPoint';

	public static function setup() {
		add_action('init', [__CLASS__, 'init']);
		add_filter('enqueue_block_editor_assets', [__CLASS__, 'enqueue_block_editor_assets']);
	}

	public static function init() {
		register_post_meta('', 'featuredImageFocalPoint', [
			'single'       => true,
			'type'         => 'object',
			'show_in_rest' => [
				'schema' => [
					'type'       => 'object',
					'properties' => [
						'x' => [ 'type' => 'number' ],
						'y' => [ 'type' => 'number' ],
					],
				],
			],
		]);
	}

	public static function enqueue_block_editor_assets() {
		wp_enqueue_script(
			'skeletor-featured-image-focal-point',
			plugin_dir_url(__FILE__) . '/build/index.js',
			[],
			filemtime(__DIR__ . '/build/index.js'),
			true
		);
	}

	/**
	 * Render the specified post�s Featured Image as a core Image block,
	 * with the object-position set to use the Focal Point property.
	 *
	 * @param int|WP_Post|null $post Defaults to global post
	 * @param string $size The image size to render
	 * @param array $additional_container_classes
	 *
	 * @return string The rendered HTML
	 */
	public static function featured_image_block($post = null, string $size = 'large', array $additional_container_classes = []) {
		$post = get_post($post);

		$post_thumb_id = get_post_thumbnail_id($post);
		$post_thumb_atts = [];

		$focal_point = get_post_meta($post->ID, self::FOCAL_POINT, true);
		if ($focal_point) {
			$post_thumb_atts['style'] = sprintf(
				'object-position: %s%% %s%%',
				100 * $focal_point['x'],
				100 * $focal_point['y']
			);
		}

		$container_classes = array_merge(
			['wp-block-image'],
			$additional_container_classes
		);

		$img = sprintf(
			'<div class="%s"><figure class="size-%s">%s</figure></div>',
			implode(' ', $container_classes),
			$size,
			wp_get_attachment_image($post_thumb_id, $size, false, $post_thumb_atts)
		);

		return render_block([
			'blockName'    => 'core/image',
			'attrs'        => [
				'id'         => $post_thumb_id,
				'sizeSlug'   => $size,
				'focalPoint' => $focal_point,
			],
			'innerHTML'    => $img,
			'innerContent' => [$img],
		]);
	}
}


add_action('after_setup_theme', ['\\Mandy\\SkeletorFeaturedImageFocalPoint', 'setup']);
