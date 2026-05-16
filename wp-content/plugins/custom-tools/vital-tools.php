<?php
/*
 * Plugin Name:  WordPress Tools
 * Plugin URI:   https://wordpress.com
 * Description:  WordPress functions, helpers, and customizations.
 * Version:      2.1.0
 * Requires at least: 5.3
 * Requires PHP: 7.2
 * Author:       WordPress
 * Author URI:   https://wordpress.com
 * Text Domain:  quick-build-tools
 * Domain Path:  /languages
 */

if (! defined('ABSPATH')) {
	exit;
}

require 'plugin-update-checker/plugin-update-checker.php';

$update_checker = Puc_v4_Factory::buildUpdateChecker(
	'https://bitbucket.org/madebymandy/mandy-tools',
	__FILE__,
	'mandy-tools'
);

if (!class_exists('Mandy_Tools')) {

	/**
	 * Main plugin class.
	 */
	#[AllowDynamicProperties]
	class Mandy_Tools {

		/**
		 * The single instance of Mandy_Tools.
		 *
		 * @var    object
		 * @access private
		 * @since  1.0.0
		 */
		private static $instance;

		/**
		 * The main plugin file.
		 *
		 * @var    string
		 * @access public
		 * @since  1.0.0
		 */
		public $file;

		/**
		 * Gets class instance.
		 *
		 * @access public
		 * @since  1.0.0
		 * @return Mandy_Tools
		 */
		public static function instance() {
			if (!isset(self::$instance) && !(self::$instance instanceof Mandy_Tools)) {
				self::$instance = new Mandy_Tools;
				self::$instance->includes();
				self::$instance->init = new Mandy_Tools_Init();
			}
			return self::$instance;
		}

		/**
		 * Sets up the class functionality.
		 *
		 * @access public
		 * @since  1.0.0
		 * @return void
		 */
		public function __construct() {
			$this->file = plugin_dir_path(__FILE__);
			add_action('plugins_loaded', [$this, 'load_plugin_textdomain']);
			add_action('admin_enqueue_scripts', [$this, 'vtl_enqueue_custom_search_script']);
		}

		/**
		 * Register and enqueue a custom scripts
		 */
		public function vtl_enqueue_custom_search_script() {
			wp_enqueue_script('vtl_custom_search_script_js', plugins_url('js/nav-menu-search.js', __FILE__), ['jquery']);
			wp_localize_script('vtl_custom_search_script_js', 'vtl_search_ajax_object', array('ajax_url' => admin_url('admin-ajax.php')));
		}

		/**
		 * Includes required plugin files.
		 *
		 * @access private
		 * @since  1.0.0
		 * @return void
		 */
		private function includes() {

			// Required plugin components
			require_once($this->file . 'includes/plugin-helpers.php');

			// Plugin classes
			require_once($this->file . 'includes/class-acf.php');
			require_once($this->file . 'includes/class-admin-blog-rename.php');
			require_once($this->file . 'includes/class-admin-nav-search.php');
			require_once($this->file . 'includes/class-admin-render.php');
			require_once($this->file . 'includes/class-admin-reveal-ids.php');
			require_once($this->file . 'includes/class-admin-users.php');
			require_once($this->file . 'includes/class-development.php');
			require_once($this->file . 'includes/class-gravity-forms.php');
			require_once($this->file . 'includes/class-media-library.php');
			require_once($this->file . 'includes/class-oembed.php');
			require_once($this->file . 'includes/class-page-cleanup.php');
			require_once($this->file . 'includes/class-page-render.php');
			require_once($this->file . 'includes/class-performance.php');
			require_once($this->file . 'includes/class-search.php');
			require_once($this->file . 'includes/class-searchwp.php');
			require_once($this->file . 'includes/class-theme-setup.php');
			require_once($this->file . 'includes/class-wordpress.php');
			require_once($this->file . 'includes/class-yoast.php');

			// Initialize classes
			require_once($this->file . 'includes/class-mandy-tools-init.php');

			// Plugin libraries
			require_once($this->file . 'includes/lib/acf.php');
			require_once($this->file . 'includes/lib/development.php');
			require_once($this->file . 'includes/lib/gravity-forms.php');
			require_once($this->file . 'includes/lib/helpers.php');
			require_once($this->file . 'includes/lib/yoast.php');
		}

		/**
		 * Loads the plugin text domain.
		 *
		 * @access public
		 * @since  1.0.0
		 * @return void
		 */
		public function load_plugin_textdomain() {
			load_plugin_textdomain('mandy-tools', false, basename(dirname(__FILE__)) . '/languages/');
		}
	}

	/**
	 * Initializes Mandy_Tools.
	 *
	 * @access public
	 * @since  1.0.0
	 * @return Mandy_Tools
	 */
	function mandy_tools_run() {
		return Mandy_Tools::instance();
	}

	mandy_tools_run();
}
