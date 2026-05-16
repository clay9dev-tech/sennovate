<?php

use Mandy\MandyMustache;

global $post;
$selected_post_id = get_option('custom_footer_options');
if ($selected_post_id) {
	$post = get_post($selected_post_id);
	if ($post && $post->post_type === 'wp_block') {
		$args['footer_default'] = apply_filters('the_content', $post->post_content);
	}
}

$args['theme_url'] = get_stylesheet_directory_uri();

// Fetch ACF Footer Options
$args['footer_logo']      = get_field('footer_logo', 'option');
$args['footer_address']   = get_field('footer_address', 'option');
$args['footer_phone']     = get_field('footer_phone', 'option');
$args['footer_email']     = get_field('footer_email', 'option');
$args['footer_socials']   = get_field('footer_socials', 'option');
$args['footer_certs']     = get_field('footer_certs', 'option');
$args['footer_menu_columns'] = get_field('footer_menu_columns', 'option');
$args['footer_copyright'] = get_field('footer_copyright', 'option');
$args['footer_legal_menu_id'] = get_field('footer_legal_menu', 'option');

// Process Menu Columns
if ($args['footer_menu_columns']) {
    foreach ($args['footer_menu_columns'] as &$column) {
        $menu_id = $column['menu'];
        if ($menu_id) {
            $menu_items = wp_get_nav_menu_items($menu_id);
            $column['links'] = [];
            if ($menu_items) {
                foreach ($menu_items as $item) {
                    $column['links'][] = [
                        'text' => $item->title,
                        'url'  => $item->url
                    ];
                }
            }
        }
    }
}

// Process Legal Menu
if ($args['footer_legal_menu_id']) {
    $legal_menu_items = wp_get_nav_menu_items($args['footer_legal_menu_id']);
    $args['footer_legal_links'] = [];
    if ($legal_menu_items) {
        foreach ($legal_menu_items as $item) {
            $args['footer_legal_links'][] = [
                'text' => $item->title,
                'url'  => $item->url
            ];
        }
    }
}

// Handle empty address for BR support
if ($args['footer_address']) {
    $args['footer_address'] = do_shortcode(nl2br($args['footer_address']));
}

if ($args['footer_copyright']) {
    $args['footer_copyright'] = do_shortcode($args['footer_copyright']);
}

// Map social icons
if ($args['footer_socials']) {
    foreach ($args['footer_socials'] as &$social) {
        $platform = $social['platform'];
        // Default mapping if images exist in theme
        $social['icon_url'] = $args['theme_url'] . "/images/{$platform}.svg";
        
        // Handle Twitter/X special case if needed
        if ($platform === 'twitter') {
            $social['icon_url'] = $args['theme_url'] . "/images/twitter.svg";
        }
    }
}

// Fallback for Logo
if (!$args['footer_logo']) {
    $args['footer_logo'] = 'https://sennovate.clay9dev.com/wp-content/uploads/2026/05/logo-white.svg';
}

// Render mustache template
if (class_exists('Mandy\MandyMustache')) {
	MandyMustache::render('template/footer-default', $args);
}
