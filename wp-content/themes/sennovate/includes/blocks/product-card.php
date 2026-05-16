<?php

use \Mandy\Skeletor_Block;
use Mandy\SkeletorFeaturedImageFocalPoint;
use WPSEO_Primary_Term;

if (!class_exists('\Mandy\Skeletor_Block')) {
    return;
}

class Product_Card extends Skeletor_Block
{
    public static $title = 'Product Card';
    public static $name = 'product_card';

	public static $field_group = [
		[
			'key' => 'field_product_card',
			'label' => 'Product Card',
			'name' => 'product_card',
			'type' => 'post_object',
			'post_type' => ['page'],
            'return_format' => 'object', // Important
            'ui' => 1,
        ],
		[
            'key' => 'field_product_card_add_padding_arround_content',
            'label' => 'Add Padding for Content',
            'name' => 'product_card_arround_content_padding',
            'type' => 'true_false',
            'ui' => 1,
            'ui_on_text' => 'Yes',
            'ui_off_text' => 'No',
		]
	];

    public static function before_render($block_data) {
        if (empty($block_data['product_card'])) {
            $block_data['product_card'] = []; // No posts selected
            return $block_data;
        }

        // Always treat as array
        $post = $block_data['product_card'];

        $featured_image = get_post_thumbnail_id($post);
        $image = $featured_image
            ? SkeletorFeaturedImageFocalPoint::featured_image_block($post, 'medium')
            : '<figure class="size-medium"><img class="size-medium no-thumbnail" src="' . esc_url(get_stylesheet_directory_uri() . '/images/no-thumbnail.png') . '" alt="' . esc_attr(get_the_title($post)) . '" /></figure>';

        $excerpt = get_the_excerpt($post);
        $trimmed_excerpt = wp_trim_words($excerpt, 15, '...');

        $block_data['id']           = $post->ID;
        $block_data['image']        = $image;
        $block_data['title']        = get_the_title($post);
        $block_data['content']      = get_the_excerpt( $post->ID );
        $block_data['href']         = get_the_permalink($post);
        $block_data['icon_list']    = get_field('products_list_fields', $post->ID);
        $block_data['cta']          = __('Learn More');

		if (isset_and_true($block_data, 'product_card_arround_content_padding')) {
			$block_data['add_padding'] = 'add-padding';
		}
    
        return $block_data;
    }

    public static function block_attributes($block_attributes, $block_data) {
        if (
            isset($block_data['product_card']) &&
            is_array($block_data['product_card']) &&
            !empty($block_data['product_card']['href'])
        ) {
            $block_attributes['href'] = esc_url($block_data['product_card']['href']);
        }
    
        return $block_attributes;
    }    
}

add_action('after_setup_theme', ['Product_Card', 'init']);
