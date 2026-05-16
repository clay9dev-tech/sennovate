<?php

class Theme_Rest_API {
	public static function setup() {
		add_filter('rest_endpoints', [__CLASS__, 'rest_endpoints']);
		remove_action('wp_head', 'rest_output_link_wp_head', 10);
		remove_action('template_redirect', 'rest_output_link_header', 11);
	}

	public static function rest_endpoints($endpoints) {
		if (!is_user_logged_in()) {
			unset($endpoints['/wp/v2/users']);
			unset($endpoints['/wp/v2/users/(?P<id>[\d]+)']);
			unset($endpoints['/wp/v2/users/me']);
		}

		return $endpoints;
	}
}

add_action('after_setup_theme', ['Theme_Rest_API', 'setup']);
