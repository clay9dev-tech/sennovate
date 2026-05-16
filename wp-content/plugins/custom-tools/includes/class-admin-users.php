<?php
if (! defined('ABSPATH')) {
	exit;
}

/**
 * Sets custom WordPress permissions and capabilities.
 *
 * @since 2.0.6
 */

class Mandy_Admin_Users {

	public function __construct() {
		add_action('admin_init', array($this, 'editor_capabilities'));
		add_filter('searchwp_settings_cap', array($this, 'searchwp_capabilities'));
	}

	/**
	 * Add capabilities to Editor users
	 *
	 * @access public
	 * @since 2.0.6
	 * @return void
	 */
	public function editor_capabilities() {
		$role = get_role('editor');
		$role->add_cap('gform_full_access');  // Gravity Forms
		$role->add_cap('edit_theme_options'); // Menus
	}

	/**
	 * Updates SearchWP settings capability so Editors can access.
	 *
	 * @access public
	 * @since 2.0.6
	 * @param  string $capability The capability.
	 * @return string The filtered capability.
	 */
	public function searchwp_capabilities($capability) {
		$capability = 'edit_posts';
		return $capability;
	}

}
