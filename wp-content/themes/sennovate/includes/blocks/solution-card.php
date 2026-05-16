<?php
use \Mandy\Skeletor_Block;

if (!class_exists('\Mandy\Skeletor_Block')) {
	return;
}

class Solution_Card extends Skeletor_Block {
	public static $title = 'Solution Card';
	public static $name  = 'solution_card';

	public static $field_group = [
		[
			'key'           => 'field_6892824b2fcc2',
			'label'         => 'Icon',
			'name'          => 'card_icon',
			'type'          => 'image',
			'return_format' => 'array',
			'library'       => 'all',
			'preview_size'  => 'medium'
		],
		[
			'key' 			=> 'field_6892820e2fcc0',
			'label' 		=> 'Heading',
			'name' 			=> 'card_heading',
			'type' 			=> 'text',
		],
		[
			'key' 			=> 'field_689282342fcc1',
			'label' 		=> 'Description',
			'name' 			=> 'card_description',
			'type' 			=> 'text',
		],
		[
			'key' 			=> 'field_689282692fcc3',
			'label' 		=> 'Button',
			'name' 			=> 'card_button',
			'type' 			=> 'link',
			'return_format' => 'array',
		],
		[
			'key' 			=> 'field_689282aa2fcc4',
			'label' 		=> 'Background',
			'name' 			=> 'card_background_color',
			'type' 			=> 'color_picker',
			'return_format' => 'string',
		],
		[
			'key' 			=> 'field_689282cf2fcc5',
			'label' 		=> 'Radius',
			'name' 			=> 'card_border_radius',
			'type' 			=> 'number',
		]
	];

	public static function before_render($block_data) {
		$block_data['icon'] 			= get_field('card_icon');
		$block_data['heading'] 			= get_field('card_heading') ?: '';
		$block_data['description'] 		= get_field('card_description') ?: '';
		$block_data['button'] 			= get_field('card_button');
		$block_data['cta'] 				= __('Learn More');
		$block_data['background_color'] = get_field('card_background_color') ?: '#F7F8FA';
		$block_data['border_radius'] 	= get_field('card_border_radius') ?: '10';
		return $block_data;
	}

}

add_action('after_setup_theme', ['Solution_Card', 'init']);
