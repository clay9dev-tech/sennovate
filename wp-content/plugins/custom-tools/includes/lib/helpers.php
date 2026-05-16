<?php
if (! defined('ABSPATH')) {
	exit;
}

/**
 * Returns a key value from an array or false if it doesn't exist.
 *
 * @since  1.0.0
 * @param  array $arr Array to check.
 * @param  string $key Key name you want.
 * @return string|boolean Key value or false.
 */
function isset_and_true($arr, $key) {
	if (isset($arr[$key]) && $arr[$key]) {
		return $arr[$key];
	} else {
		return false;
	}
}

/**
 * Gets inline SVG markup.
 *
 * @since 1.0.0
 * @param  string $file File name (if ending in /{something}.svg, use that for full path instead of concatenating).
 * @param  boolean $echo Echo SVG file contents or return if false.
 * @param  string $path Path of image directory relative to theme's root.
 * @return string Markup of SVG file.
 */
function get_inline_svg($file, $echo = true, $path = 'assets/images') {
	if (preg_match('/^(.*?)\/(.*?)\.svg$/i', $file)) {
		$file_path = $file;
	} else {
		$file_path = get_template_directory() . '/' . $path . '/' . $file;
	}

	if (file_exists($file_path)) {
		if ($echo === true) {
			echo file_get_contents($file_path);
		} else {
			return file_get_contents($file_path);
		}
	}
}

/**
 * Gets inline SVG from file URL.
 * USE SPARINGLY!
 *
 * @since  1.0.0
 * @param  string $file URL of SVG.
 * @param  boolean $echo Echo SVG file contents or return if false.
 * @return string Markup of SVG file.
 */
function get_url_inline_svg($file, $echo = true) {
	$headers = @get_headers($file); // phpcs:ignore
	$response = substr($headers[0], 9, 3);
	if (!$headers || $response !== '200') {
		return false;
	} else {
		if ($echo === true) {
			echo file_get_contents($file);
		} else {
			return file_get_contents($file);
		}
	}
}

/**
 * Returns actual disk path of asset from given absolute URI.
 *
 * @since  1.0.0
 * @param  string $uri Absolute URI.
 * @return string Disk path.
 */
function get_local_image_path($uri = false) {
	if ($uri) {
		return ABSPATH . preg_replace('/.*\/\/(.*?)\/(.*?)/', '\2', $uri);
	}
}

/**
 * Creates web-friendly slug from any string.
 *
 * @since  1.0.0
 * @param  string $text String to convert.
 * @return string Web-friendly slug.
 */
function create_slug($text) {
	$text = strtolower($text);
	$text = preg_replace('/[^a-z0-9_\s-]/', '', $text);
	$text = preg_replace('/[\s-]+/', ' ', $text);
	$text = preg_replace('/[\s_]/', '-', $text);
	return $text;
}

/**
 * Tests if current post is an ancestor of given post ID.
 *
 * @since  1.0.0
 * @param  integer $pid Post ID of parent.
 * @return boolean
 */
function is_tree($pid) {
	global $post;
	if (is_page($pid)) {
		return true;
	}
	$anc = get_post_ancestors($post->ID);
	foreach ($anc as $ancestor) {
		if (is_page() && $ancestor == $pid) {
			return true;
		}
	}
	return false;
}

/**
 * Returns post ID of the topmost parent of the current post.
 * For use within the loop.
 *
 * @since  1.0.0
 * @return integer Post ID.
 */
function get_post_top_ancestor_id() {
	global $post;
	if ($post->post_parent) {
		$ancestors = array_reverse(get_post_ancestors($post->ID));
		return $ancestors[0];
	}
	return $post->ID;
}

/**
 * Returns a YouTube video ID from video URL.
 *
 * @since  1.0.0
 * @param  string $url Video page URL.
 * @return string Video ID.
 */
function get_youtube_id($url) {
	$pattern =
		'%^# Match any youtube URL
		(?:https?://)?  # Optional scheme. Either http or https
		(?:www\.)?      # Optional www subdomain
		(?:             # Group host alternatives
		  youtu\.be/    # Either youtu.be,
		| youtube\.com  # or youtube.com
		  (?:           # Group path alternatives
			/embed/     # Either /embed/
		  | /v/         # or /v/
		  | /watch\?v=  # or /watch\?v=
		  )             # End path alternatives.
		)               # End host alternatives.
		([\w-]{10,12})  # Allow 10-12 for 11 char youtube id.
		$%x';
	$result = preg_match($pattern, $url, $matches);
	if (false !== $result) {
		return $matches[1];
	}
	return false;
}

/**
 * Returns the current active post type.
 *
 * @since  1.0.0
 * @return string|null Post type name or null.
 */
function get_current_post_type() {
	global $post, $typenow, $current_screen;

	switch (true) {
		// Get from current post
		case ($post && $post->post_type):
			$post_type = $post->post_type;
			break;

		// Get from $typenow global for wp-admin pages
		case ($typenow):
			$post_type = $typenow;
			break;

		// Get from $current_screen global for wp-admin pages
		case ($current_screen && $current_screen->post_type):
			$post_type = $current_screen->post_type;
			break;

		// Get from the post_type query string
		case (isset($_REQUEST['post_type'])):
			$post_type = sanitize_key($_REQUEST['post_type']);
			break;

		// Get via current post ID
		case (isset($_REQUEST['post'])):
			$post_type = get_post_type($_REQUEST['post']);
			break;

		default:
			$post_type = null;
			break;
	}

	return $post_type;
}


/**
 * Returns _blank target if passed URL is not this site's hostname.
 *
 * @since  1.0.0
 * @param  string $url The URL
 * @param  string $echo Echo or return the target.
 * @return string The target attribute.
 */
function the_link_target($url, $echo = true) {
	$parsed_url = parse_url($url);
	if ($parsed_url && array_key_exists('host', $parsed_url)) {
		if ($parsed_url['host'] !== $_SERVER['HTTP_HOST']) {
			$target = 'target="_blank"';
		} else {
			$target = 'target="_self"';
		}
	}
	if ($echo === true) {
		echo $target;
	} else {
		return $target;
	}
}

/**
 * get_template_part(), but return a string instead of outputting it.
 *
 * @param String $template_name
 * @param String $part_name
 * @param Array $args
 * @return String
 */
function load_template_part($template_name, $part_name = null, $args = []) {
	ob_start();
	get_template_part($template_name, $part_name, $args);
	$var = ob_get_contents();
	ob_end_clean();
	return $var;
}
