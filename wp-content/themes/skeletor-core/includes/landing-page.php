<?php

/**
 * Landing Page functions
 */

/**
 * Checks if a landing page was requested based on set criteria
 * @return boolean
 */
function is_landing_page() {

	switch (true) {
		case is_page_template('page-landing.php'):
		case is_singular('resource'):
			$return_value = true;
			break;

		default:
			$return_value = false;
			break;
	}

	return apply_filters('skeletor_is_landing_page', $return_value);
}

/**
 * Adds body class to all landing pages
 */
add_filter('body_class', function($classes) {
	if (is_landing_page()) {
			$classes[] = 'landing-page';
	}
	return $classes;
});
