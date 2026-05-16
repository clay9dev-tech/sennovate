<?php
class Asset_Enqueuer
{
	public static $defer_styles = [
		'skeletor',
		'wp-block-library',
	];

	public static function setup()
	{
		add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue_scripts']);
		add_action('admin_enqueue_scripts', [__CLASS__, 'admin_enqueue_scripts']);
		add_filter('style_loader_tag', [__CLASS__, 'defer_style_loader_tag'], 10, 3);

		remove_action('wp_print_styles', 'print_emoji_styles');
	}

	public static function defer_style_loader_tag($tag, $handle, $href)
	{
		$defer_styles = apply_filters('defer_styles', self::$defer_styles);

		if (!in_array($handle, $defer_styles, true)) {
			return $tag;
		}

		return sprintf(
			'<link rel="preload" href="%s" as="style" onload="this.onload=null;this.rel=\'stylesheet\'"><noscript>%s</noscript>',
			$href,
			$tag
		);
	}

	public static function enqueue_scripts()
	{
		wp_enqueue_style(
			'skeletor',
			get_template_directory_uri() . '/assets/dist/main.css',
			false,
			filemtime(get_template_directory() . '/assets/dist/main.css')
		);

		$script = include(get_template_directory() . '/assets/dist/main.asset.php');
		if (!$script) {
			return;
		}

		wp_enqueue_script(
			'skeletor',
			get_template_directory_uri() . '/assets/dist/main.js',
			$script['dependencies'],
			$script['version'],
			true
		);
	}

	public static function admin_enqueue_scripts()
	{
		wp_enqueue_style(
			'skeletor_admin',
			get_template_directory_uri() . '/assets/dist/admin.css',
			false,
			filemtime(get_template_directory() . '/assets/dist/admin.css')
		);

		$script = include(get_template_directory() . '/assets/dist/main.asset.php');
		if (!$script) {
			return;
		}

		wp_enqueue_script(
			'skeletor_admin',
			get_template_directory_uri() . '/assets/dist/admin.js',
			$script['dependencies'],
			$script['version'],
			true
		);
	}
}

add_action('after_setup_theme', ['Asset_Enqueuer', 'setup']);
