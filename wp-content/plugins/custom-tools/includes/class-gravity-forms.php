<?php
if (! defined('ABSPATH')) {
	exit;
}

/**
 * Gravity Forms customizations.
 *
 * @since 1.0.0
 */
class Mandy_Gravity_Forms {

	/**
	 * Sets up the class functionality.
	 *
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	public function __construct() {
		add_action('wp_before_admin_bar_render', [$this, 'remove_gforms_menu'], 20);
		add_action('admin_init', [$this, 'remove_dashboard_widgets']);
		add_filter('gform_form_tag', [$this, 'gform_form_tag'], 10, 2);

		/**
		 * Moves Gravity Forms scripts to footer.
		 *
		 * @since 1.0.0
		 */
		add_filter('gform_init_scripts_footer', '__return_true');

		/**
		 * Enable field label visibility setting on field appearance tab.
		 *
		 * @since 1.0.0
		 */
		add_filter('gform_enable_field_label_visibility_settings', '__return_true');
	}

	/**
	 * Removes Gravity Forms menu from the admin bar.
	 *
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	public function remove_gforms_menu() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu('gform-forms');
	}

	/**
	 * Removes Gravity Forms widgets from the admin dashboard.
	 *
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	public function remove_dashboard_widgets() {
		remove_meta_box('rg_forms_dashboard', 'dashboard', 'normal');
	}

	/**
	 * Adds form title to a data attribute on the form element.
	 *
	 * @access public
	 * @since  1.0.0
	 * @param  string $form_tag The string containing the <form> tag.
	 * @param  object $form The current form.
	 * @return string The new <form> tag string.
	 */
	public function gform_form_tag($form_tag, $form) {
		$form_title = $form['title'];
		$form_tag = str_replace('<form', "<form data-formtitle='{$form_title}'", $form_tag);
		return $form_tag;
	}
}

