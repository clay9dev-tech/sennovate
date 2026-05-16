<?php
if (! defined('ABSPATH')) {
	exit;
}

/**
 * Search customizations.
 *
 * @since 1.0.0
 */
class Mandy_Search {

	/**
	 * Sets up the class functionality.
	 *
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	public function __construct() {
		add_action('pre_get_posts', [$this, 'empty_search_queries']);
		add_action('template_redirect', [$this, 'custom_search_url']);
	}

	/**
	 * Prevents 404 on empty search queries.
	 *
	 * @access public
	 * @since  1.0.0
	 * @param  WP_Query $query The WP_Query instance (passed by reference).
	 * @return WP_Query Modified query.
	 */
	public function empty_search_queries($query) {
		global $wp_query;

		if (!is_admin() && !wp_doing_ajax() && $query->is_main_query()) {

			if (isset($_GET['s']) && $_GET['s'] === '') {
				$wp_query->set('s', ' ');
				$wp_query->is_search = true;
			}
		}

		return $query;
	}

	/**
	 * Redirects ?s=query searches to /search/query/.
	 *
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	public function custom_search_url() {
		global $wp_rewrite;

		if (!isset($wp_rewrite) || !is_object($wp_rewrite) || !$wp_rewrite->using_permalinks()) {
			return;
		}

		$keywords = urlencode(get_query_var('s'));
		$base = $wp_rewrite->search_base;
		$path = "/{$base}/{$keywords}/";

		if (function_exists('wpml_home_url')) {
			$redirect_url = wpml_home_url($path);
		} else {
			$redirect_url = home_url($path);
		}

		if (is_search() && !is_admin() && strpos($_SERVER['REQUEST_URI'], "/{$base}/") === false) {
			wp_redirect($redirect_url);
			exit();
		}
	}
}
