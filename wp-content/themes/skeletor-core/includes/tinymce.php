<?php
class TinyMCE_Customizations {	
	static $toolbar = [
		'formatselect',
		'styleselect',
		'bold',
		'italic',
		'underline',
		'forecolor',
		'bullist',
		'numlist',
		'indent',
		'outdent',
		'alignleft',
		'aligncenter',
		'alignright',
		'link',
		'unlink',
		'pastetext',
	];

	static function setup() {
		add_filter('tiny_mce_before_init', [__CLASS__, 'tiny_mce_before_init']);
		add_filter('acf/fields/wysiwyg/toolbars', [__CLASS__, 'acf_toolbar']);
	}

	/**
	 * Getting palette color dyncamiclly from theme.json file 
	*/
	static function dynamic_color()
	{
		$colors = [];
		if (class_exists('WP_Theme_JSON_Resolver')) {
			$colorPalette = [];
			$settings = WP_Theme_JSON_Resolver::get_theme_data()->get_settings();
			if (isset($settings['color']['palette']['theme'])) {
				$colorPalette = $settings['color']['palette']['theme'];
				foreach($colorPalette as $values) {
					$colors[] = $values['color'];
				}
			}
		}

		return $colors;
	}
	
	static function tiny_mce_before_init($options) {
		$options['paste_as_text'] = true;
		$options['body_class'] = 'core';
		$options['block_formats'] = 'Paragraph=p;Heading 1=h1;Heading 2=h2;Heading 3=h3;Heading 4=h4;Heading 5=h5;Heading 6=h6;Pre=pre;Blockquote=blockquote';
		$options['style_formats'] = json_encode([
			[
				'title' => 'Buttons',
				'items' => [
					[
						'title'    => 'Primary CTA',
						'selector' => 'a,button',
						'classes'  => 'cta cta-primary',
						'wrapper'  => false,
					],
					[
						'title'    => 'Secondary CTA',
						'selector' => 'a,button',
						'classes'  => 'cta cta-secondary',
						'wrapper'  => false,
					],
					[
						'title'    => 'Tertiary CTA',
						'selector' => 'a,button',
						'classes'  => 'cta cta-tertiary',
						'wrapper'  => false,
					],
				],
			],
			[
				'title' => 'Lists',
				'items' => [
					[
						'title'    => 'Checklist',
						'classes'  => 'is-style-check-list',
						'selector' => 'ul',
					],
				],
			],
			[
				'title' => 'Text',
				'items' => [],
			],
		]);

		$palette = array_reduce(static::dynamic_color(), function($palette = [], $color = null) {
			if (!is_null($color)) {
				$color = preg_replace('/^\#/', '', $color);
				$palette[] = sprintf('"%s", "%s"', $color, $color);
			}

			return $palette;
		});

		$options['textcolor_map'] = '[' . implode(', ', $palette) . ']';
		$options['style_formats_autohide'] = true;
		$options['toolbar1'] = implode(',', static::$toolbar);
		$options['toolbar2'] = '';

		return $options;
	}

	static function acf_toolbar($toolbars) {
		$toolbars['Full'][1] = static::$toolbar;

		return $toolbars;
	}
}

add_action('after_setup_theme', ['TinyMCE_Customizations', 'setup']);
