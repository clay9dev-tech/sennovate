<?php

use \Mandy\Skeletor_Block;
use Mandy\SkeletorFeaturedImageFocalPoint;

if (!class_exists('\Mandy\Skeletor_Block')) {
	return;
}

class Recent_Posts extends Skeletor_Block {
	public static $title = 'Recent Posts';
	public static $name = 'recent_posts';

	public static $field_group = [
		[
			'key' => 'field_recent_posts',
			'label' => 'Recent Posts',
			'name' => 'recent_posts',
			'type' => 'relationship',
			'post_type' => ['post', 'acq_resource'],
			'filters' => ['search', 'post_type', 'taxonomy'],
			'max' => 4,
			'return_format' => 'object',
		]
	];

	public static function render($block_data) {
		$recent_posts = $block_data['recent_posts'] ?? [];

		if (empty($recent_posts)) {
			return '';
		}

		echo '<div class="recent-posts">';

		// Left column - first post
		echo '<div class="column-left">';
		echo Post_Card::render(['post' => $recent_posts[0], 'type' => 'large-and-small']);
		echo '</div>';

		// Right column - remaining posts
		echo '<div class="column-right">';
		foreach (array_slice($recent_posts, 1) as $post) {
			echo Post_Card::render(['post' => $post, 'type' => 'two-column']);
		}
		echo '</div>';

		echo '</div>';

	}
}

add_action('after_setup_theme', ['Recent_Posts', 'init']);
