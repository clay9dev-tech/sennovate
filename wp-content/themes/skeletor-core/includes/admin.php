<?php
class Admin {
	public static function setup() {
		add_action('login_head', [__CLASS__, 'login_logo']);
		add_filter('login_headerurl', [__CLASS__, 'login_headerurl']);
		add_filter('login_headertext', [__CLASS__, 'login_headerurl']);
		add_filter('login_errors', [__CLASS__, 'login_errors']);

		// comment/remove the following line to enable loading remote block patterns
		add_filter('should_load_remote_block_patterns', '__return_false');
	}

	/**
	 * obfuscate error messages on certain login error types
	 *
	 * @param string $error_message
	 * @return string
	 */
	public static function login_errors($error_message) {
		global $errors;
		/**
		 * NOTE: woocommerce calls the login_errors filter
		 * witout registering any global errors
		 */
		if (!$errors || !is_wp_error($errors)) {
			return $error_message;
		}

		$error_codes = $errors->get_error_codes();

		$target_codes = [
			'incorrect_password',
			'invalid_username',
			'invalid_email',
		];

		if (!array_intersect($target_codes, $error_codes)) {
			return $error_message;
		}

		return '<strong>ERROR</strong>: Invalid username or password.';
	}

	public static function login_logo() {
		echo "<style>
		body.login #login h1 a {
			background: url('" . get_site_url() . "/wp-admin/images/wordpress-logo.svg') no-repeat scroll center top transparent;
			background-size: 84px;
		}
		</style>";
	}

	/**
	 * Adds site URL to login page logo link
	 */
	public static function login_headerurl() {
		return esc_url(home_url());
	}

	/**
	 * Adds site name to login page logo link title
	 */
	function login_headertext() {
		return get_bloginfo('name');
	}
}

add_action('after_setup_theme', ['Admin', 'setup']);
