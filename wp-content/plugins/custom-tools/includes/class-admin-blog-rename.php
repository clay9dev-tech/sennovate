<?php
if (! defined('ABSPATH')) {
	exit;
}

/**
 * Renames core "Posts" post type to "Blog Posts" throughout WordPress.
 *
 * @since 1.0.0
 */
class Mandy_Admin_Blog_Rename {

	/**
	 * Sets up the class functionality.
	 *
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	public function __construct() {
		add_action('init', [$this, 'change_post_object_labels']);
		add_action('admin_menu', [$this, 'change_admin_menu_text']);
	}

	/**
	 * Changes "Posts" text in admin menu.
	 *
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	public function change_admin_menu_text() {
		global $menu;
		$menu[5][0] = 'Blog Posts';
	}

	/**
	 * Changes core "Post" post type labels.
	 *
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	public function change_post_object_labels() {
		global $wp_post_types;
		$labels = &$wp_post_types['post']->labels;
		$labels->add_new_item          = 'Add New Blog Post';
		$labels->all_items             = 'All Blog Posts';
		$labels->archives              = 'Blog Post Archives';
		$labels->attributes            = 'Blog Post Attributes';
		$labels->edit_item             = 'Edit Blog Post';
		$labels->insert_into_item      = 'Insert into blog post';
		$labels->name                  = 'Blog Posts';
		$labels->name_admin_bar        = 'Blog Post';
		$labels->new_item              = 'New Blog Post';
		$labels->not_found             = 'No blog posts found';
		$labels->not_found_in_trash    = 'No blog posts found in trash';
		$labels->search_items          = 'Search Blog Posts';
		$labels->singular_name         = 'Blog Post';
		$labels->uploaded_to_this_item = 'Uploaded to this blog post';
		$labels->view_item             = 'View Blog Post';
	}
}
