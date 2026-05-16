<?php
if (! defined('ABSPATH')) {
	exit;
}

/**
 * Redirects
 * Based on plugin by Mark Jaquith.
 * http://txfx.net/wordpress-plugins/nice-search
 *
 * @since 1.0.0
 */
class Mandy_Search_Custom_Urls {

	/**
	 * Sets up the class functionality.
	 *
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	public function __construct() {
		add_action('template_redirect', [$this, 'custom_search_url']);
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
