<?php
class Block_Editor_Customization {

	const BLOCK_PATTERN_CATEGORIES = [];

	public static function setup() {
		add_theme_support('editor-styles');

		add_editor_style([
			'assets/dist/main.css',
		]);

		add_post_type_support('block', 'revisions');
		static::register_block_pattern_categories();
	}

	public static function register_block_pattern_categories() {
		remove_theme_support('core-block-patterns');

		foreach (self::BLOCK_PATTERN_CATEGORIES as $name => $block_pattern_category) {
			register_block_pattern_category($name, $block_pattern_category);
		}
	}
}

add_action('after_setup_theme', ['Block_Editor_Customization', 'setup']);
