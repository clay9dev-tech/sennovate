<?php

/**
 * Return an Image Block with the Featured Image for the specified Post. If that image
 * happens to have a focal_point meta property, that will be included on the image tag
 * style as object-position.
 *
 * @param int|WP_Post|null $post
 * @return string
 */
function get_post_image_with_focal_point($post = null): string {
	$post = get_post($post);
	if (!isset($post->ID)) {
		return '';
	}

	$post_thumb_id = apply_filters('post_card_thumbnail_id', get_post_thumbnail_id($post), $post);
	if (!$post_thumb_id) {
		return '';
	}

	$post_thumb_size = 'large';
	$post_thumb_atts = [];

	$focal_point = get_post_meta($post_thumb_id, 'focal_point', true);
	if ($focal_point) {
		$split = explode(',', $focal_point);

		$post_thumb_atts['style'] = sprintf(
			'object-position: %s %s;',
			$split[0],
			$split[1]
		);
	}

	$raw_img = wp_get_attachment_image($post_thumb_id, $post_thumb_size, false, $post_thumb_atts);

	$img = sprintf(
		'<figure class="wp-block-image size-%s">%s</figure>',
		$post_thumb_size,
		$raw_img
	);

	return (string) render_block(
		[
			'blockName'    => 'core/image',
			'attrs'        => [
				'id'         => $post_thumb_id,
				'sizeSlug'   => $post_thumb_size,
				'focalPoint' => $focal_point,
			],
			'innerHTML'    => $img,
			'innerContent' => [$img],
		]
	);
}