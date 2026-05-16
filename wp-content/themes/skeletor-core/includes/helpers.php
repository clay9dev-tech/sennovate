<?php

function find_field($field_name, $field_scopes = []) {
	if ($ret = get_field($field_name)) {
		return $ret;
	} else {
		foreach ($field_scopes as $scope) {
			if ($ret = get_field($field_name, $scope)) {
				return $ret;
			}
		}
	}

	return null;
}

function upload_get_contents($url) {
	$uploads = wp_upload_dir();
	$file = str_replace($uploads['baseurl'], $uploads['basedir'], $url);
	$ret = file_get_contents($file);
	$ret = preg_replace('/<\?xml version="1.0" encoding="UTF-8"\?>\s+/', '', $ret);
	return $ret;
}

/**
 * Reads in the custom_logo and if it is an SVG
 * returns the contents of the svg
 *
 * @param int $logo_id the ID of the media file to use
 * @return null|string
 */
function vtl_maybe_load_svg_logo($logo_id) {
	// translate to a full path
	$fullpath = get_attached_file($logo_id);
	if (!$fullpath) {
		return;
	}

	// bail if it doesn't exist
	if (!file_exists($fullpath)) {
		return;
	}

	$ext = pathinfo($fullpath, PATHINFO_EXTENSION);
	if (strtolower($ext) !== 'svg') {
		return;
	}

	$ret = file_get_contents($fullpath);
	$ret = preg_replace('/<\?xml version="1.0" encoding="UTF-8"\?>\s+/', '', $ret);
	return $ret;
}

/**
 * potentially renders the core site logo block
 *
 * When the `skeletor_user_core_site_logo` filter returns true
 * the theme will try to load a core/site-logo block
 *
 * When the `skeletor_inline_svg_site_logo` filter returns true
 * if a custom logo is used and it is an SVG,
 * it will be loaded in place of an IMG element
 * emulating the wp-block-site-logo block
 *
 * @return null|string
 */
function maybe_get_core_site_logo() {
	$use_site_logo = apply_filters('skeletor_use_core_site_logo', false);
	if (!$use_site_logo) {
		return;
	}

	// try to retrieve our theme mod: custom logo
	$logo_id = get_theme_mod( 'custom_logo' );
	if (!$logo_id) {
		return;
	}

	$try_to_load_svg = apply_filters('skeletor_inline_svg_site_logo', true);
	if ($try_to_load_svg) {
		$svg_logo = vtl_maybe_load_svg_logo($logo_id);
		if ($svg_logo) {
			return sprintf('
					<div class="wp-block-site-logo">
						<a href="%s" rel="home">
							%s
						</a>
					</div>
				',
				home_url(),
				$svg_logo
			);
		}
	}

	return render_block([
		'blockName'      => 'core/site-logo',
		'shouldSyncIcon' => false,
	]);
}

/**
 * renders a home link
 *
 * When the `skeletor_inline_svg_site_logo` filter returns true
 * if a custom logo is used and it is an SVG,
 * it will be loaded in place of an IMG element
 * emulating the wp-block-site-logo block
 *
 * @return void
 */
function home_link() {
	// maybe load site-logo
	$site_logo = maybe_get_core_site_logo();
	if ($site_logo) {
		return $site_logo;
	}

	// try to load logo from 'other content'
	if (function_exists('get_field') && ($site_logo_url = get_field('site_logo', 'options'))) {
		$site_logo = upload_get_contents($site_logo_url);
		if (function_exists('str_starts_with')) {
			$is_svg = str_starts_with($site_logo, '<svg');
		} else {
			$is_svg = substr($site_logo, 0, 4) === '<svg';
		}
		if (!$is_svg) {
			$site_logo = sprintf('<img src="%s" alt="%s site logo" class="site-logo" />', $site_logo_url, get_bloginfo('name', 'display'));
		}
	} else {
		$site_logo = get_bloginfo('name', 'display');
	}

	$additional_classes = apply_filters('skeletor_additional_home_link_classes', []);
	if (!is_array($additional_classes)) {
		$additional_classes = [$additional_classes];
	}
	$classes = array_merge(['home-link'], $additional_classes);
	$classes = array_filter($classes);

	return sprintf(
		'<a href="%s" class="%s">%s <span class=screen-reader-text>%s</span></a>',
		site_url(),
		implode(' ', $classes),
		$site_logo,
		'Home'
	);
}
/**
 * Display the classes for the html element
 * @param string|array $class One or more classes to add to the class list.
 */
function html_class($class = '') {
	echo 'class="' . join(' ', get_html_class($class)) . '"';
}

/**
 * Retrieve the classes for the html element as an array
 * @param string|array $class One or more classes to add to the class list.
 * @return array Array of classes.
 */
function get_html_class($class = '') {
	$classes = array();

	if (!empty($class)) {
		if (!is_array($class)) {
			$class = preg_split('#\s+#', $class);
		}
		$classes = array_merge($classes, $class);
	} else {
		$class = array();
	}

	$classes = array_map('esc_attr', $classes);

	$classes = apply_filters('html_class', $classes, $class);

	return array_unique($classes);
}


/**
 * Returns ACF Field data for the specified block. This is convenient when
 * traversing blocks from parse_blocks() where the field data is in the raw
 * "post meta" format. Pass a field name in the second argument to get only
 * that specific field.
 *
 * @param array $block
 * @param string? $field_name
 * @return mixed
 */
function get_block_acf($block, $field_name = null) {
	acf_setup_meta($block['attrs']['data'], $block['attrs']['id'], true);
	if ($field_name) {
		$result = get_field($field_name);
	} else {
		$result = get_fields();
	}
	acf_reset_meta();

	return $result;
}

/**
 * Traverse over $blocks recursively and run $callback on each block.
 *
 * @param array $blocks
 * @param callable $callback
 * @return void
 */
function traverse_blocks(array &$blocks, callable $callback): void {
	foreach ($blocks as &$block) {
		$callback($block);

		if (!isset($block['innerBlocks']) || !is_array($block['innerBlocks'])) {
			continue;
		}

		traverse_blocks($block['innerBlocks'], $callback);
	}
}

/**
 * Find the number of posts for a given term in a taxonomy
 *
 * @param string $taxonomy
 * @param string $term_slug
 * @param string $post_type
 * @param boolean $only_published
 * @return int
 */
function get_term_count($taxonomy, $term_slug, $post_type, $only_published = true) {
	$params = [
		'fields'      => 'ids',
		'numberposts' => -1,
		'post_type'   => $post_type,
		'post_status' => $only_published ? 'publish' : 'any',
		'tax_query'   => [
			[
				'taxonomy' => $taxonomy,
				'field'    => 'slug',
				'terms'    => $term_slug,
			],
		],
	];

	$posts = get_posts($params);

	return count($posts);
}

if (!function_exists('get_current_url')) {
	/**
	 * get a string representing the URL of the current request
	 * NOTE this will include the query string if applicable
	 *
	 * @param boolean $fully_qualified
	 * @return string
	 */
	function get_current_url(bool $fully_qualified = false) {
		global $wp;
		$url = add_query_arg( $_SERVER['QUERY_STRING']?? [], '', $wp->request);

		if ($fully_qualified) {
			return home_url( $url );
		}

		return $url;
	}
}
