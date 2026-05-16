<?php
class Theme_Options {
	const THEME_OPTIONS = [];

	public static function setup() {
		add_filter('theme_options_fields', [__CLASS__, 'theme_options_fields']);
	}

	public static function theme_options_fields($fields) {
		return array_merge($fields, self::THEME_OPTIONS);
	}
}

add_action('after_setup_theme', ['Theme_Options', 'setup']);
