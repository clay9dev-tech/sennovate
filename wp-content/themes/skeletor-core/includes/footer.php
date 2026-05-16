<?php
class Theme_Footer {
	const THEME_OPTIONS = [
		[
			'key'   => 'field_theme_opts_tab_footer',
			'label' => 'Site Footer',
			'type'  => 'tab',
		],
		[
			'key'           => 'field_theme_opts_footer_copy_text',
			'label'         => 'Copyright Text',
			'name'          => 'copyright_text',
			'type'          => 'text',
			'instructions'  => 'Use the tag <code style="font-style: normal;">{year}</code> to place the current year.',
			'default_value' => 'Copyright © {year}',
			'placeholder'   => 'Copyright © {year}',
		],
	];

	public static function add_theme_options($options) {
		return array_merge($options, self::THEME_OPTIONS);
	}
}

add_filter('theme_options_fields', ['Theme_Footer', 'add_theme_options']);
