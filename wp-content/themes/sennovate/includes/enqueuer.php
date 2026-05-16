<?php

namespace ChildTheme;

class Enqueuer
{

	public static function setup()
	{
		add_action('wp_enqueue_scripts', [__CLASS__, 'enqueue_scripts'], PHP_INT_MAX);
		add_action('admin_enqueue_scripts', [__CLASS__, 'admin_enqueue_scripts'], PHP_INT_MAX);
		add_filter('get_custom_logo',  [__CLASS__, 'disable_custom_logo_dimensions'], 10, 3);
		add_shortcode( 'qb_year', [__CLASS__, 'current_year_shortcode'] );
		add_filter('wpseo_metabox_prio', [__CLASS__, 'yoasttobottom']);
		add_action('init', [__CLASS__, 'mandy_register_custom_button_styles']);

		//Wait until init so that it loads AFTER the base skeletor styles
		add_action('init', function () {
			add_editor_style(['build/main.css']);
		});
	}

	/**
	 * Showing the current year
	 */
	public static function current_year_shortcode() {
		$year = date('Y');
		return $year;
	}

	// Move Yoast to bottom
	public static function yoasttobottom() {
		return 'low';
	}

	public static function enqueue_scripts()
	{
		// Enque website font family - Open Sans & Inter
		wp_enqueue_style('Sennovate-fonts', 'https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap');

		// Modaal POPUP
		wp_enqueue_script('modaal-js', 'https://cdn.jsdelivr.net/npm/modaal@0.4.4/dist/js/modaal.min.js', array('jquery'), '0.4.4', true);
		wp_enqueue_style('modaal-css', 'https://cdn.jsdelivr.net/npm/modaal@0.4.4/dist/css/modaal.min.css', array(), '0.4.4', 'all');

		// Swiper Slider
		wp_enqueue_style('Swiper-Inner-css', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css', array(), '0.4.4', 'all');
		wp_enqueue_script('Swiper-Inner-js', 'https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js', array('jquery'), '0.4.4', true);

		// GSAP + ScrollTrigger — registered here, enqueued per-block as needed
		wp_register_script('gsap', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/gsap.min.js', array(), '3.12.5', true);
		wp_register_script('gsap-scrolltrigger', 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/ScrollTrigger.min.js', array('gsap'), '3.12.5', true);

		$child_theme_build_path = sprintf('%s/build', get_stylesheet_directory());
		$child_theme_build_url = sprintf('%s/build', get_stylesheet_directory_uri());

		$asset = require_once(sprintf('%s/main.asset.php', $child_theme_build_path));
		if (!$asset) {
			return;
		}

		wp_enqueue_script(
			'acqueon_build',
			sprintf('%s/main.js', $child_theme_build_url),
			$asset['dependencies'],
			$asset['version'],
			true
		);

		wp_enqueue_style(
			'acqueon_build',
			sprintf('%s/main.css', $child_theme_build_url),
			[],
			filemtime(sprintf('%s/main.css', $child_theme_build_path))
		);

		wp_enqueue_style(
			'acqueon-Silka-fonts',
			get_stylesheet_directory_uri() . '/fonts.css',
			array(), // Dependencies if any
			filemtime(get_stylesheet_directory() . '/fonts.css'), // Versioning by file modification time
			'all'
		);

		wp_enqueue_script(
			'mobile-menu',
			get_stylesheet_directory_uri() . '/src/scripts/mobile-menu.js',
			array(),
			filemtime(get_stylesheet_directory() . '/src/scripts/mobile-menu.js'),
			true
		);

	}

	public static function admin_enqueue_scripts()
	{
		$child_theme_build_path = sprintf('%s/build', get_stylesheet_directory());
		$child_theme_build_url = sprintf('%s/build', get_stylesheet_directory_uri());

		$admin_asset = require_once(sprintf('%s/admin.asset.php', $child_theme_build_path));
		if (!$admin_asset) {
			return;
		}

		wp_enqueue_script(
			'child_theme_admin',
			sprintf('%s/admin.js', $child_theme_build_url),
			$admin_asset['dependencies'],
			$admin_asset['version'],
			true
		);

		wp_enqueue_style(
			'child_theme_admin',
			sprintf('%s/admin.css', $child_theme_build_url),
			[],
			filemtime(sprintf('%s/admin.css', $child_theme_build_path))
		);
	}

	public static function disable_custom_logo_dimensions($html) {
		$html = preg_replace('/(width|height)="[0-9]*"\s/', "", $html);
		return $html;
	}

	public static function mandy_register_custom_button_styles() {
		// Outline style
		register_block_style(
			'core/button',
			array(
				'name'  => 'outline',
				'label' => __('Outline Button', 'clay9')
			)
		);

		// Text Arrow style
		register_block_style(
			'core/button',
			array(
				'name'  => 'button-arrow',
				'label' => __('Button Arrow', 'clay9')
			)
		);

		// Text Arrow style
		register_block_style(
			'core/button',
			array(
				'name'  => 'text-arrow',
				'label' => __('Text Arrow', 'clay9')
			)
		);

	}

}

add_action('after_setup_theme', ['ChildTheme\Enqueuer', 'setup']);
