<?php
if (!defined('ABSPATH')) {
	exit;
}

/**
 * Advanced Custom Fields customizations.
 *
 * @since   1.0.0
 */
class Mandy_Tools_ACF {

	/**
	 * Sets up the class functionality.
	 *
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	public function __construct() {
		add_filter('acf/fields/post_object/query', [$this, 'acf_fields_query_nodrafts'], 10, 3);
		add_filter('acf/fields/relationship/query', [$this, 'acf_fields_query_nodrafts'], 10, 3);
	}

	/**
	 * Filters ACF post object field queries to show only published posts.
	 *
	 * @access public
	 * @since  1.0.0
	 * @param  array  $args    The WP_Query args used to find choices.
	 * @param  array  $field   The field array containing all attributes & settings.
	 * @param  int    $post_id The current post ID being edited.
	 * @return array           New array of WP_Query arguments.
	 */
	public function acf_fields_query_nodrafts($args, $field, $post_id) {
		$args['post_status'] = 'publish';
		return $args;
	}
}
