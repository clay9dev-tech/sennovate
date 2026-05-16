<?php

use \Mandy\Skeletor_Block;
use Mandy\SkeletorFeaturedImageFocalPoint;

if (!class_exists('\Mandy\Skeletor_Block')) {
	return;
}

class Related_Articles_Card extends Skeletor_Block {
	public static $title = 'Related Articles';
	public static $name = 'related_articles_card';

	public static $field_group = [
		[
			'key' => 'field_related_posts',
			'label' => 'Related Posts',
			'name' => 'related_posts',
			'type' => 'relationship',
			'post_type' => ['post', 'acq_resource'],
			'filters' => ['search', 'post_type', 'taxonomy'],
			'max' => 3,
			'return_format' => 'object',
		]
	];

	public static function render($block_data) {
		$related_posts = $block_data['related_posts'] ?? [];

		// If manually selected related posts are empty, fallback to automatic logic
		if (empty($related_posts)) {
			global $post;

			if (!isset($post) || !is_a($post, 'WP_Post')) {
				return ''; // safety check
			}

			$post_id = $post->ID;

			// Get categories of current post
			$categories = wp_get_post_categories($post_id);

			// Get fallback posts from same categories (excluding current post)
			$fallback_args = [
				'post_type'      => 'post',
				'posts_per_page' => 3,
				'post__not_in'   => [$post_id],
				'category__in'   => $categories,
				'orderby'        => 'date',
				'order'          => 'DESC',
			];

			$fallback_query = new WP_Query($fallback_args);
			$related_posts = $fallback_query->have_posts() ? $fallback_query->posts : [];
		}

		if (empty($related_posts)) {
			return ''; // still empty
		}

		echo '<div class="related-articles-cards">';
		foreach ($related_posts as $post_item) {
			echo Post_Card::render(['post' => $post_item]);
		}
		echo '</div>';
	}

}

add_action('after_setup_theme', ['Related_Articles_Card', 'init']);
