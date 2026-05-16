<?php
if (! defined('ABSPATH')) {
	exit;
}

/**
 * WordPress admin customizations.
 *
 * @since 1.0.0
 */
class Mandy_Admin_Render {

	/**
	 * Sets up the class functionality.
	 *
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	public function __construct() {
		add_action('wp_before_admin_bar_render', [$this, 'remove_admin_bar_menus'], 20);
		add_filter('admin_bar_menu', [$this, 'replace_howdy'], 9992);
		add_filter('admin_footer_text', [$this, 'add_footer_text']);
		add_action('admin_init', [$this, 'remove_dashboard_widgets']);
		add_action('admin_init', [$this, 'remove_post_metaboxes']);
		add_action('admin_init', [$this, 'remove_page_metaboxes']);
		add_action('admin_init', [$this, 'add_post_types_nav_menu']);

		/**
		 * Disables default admin color scheme picker.
		 *
		 * @since 1.0.0
		 */
		remove_action('admin_color_scheme_picker', 'admin_color_scheme_picker');
	}

	/**
	 * Removes admin bar menus.
	 *
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	public function remove_admin_bar_menus() {
		global $wp_admin_bar;
		$wp_admin_bar->remove_menu('customize'); // Customizer link
	}

	/**
	 * Changes the user greeting text in the admin bar.
	 *
	 * @access public
	 * @since  1.0.0
	 * @param  WP_Admin_Bar $wp_admin_bar WP_Admin_Bar instance, passed by reference.
	 * @return void
	 */
	public function replace_howdy($wp_admin_bar) {
		$my_account = $wp_admin_bar->get_node('my-account');
		$newtitle   = str_replace(__('Howdy,', 'mandy-tools'), __('Logged in as', 'mandy-tools'), $my_account->title);
		$wp_admin_bar->add_node([
			'id'    => 'my-account',
			'title' => $newtitle,
		]);
	}

	/**
	 * Changes the text in the admin footer.
	 *
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	public function add_footer_text() {
		printf(
			'%s | <a href="https://www.clay9.com/" target="_blank">%s</a>',
			__('Powered by WordPress', 'mandy-tools'),
			__('Made by Clay9', 'mandy-tools')
		);
	}

	/**
	 * Removes widgets from the admin dashboard.
	 *
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	public function remove_dashboard_widgets() {
		remove_action('welcome_panel', 'wp_welcome_panel');            // Welcome to WordPress
		remove_meta_box('dashboard_right_now', 'dashboard', 'core');   // At a Glance
		remove_meta_box('dashboard_primary', 'dashboard', 'normal');   // WordPress Events & News
		remove_meta_box('dashboard_quick_press', 'dashboard', 'side'); // Quick Draft
	}

	/**
	 * Removes metaboxes from post editor.
	 *
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	public function remove_post_metaboxes() {
		remove_meta_box('postcustom', 'post', 'normal');    // Custom fields
		remove_meta_box('trackbacksdiv', 'post', 'normal'); // Trackbacks
	}

	/**
	 * Removes metaboxes from page editor
	 *
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	public function remove_page_metaboxes() {
		remove_meta_box('postcustom', 'page', 'normal');    // Custom fields
		remove_meta_box('postexcerpt', 'page', 'normal');   // Excerpt
		remove_meta_box('trackbacksdiv', 'page', 'normal'); // Trackbacks
	}

	/**
	 * Renders Post Type Index Pages meta box on Menus page.
	 *
	 * @access public
	 * @since  2.0.1
	 * @return void
	 */
	public function render_add_post_types_nav_menu() {
		$post_types = get_post_types([
			'public'   => true,
			'_builtin' => false,
		], 'objects', 'and');
		?>
		<div id="posttype-archive" class="posttypediv">
			<ul class="posttype-tabs add-menu-item-tabs">
				<li class="tabs"><?php _e('Index Pages'); ?></li>
			</ul>
			<div class="tabs-panel tabs-panel-active">
				<ul class="categorychecklist form-no-clear">
				<?php
				$i = 0;
				foreach ($post_types as $post_type) {
					if ($post_type->has_archive === true) {
						?>
						<li>
							<label class="menu-item-title"><input type="checkbox" class="menu-item-checkbox" name="menu-item[-<?php echo $i; ?>][menu-item-object-id]" value="<?php echo $post_type->name; ?>"> <?php echo $post_type->labels->all_items; ?></label>
							<input type="hidden" class="menu-item-object" name="menu-item[-<?php echo $i; ?>][menu-item-object]" value="<?php echo $post_type->name; ?>">
							<input type="hidden" class="meni-item-type" name="menu-item[-<?php echo $i; ?>][menu-item-type]" value="post_type_archive">
							<input type="hidden" class="menu-item-title" name="menu-item[-<?php echo $i; ?>][menu-item-title]" value="<?php echo $post_type->labels->name; ?>">
							<input type="hidden" class="menu-item-url" name="menu-item[-<?php echo $i; ?>][menu-item-url]" value="<?php echo get_post_type_archive_link($post_type->name); ?>">
							<input type="hidden" class="menu-item-target" name="menu-item[-<?php echo $i; ?>][menu-item-target]" value="">
							<input type="hidden" class="menu-item-attr-title" name="menu-item[-<?php echo $i; ?>][menu-item-attr-title]" value="">
							<input type="hidden" class="menu-item-classes" name="menu-item[-<?php echo $i; ?>][menu-item-classes]" value="">
							<input type="hidden" class="menu-item-xfn" name="menu-item[-<?php echo $i; ?>][menu-item-xfn]" value="">
						</li>
						<?php
					}
					$i++;
				}
				?>
				</ul>
			</div>
			<p class="button-controls">
				<span class="add-to-menu">
					<input type="submit" class="button-secondary submit-add-to-menu right" value="Add to Menu" name="add-post-type-menu-item" id="submit-posttype-archive">
					<span class="spinner"></span>
				</span>
			</p>
		</div>
		<?php
	}

	/**
	 * Adds Post Type Index Pages meta box on Menus page.
	 *
	 * @access public
	 * @since  2.0.1
	 * @return void
	 */
	public function add_post_types_nav_menu() {
		add_meta_box('post_types_meta_box', __('Post Type Index Pages'), [$this, 'render_add_post_types_nav_menu'], 'nav-menus', 'side', 'default');
	}
}
