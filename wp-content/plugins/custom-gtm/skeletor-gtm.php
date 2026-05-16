<?php
/*
	Plugin Name: WordPress GTM
	Plugin URI:  https://wordpress.com
	Description: Skeletor Theme Options Add-On to configure Google Tag Manager and Google Analytics.
	Version:     1.0.1
	Requires at least: 5.2
	Requires PHP: 7.0
	Author:      WordPress
	Author URI:  https://wordpress.com
	Text Domain: quickbuild
*/
namespace Skeletor;

defined('ABSPATH') || exit;

class Google_Tag_Manager {
	/**
	 * The Google Tag Manager javascript embed, copied from GTM but with %s
	 * instead of the Account ID.
	 * @var string
	 */
	const GTM_SNIPPET = <<<'GTM'
<script>
// Google Tag Manager
(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','%s');
</script>
GTM;

	/**
	 * The Google Tag Manager noscript, copied from GTM but with %s instead
	 * of the Account ID.
	 * @var string
	 */
	const GTM_NOSCRIPT = <<<'GTMNS'
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=%s" height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
GTMNS;

	/**
	 * The Google Analytics embed snippet, but with %1$s where the Account ID
	 * goes.
	 * @var string
	 */
	const GA_SNIPPET = <<<'GA'
<script async src="https://www.googletagmanager.com/gtag/js?id=%1$s"></script>
<script>
window.dataLayer = window.dataLayer || [];
function gtag(){dataLayer.push(arguments);}
gtag('js', new Date());
gtag('config', '%1$s');
</script>
GA;

	/**
	 * An array describing the ACF fields that will be added to the Skeletor
	 * Theme Options field group.
	 * @var array
	 */
	const THEME_OPTIONS = [
		[
			'key'   => 'field_theme_opts_tab_gtm',
			'label' => 'Google Tag Manager',
			'type'  => 'tab',
		],
		[
			'key'   => 'field_theme_opts_google_tag_manager_id',
			'type'  => 'text',
			'name'  => 'gtm_id',
			'label' => 'Google Tag Manager ID',
		],
		[
			'key'   => 'field_theme_opts_google_tag_manager_gaid',
			'type'  => 'text',
			'name'  => 'ga_id',
			'label' => 'Google Analytics ID',
		],
	];

	/**
	 * Called on after_setup_theme. Set up filters/actions
	 *
	 * @return void
	 */
	public static function setup() : void {
		add_filter('theme_options_fields', [__CLASS__, 'add_theme_options']);

		if (function_exists('get_field')) {
			add_action('wp_head', [__CLASS__, 'wp_head'], PHP_INT_MIN);
			add_action('wp_body_open', [__CLASS__, 'wp_body_open'], PHP_INT_MIN);
		} else {
			add_action('admin_notices', [__CLASS__, 'no_acf_notice']);
		}
	}

	/**
	 * Called on admin_notices if get_field() is not defined. Pops a warning
	 * message indicating that this plugin required Advaned Custom Fields.
	 *
	 * @return void
	 */
	public static function no_acf_notice() : void {
		$class = 'notice notice-warning is-dismissible';
		$message = __('WARNING: Skeletor GTM requires Advanced Custom Fields!', 'mandytechnologies');

		printf('<div class="%1$s"><p>%2$s</p></div>', esc_attr($class), esc_html($message));
	}

	/**
	 * Filter on theme_options_fields to add
	 * Google_Tag_Manager::THEME_OPTIONS to the global options created by
	 * Skeletor_Theme_Options.
	 *
	 * @param array $options
	 * @return array
	 */
	public static function add_theme_options(array $options) : array {
		return array_merge($options, self::THEME_OPTIONS);
	}

	/**
	 * Action called on wp_head to output the GTM_SNIPPET and GA_SNIPPET at
	 * the top of the head.
	 *
	 * @return void
	 */
	public static function wp_head() : void {
		if ($gtm_id = get_field('gtm_id', 'options')) {
			printf(self::GTM_SNIPPET, $gtm_id);
		}

		if ($ga_id = get_field('ga_id', 'options')) {
			printf(self::GA_SNIPPET, $ga_id);
		}
	}

	/**
	 * Action called on wp_body_open to output the noscript version of the
	 * Google Tag Manager snippet.
	 *
	 * @return void
	 */
	public static function wp_body_open() : void {
		if ($gtm_id = get_field('gtm_id', 'options')) {
			printf(self::GTM_NOSCRIPT, $gtm_id);
		}
	}
}

add_action('after_setup_theme', ['Skeletor\Google_Tag_Manager', 'setup']);
