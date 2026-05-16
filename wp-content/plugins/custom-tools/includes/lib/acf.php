<?php
if (! defined('ABSPATH')) {
	exit;
}

/**
 * Retrieves post data including ACF field values.
 *
 * @since  1.0.0
 * @param  int|WP_Post|null $post Post ID or post object. Defaults to global $post.
 * @return array Post data
 */
function get_post_and_fields($post = null) {
	$post = get_post($post);
	if (!$post) {
		return false;
	}
	$acf = [];
	if (function_exists('get_fields')) {
		$acf = get_fields($post->ID) ? : [];
	}
	return array_merge( (array) $post, $acf, [
		'permalink' => get_the_permalink($post->ID),
	]);
}
