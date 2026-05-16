<?php

use \Mandy\Skeletor_Block;
use Mandy\SkeletorFeaturedImageFocalPoint;
use WPSEO_Primary_Term;

if (!class_exists('\Mandy\Skeletor_Block')) {
    return;
}

class Post_Card extends Skeletor_Block
{
    public static $title = 'Post Card';
    public static $name = 'post_card';

    /**
     * Get the categories associated with the post
     *
     * @param int $id
     * @return array
     */
	protected static function _get_categories($id)
	{
		$taxonomy = apply_filters('category', 'category', $id);

		$terms = \get_the_terms($id, $taxonomy);
		if ($terms && !\is_wp_error($terms)) {
			return $terms;
		}

		return [];
	}

    public static function get_primary_category($post) {
        if ( ! $post instanceof WP_Post ) {
            return null;
        }

        // Detect taxonomy based on post type
        $taxonomy = 'category';
        if ( get_post_type($post) === 'acq_resource' ) {
            $taxonomy = 'resource_type';
        }

        $primary_term = null;

        // Try Yoast SEO primary term
        if ( class_exists('WPSEO_Primary_Term') ) {
            $yoast_primary = new \WPSEO_Primary_Term($taxonomy, $post->ID);
            $term_id       = $yoast_primary->get_primary_term();
            $term          = get_term($term_id, $taxonomy);

            if ( ! is_wp_error($term) && $term instanceof WP_Term ) {
                $primary_term = $term;
            }
        }

        // Fallback: first assigned term
        if ( ! $primary_term ) {
            $terms = get_the_terms($post->ID, $taxonomy);
            if ( ! empty($terms) && ! is_wp_error($terms) ) {
                $primary_term = array_shift($terms);
            }
        }

        return $primary_term instanceof WP_Term ? $primary_term : null;
    }

    public static function before_render($block_data) {
        if (empty($block_data['post'])) {
            $block_data['post'] = []; // No posts selected
            return $block_data;
        }

        // Always treat as array
        $post = $block_data['post'];

        $featured_image = get_post_thumbnail_id($post);
        $image = $featured_image
            ? SkeletorFeaturedImageFocalPoint::featured_image_block($post, 'medium')
            : '<figure class="size-medium"><img class="size-medium no-thumbnail" src="' . esc_url(get_stylesheet_directory_uri() . '/images/no-thumbnail.png') . '" alt="' . esc_attr(get_the_title($post)) . '" /></figure>';

        $category = self::get_primary_category($post);
        $categories_data = [];
        
        if (!empty($category)) {
            $are_terms_clickable = true;
        
            $categories_data = [
                'name'      => $category->name,
                'permalink' => $are_terms_clickable ? get_term_link($category) : '',
            ];
        }

        $excerpt = get_the_excerpt($post);
        $trimmed_excerpt = wp_trim_words($excerpt, 15, '...');

        $author_id = get_the_author_meta('ID');
        $avatar_url = get_avatar_url($author_id, ['size' => 24]);
        $block_data['id']           = $post->ID;
        $block_data['image']        = $image;
        $block_data['categories']   = $categories_data;
        $block_data['title']        = get_the_title($post);
        $block_data['content']      = $trimmed_excerpt;
        $block_data['author']       = get_the_author_meta('display_name', $post->post_author);
        $block_data['href']         = get_the_permalink($post);
        $block_data['cta']          = __('Continue Reading');
        $block_data['author_image'] = $avatar_url;
        $block_data['class'] = $block_data['type'] ? $block_data['type'] : 'all';

        if(isset($block_data['date_enable'])) {
            $block_data['post_date']         = get_the_date('j M, Y');
        }

        if ($start_data = get_field('start_date')) {
             $block_data['post_date'] = $start_data;
        }
        //$block_data['post_read']['icon'] = get_stylesheet_directory_uri() . '/images/clock-black.svg';

        $readmin = \Mandy\Skeletor\Blog\Template::get_reading_time();
        $readmin = ($readmin != 0) ? $readmin : 1;
        $block_data['post_read_time'] = $readmin . ' Min Read';

        return $block_data;
    }

    public static function block_attributes($block_attributes, $block_data) {
        if (
            isset($block_data['post']) &&
            is_array($block_data['post']) &&
            !empty($block_data['post']['href'])
        ) {
            $block_attributes['href'] = esc_url($block_data['post']['href']);
        }
    
        return $block_attributes;
    }
    
}

add_action('after_setup_theme', ['Post_Card', 'init']);
