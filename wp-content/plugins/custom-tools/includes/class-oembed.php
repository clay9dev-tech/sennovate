<?php
if (! defined('ABSPATH')) {
	exit;
}

/**
 * Register oEmbed providers for services
 * not natively registered in WordPress
 *
 * @since   2.0.9
 */
class Mandy_Oembed {

	public function __construct() {
		add_action('init', [__CLASS__, 'register_wistia_provider']);
	}

	/**
	 * adds a wistia provider to the oembed functions
	 * native to WordPress
	 *
	 * @return void
	 */
	public static function register_wistia_provider() {
		if (!function_exists('wp_oembed_add_provider')) {
			return;
		}

		wp_oembed_add_provider('/https?:\/\/(.+)?(wistia.com|wi.st)\/(medias|embed)\/.*/', 'http://fast.wistia.com/oembed', true);
	}
}
