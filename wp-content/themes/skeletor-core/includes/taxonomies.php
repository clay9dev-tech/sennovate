<?php

class Theme_Taxonomies {
	public static function setup() {
		add_filter('get_terms', [__CLASS__, 'modify_term_count'], 10, 2);
	}

	/**
	 * Return an array of names of (public) Taxonomies that are used in more than one
	 * (public) Post Type.
	 *
	 * @return array
	 */
	private static function get_shared_taxonomies(): array {
		$ret = [];
		$all_taxonomies = get_taxonomies([], 'objects');

		foreach ($all_taxonomies as $t) {
			if (!$t->public) {
				continue;
			}

			$post_types = array_filter($t->object_type, function($pt) {
				return $pt !== 'none';
			});

			if (count($post_types) > 1) {
				$ret[] = $t->name;
			}
		}

		return $ret;
	}

	/**
	 * retrieve a post_type specific count
	 * for our shared taxonomies
	 *
	 * @param array $terms
	 * @param array|string $taxonomy
	 * @return array
	 */
	public static function modify_term_count($terms, $taxonomy) {
		/**
		 * since there's a bevy of possiblities for what $terms could be
		 * let's do some checking
		 */
		if (is_wp_error($terms) || !is_array($terms)) {
			return $terms;
		}

		// this is only needed in the admin
		if (!is_admin()) {
			return $terms;
		}

		if (is_array($taxonomy)) {
			// let's not bother if it's for more than one taxonomy
			if (count($taxonomy) > 1) {
				return $terms;
			}

			$taxonomy = array_shift($taxonomy);
		}

		// if it's not one of our shared taxonomies, move on
		if (!in_array($taxonomy, self::get_shared_taxonomies(), true)) {
			return $terms;
		}

		if (!function_exists('get_current_screen')) {
			return $terms;
		}

		$screen = get_current_screen();
		if (!$screen) {
			return $terms;
		}

		switch (true) {
			//post_type edit screen is ok
			case $screen->base === 'edit':
			case $screen->id === sprintf('edit-%s', $taxonomy):
				# these are our desired options
				break;
			default:
				return $terms;
				break;
		}

		$updated_terms = array_map(function($term) use ($taxonomy, $screen) {
			if (!is_a($term, \WP_Term::class)) {
				return $term;
			}
			$term->count = get_term_count($taxonomy, $term->slug, $screen->post_type, false);
			return $term;
		}, $terms);

		return $updated_terms;
	}

}

add_action('after_setup_theme', ['Theme_Taxonomies', 'setup']);
