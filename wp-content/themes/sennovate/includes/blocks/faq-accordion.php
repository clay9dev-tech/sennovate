<?php
use \Mandy\Skeletor_Block;

if (!class_exists('\Mandy\Skeletor_Block')) {
	return;
}

class Faq_Accordion extends Skeletor_Block {
	public static $title = 'FAQ';
	public static $name = 'faq_accordion';

	public static $field_group = [
		[
			'key'               => 'field_faq_block',
			'label'             => 'FAQs',
			'name'              => 'faq_block',
			'type'              => 'relationship',
			'post_type'         => 'faq',
			'filters'           => ['search', 'taxonomy'],
			'post_status'       => 'publish',
			'instructions'      => 'Choose the post, that will appear on the FAQ block',
			'required'          => 0,
			'conditional_logic' => 0,
			'return_format'     => 'object',
			'allow_null'        => 1,
			'multiple'          => 1,
			'ui'                => 1,
		],
	];

	public static function before_render($block_data) {
		if (!$block_data['faq_block']) {
			return $block_data;
		}
		foreach ($block_data['faq_block'] as $key => $faq) {
			$faq->post_content = apply_filters('the_content', $faq->post_content);
			$block_data['faqs']['faq'][$key]['posts'] = $faq;
		}
		return $block_data;
	}
}

add_action('after_setup_theme', ['Faq_Accordion', 'init']);
