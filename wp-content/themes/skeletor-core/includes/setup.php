<?php

/**
 * Sets theme defaults and features
 */

/**
 * Theme setup
 */
function mandy_theme_setup() {

	// Enable featured image support
	add_theme_support('post-thumbnails');

}

add_action('after_setup_theme', 'mandy_theme_setup', 9);
