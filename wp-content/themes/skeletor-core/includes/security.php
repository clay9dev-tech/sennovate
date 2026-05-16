<?php
class Security_Customizations {
	const HEADERS = [
		'X-Frame-Options'           => 'SAMEORIGIN',
		'X-XSS-Protection'          => '1; mode=block',
		'X-Content-Type-Options'    => 'nosniff',
		'Strict-Transport-Security' => 'max-age=31536000; includeSubdomains; preload',
	];

	const CONTENT_SECURITY_POLICY = [
		'script-src' => [
			"'self'",
			"'unsafe-inline'",
			"'unsafe-eval'",
			'*.hotjar.com',
			'www.googleadservices.com',
			'www.googletagmanager.com',
			'www.google-analytics.com',
		],
		'style-src'  => [
			"'self'",
			"'unsafe-inline'",
			'fonts.googleapis.com',
		],
		'img-src'    => [
			"'self'",
			'data:',
			'*.gravatar.com',
			'wp-rocket.me',
			'www.google-analytics.com',
			'www.googletagmanager.com',
			'googleads.g.doubleclick.net',
			'www.google.com',
			'*.wpengine.com',
			'*.w.org',
		],
		'worker-src' => [
			"'self'",
			'blob:',
		],
	];

	public static function setup() {
		add_filter('the_password_form', [__CLASS__, 'the_password_form']);
		add_filter('protected_title_format', [__CLASS__, 'protected_title_format']);
		add_filter('posts_where', [__CLASS__, 'exclude_protected']);
		add_action('wp_head', [__CLASS__, 'noindex_protected'], 2);

		//Remove shortlink http header and meta tag
		remove_action('wp_head', 'wp_shortlink_wp_head', 10);
		remove_action('template_redirect', 'wp_shortlink_header', 11);

		//Bail out here if the headers have already gone out
		if (headers_sent()) {
			return;
		}

		foreach (self::HEADERS as $header_key => $header_val) {
			header("{$header_key}: {$header_val}");
		}

		// header('Content-Security-Policy: ' . self::get_content_security_policy());
	}

	/**
	 * Customize protected post password form output
	 */
	public static function the_password_form() {
		global $post;
		$label = 'pwbox-' . (empty($post->ID) ? rand() : $post->ID);

		?>
		<form action="<?php echo esc_url(site_url('wp-login.php?action=postpass', 'login_post')); ?>" method="post" class="post-password-form">
			<label for="<?php echo $label; ?>">Password</label>
			<input name="post_password" id="<?php echo $label; ?>" type="password" placeholder="Enter password">
			<button type="submit" name="Submit" value="<?php echo esc_attr('Submit'); ?>">Log In</button>
		</form>
		<?php
	}

	/**
	 * Remove "Protected:" text from protected post titles
	 */
	public static function protected_title_format($title) {
		return '%s';
	}
	/**
	 * Alters query in main loop to exclude protected posts
	 */
	public static function exclude_protected($where = '') {
		global $wpdb;

		if (!is_single()
			&& !is_page()
			&& !is_admin()
			&& !(defined('REST_REQUEST') && REST_REQUEST)) {
			$where .= " AND {$wpdb->posts}.post_password = ''";
		}

		return $where;
	}


	/**
	 * Add 'noindex' meta robots tag to protected posts
	 */
	public static function noindex_protected() {
		global $post;
		if (!empty($post->post_password)) {
			echo '<meta name="robots" content="noindex">' . "\n";
		}
	}

	private static function get_content_security_policy() {
		$content_security_policy = self::CONTENT_SECURITY_POLICY;

		return array_reduce(array_keys($content_security_policy), function($output, $key) use ($content_security_policy) {
			$value = $content_security_policy[$key];
			$output .= sprintf('%s %s;', $key, implode(' ', $value));

			return $output;
		}, '');
	}
}

add_action('after_setup_theme', ['Security_Customizations', 'setup']);
