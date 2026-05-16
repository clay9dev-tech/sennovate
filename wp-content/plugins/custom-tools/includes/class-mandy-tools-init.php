<?php
if (! defined('ABSPATH')) {
	exit;
}

/**
 * Initializates all plugin classes.
 *
 * @since   1.0.0
 */
class Mandy_Tools_Init {

	/**
	 * Sets up the class functionality.
	 *
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	public function __construct() {
		$acf = new Mandy_Tools_ACF();
		$admin_blog_rename = new Mandy_Admin_Blog_Rename();
		$admin_render = new Mandy_Admin_Render();
		$admin_users = new Mandy_Admin_Users();
		$development = new Mandy_Development();
		$gravity_forms = new Mandy_Gravity_Forms();
		$media_library = new Mandy_Media_Library();
		$oemved = new Mandy_Oembed();
		$page_cleanup = new Mandy_Page_Cleanup();
		$page_render = new Mandy_Page_Render();
		$performance = new Mandy_Performance();
		$search = new Mandy_Search();
		$searchwp = new Mandy_SearchWP();
		$theme_setup = new Mandy_Theme_Setup();
		$wordpress = new Mandy_WordPress();
		$yoast = new Mandy_Yoast();

		if ((defined('WP_DEBUG') && WP_DEBUG === true) && is_admin()) {
			$admin_reveal_ids = new Mandy_Admin_Reveal_Ids();
		}
	}
}
