<?php
if (! defined('ABSPATH')) {
	exit;
}

/**
 * WordPress application customizations.
 *
 * @since 2.0.0
 */
class Mandy_WordPress {

	/**
	 * Sets up the class functionality.
	 *
	 * @access public
	 * @since  2.0.0
	 * @return void
	 */
	public function __construct() {
		add_action('get_the_excerpt', [$this, 'clean_excerpt'], 10, 2);
		add_action('excerpt_more', [$this, 'excerpt_more'], 10, 1);
	}

	/**
	 * Updates the excerpt to always end with a complete sentence.
	 * If first sentence is longer than excerpt_length, it will add the standard ellipsis.
	 *
	 * @access public
	 * @since  2.0.0
	 * @param  string $post_excerpt The post excerpt.
	 * @param  WP_Post $post Post object.
	 * @return string The filtered post excerpt.
	 */
	public function clean_excerpt($post_excerpt, $post) {
		$pos = strpos($post_excerpt, '.');
		if ($pos !== false) {
			$post_excerpt = strrpos($post_excerpt, '.') ? substr($post_excerpt, 0, strrpos($post_excerpt, '.') + 1) : false;
		}
		return $post_excerpt;
	}

	/**
	 * Changes the excerpt ellipsis.
	 *
	 * @access public
	 * @since  2.0.0
	 * @param  string $more The string shown within the more link.
	 * @return void
	 */
	public function excerpt_more($more_string) {
		return '…';
	}
}
