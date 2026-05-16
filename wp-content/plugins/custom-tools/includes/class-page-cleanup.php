<?php
if (! defined('ABSPATH')) {
	exit;
}

/**
 * Cleanup of rendered page markup.
 */
class Mandy_Page_Cleanup {

	/**
	 * Sets up the class functionality.
	 *
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	public function __construct() {
		add_action('init', [$this, 'wp_head_tidy']);
		add_filter('tiny_mce_plugins', [$this, 'disable_emojis_tinymce']);
		add_filter('wp_resource_hints', [$this, 'disable_emojis_remove_dns_prefetch'], 10, 2);

		// Disable emoji scripts and styles
		remove_action('wp_head', 'print_emoji_detection_script', 7);
		remove_action('admin_print_scripts', 'print_emoji_detection_script');
		remove_action('wp_print_styles', 'print_emoji_styles');
		remove_action('admin_print_styles', 'print_emoji_styles');
		remove_filter('the_content_feed', 'wp_staticize_emoji');
		remove_filter('comment_text_rss', 'wp_staticize_emoji');
		remove_filter('wp_mail', 'wp_staticize_emoji_for_email');

		// Removes comments RSS feed from wp_head
		add_filter('feed_links_show_comments_feed', '__return_false');
	}

	/**
	 * Cleans up the wp_head() markup.
	 *
	 * @access public
	 * @since 1.0.0
	 * @return void
	 */
	public function wp_head_tidy() {
		// Removes RSS feed links for comments, categories, tags, authors, etc.
		remove_action('wp_head', 'feed_links_extra', 3);
		// Removes XML-RPC RSD link
		remove_action('wp_head', 'rsd_link');
		// Removes Windows Live Writer link
		remove_action('wp_head', 'wlwmanifest_link');
		// Removes relational links for the posts adjacent to the current post
		remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
		// Removes relational link for post shortlink
		remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);
		// Removes generator meta tag
		remove_action('wp_head', 'wp_generator');
		// Removes inline CSS used by Recent Comments widget
		global $wp_widget_factory;
		if (isset($wp_widget_factory->widgets['WP_Widget_Recent_Comments'])) {
			remove_action('wp_head', [$wp_widget_factory->widgets['WP_Widget_Recent_Comments'], 'recent_comments_style']);
		}
	}

	/**
	 * Filter function used to remove the TinyMCE emoji plugin.
	 *
	 * @access public
	 * @since  1.0.0
	 * @param  array $plugins TinyMCE plugins.
	 * @return array Difference betwen the two arrays or empty array.
	 */
	public function disable_emojis_tinymce($plugins) {
		if (is_array($plugins)) {
			return array_diff($plugins, ['wpemoji']);
		}
		return [];
	}

	/**
	 * Remove emoji CDN hostname from DNS prefetching hints.
	 *
	 * @access public
	 * @since  1.0.0
	 * @param  array  $urls URLs to print for resource hints.
	 * @param  string $relation_type The relation type the URLs are printed for.
	 * @return array Difference betwen the two arrays.
	 */
	public function disable_emojis_remove_dns_prefetch($urls, $relation_type) {
		if ($relation_type === 'dns-prefetch') {
			$emoji_svg_url_bit = 'https://s.w.org/images/core/emoji/';
			foreach ($urls as $key => $url) {
				if (strpos($url, $emoji_svg_url_bit) !== false) {
					unset($urls[$key]);
				}
			}
		}
		return $urls;
	}
}
