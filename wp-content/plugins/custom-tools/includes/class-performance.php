<?php
if (! defined('ABSPATH')) {
	exit;
}

/**
 * Performance tweaks.
 *
 * @since 1.0.0
 */
class Mandy_Performance {

	/**
	 * Sets up the class functionality.
	 *
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	public function __construct() {
		add_filter('wp_revisions_to_keep', [$this, 'limit_revisions'], 10, 2);
	}

	/**
	 * Limits the number of post revisions.
	 *
	 * @access public
	 * @since  1.0.0
	 * @param  integer $num Number of revisions to keep.
	 * @param  WP_Post $post The current post object.
	 * @return int The new number of revisions to keep.
	 */
	public function limit_revisions($num, $post) {
		$num = 5;
		return $num;
	}
}
