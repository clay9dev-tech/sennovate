<?php
if (! defined('ABSPATH')) {
	exit;
}

/**
 * Returns the form object for a given Gravity Form ID.
 *
 * @since  1.0.0
 * @param  integer $form_id The ID of the form to be returned.
 * @return array|boolean The form info or false.
 */
function get_gravity_form_info($form_id) {
	if (class_exists('GFAPI')) {
		return GFAPI::get_form($form_id);
	} else {
		return false;
	}
}
