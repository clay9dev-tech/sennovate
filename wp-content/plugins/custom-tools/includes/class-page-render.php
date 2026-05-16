<?php
if (! defined('ABSPATH')) {
	exit;
}

/**
 * Customizations to rendered pages.
 *
 * @since 1.0.0
 */
class Mandy_Page_Render {

	/**
	 * Sets up the class functionality.
	 *
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	public function __construct() {
		add_filter('body_class', [$this, 'body_classes']);
		add_filter('the_content', [$this, 'linked_img_classes'], 100, 1);
		add_filter('acf_the_content', [$this, 'linked_img_classes'], 100, 1);
	}

	/**
	 * Adds custom classes to the body element.
	 *
	 * @access public
	 * @since  1.0.0
	 * @param  array $classes Body classes
	 * @return array Updated body classes
	 */
	public function body_classes($classes) {

		// Add post/page slug if not present and template slug
		if (is_single() || is_page() && !is_front_page()) {
			if (!in_array(basename(get_permalink()), $classes)) {
				$classes[] = basename(get_permalink());
			}
			$classes[] = str_replace('.php', '', basename(get_page_template()));
		}

		// Add post type class
		$queried_obj = get_queried_object();

		if ($queried_obj && is_post_type_archive()) {
			$classes[] = 'post-type-' . $queried_obj->name;
		}

		if ($queried_obj && is_singular()) {
			$classes[] = 'post-type-' . $queried_obj->post_type;
		}

		// Remove unnecessary classes
		$home_id_class = 'page-id-' . get_option('page_on_front');
		$remove_classes = ['page-template-default', $home_id_class];

		$classes = array_diff($classes, $remove_classes);

		return $classes;
	}

	/**
	 * Add class(es) to anchors wrapped around images in the WYSIWYG.
	 *
	 * @access public
	 * @since  1.0.0
	 * @param  string $content HTML content
	 * @return string Updated HTML content
	 */
	public function linked_img_classes($content) {
		$classes = 'wp-image-link';
		$patterns = [];
		$replacements = [];
		$patterns[0] = '/<a(?![^>]*class)([^>]*)>\s*<img([^>]*)>\s*<\/a>/'; // matches img tag wrapped in anchor tag where anchor tag where anchor has no existing classes
		$replacements[0] = '<a\1 class="' . $classes . '"><img\2></a>';
		$content = preg_replace($patterns, $replacements, $content);
		return $content;
	}
}
