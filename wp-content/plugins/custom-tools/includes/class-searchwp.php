<?php
if (! defined('ABSPATH')) {
	exit;
}

/**
 * SearchWP customizations.
 *
 * @since 1.0.0
 */
class Mandy_SearchWP {

	/**
	 * Sets up the class functionality.
	 *
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	public function __construct() {
		add_action('wp_before_admin_bar_render', [$this, 'remove_searchwp_menu'], 20);
		add_filter('searchwp_related_meta_box_priority', [$this, 'related_meta_box_priority']);
		add_filter('searchwp\statistics\log', [$this, 'filter_spammy_searches'], 10, 2);

		/**
		 * Disables media attachments indexing.
		 *
		 * @since 1.0.0
		 */
		add_filter('searchwp_index_attachments', '__return_false');
	}

	/**
	 * Excludes spammy searches from SearchWP statistics
	 *
	 * @param bool $enabled If the search query is not ignored.
	 * @param object $query The Query being run (\SearchWP\Query).
	 * @return bool Whether to log the search (true) or not (false).
	 */
	public function filter_spammy_searches($enabled, $query) {
		$search_string = $query->get_keywords();

		// More than 30 characters long
		$long = (strlen($search_string ) > 30);

		// Starts with 'www'
		$www = ('www' === substr( $search_string, 0, 3));

		// Contains a forward slash
		$slashes = (false !== strpos($search_string, '/'));

		if ($long or $www or $slashes) {
			return false;
		}

		return true;
	}

	/**
	 * Removes SearchWP menu from the admin bar.
	 *
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	public function remove_searchwp_menu() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu('searchwp');
	}

	/**
	 * Changes SearchWP Related Content metabox priority to low in an attempt to keep it at the bottom of the page.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return string The priority.
	 */
	public function related_meta_box_priority() {
		return 'low';
	}
}
