<?php

$inc = sprintf('%s/includes', get_stylesheet_directory());
$blocks = sprintf('%s/blocks', $inc);
$blog_and_resource = sprintf('%s/blog-and-resource', $inc);
$news_and_events = sprintf('%s/news-and-events', $inc);
$partners = sprintf('%s/partners', $inc);

require_once $inc . '/theme-options.php';
require_once $inc . '/child_views.php';
require_once $inc . '/enqueuer.php';
require_once $inc . '/dashboard-settings.php';
require_once $inc . '/page.php';
require_once $inc . '/mega-menu.php';
require_once $inc . '/logo-slider.php';
require_once $inc . '/career-slider.php';
require_once $inc . '/partner-logo.php';

require_once $blog_and_resource . '/template.php';
require_once $news_and_events . '/template.php';
require_once $partners . '/template.php';

require_once $blocks . '/post-card.php';
require_once $blocks . '/signup-modal.php';
require_once $blocks . '/related-articles.php';
require_once $blocks . '/product-card.php';
require_once $blocks . '/recent-posts.php';
require_once $blocks . '/faq-accordion.php';
require_once $blocks . '/solution-card.php';
require_once $blocks . '/horizontal-card-scroll.php';
require_once $blocks . '/sennovate-hero-banner.php';
require_once $blocks . '/operating-model-grid.php';
require_once $blocks . '/services-tabs-section.php';
require_once $blocks . '/sennovate-values-grid.php';
require_once $blocks . '/sennovate-partners-grid.php';
require_once $blocks . '/sennovate-testimonial-slider.php';
require_once $blocks . '/sennovate-founder-section.php';
require_once $blocks . '/sennovate-cta-banner.php';
require_once $blocks . '/sennovate-page-hero.php';
require_once $blocks . '/sennovate-feature-grid.php';
require_once $blocks . '/sennovate-strike-team.php';
require_once $blocks . '/sennovate-engagement-models.php';
require_once $blocks . '/sennovate-faq-section.php';
require_once $blocks . '/sennovate-add-value.php';
require_once $blocks . '/sennovate-built-to-perform.php';
require_once $blocks . '/sennovate-fully-connected.php';
require_once $blocks . '/sennovate-keep-safe.php';
require_once $blocks . '/sennovate-reasons-grid.php';

/**
 * ACF JSON Sync
 */
add_filter('acf/settings/save_json', function($path) {
    return get_stylesheet_directory() . '/acf-json';
});

add_filter('acf/settings/load_json', function($paths) {
    unset($paths[0]);
    $paths[] = get_stylesheet_directory() . '/acf-json';
    return $paths;
});






/**
 * Current Year Shortcode
 */
add_shortcode('current_year', function() {
    return date('Y');
});
