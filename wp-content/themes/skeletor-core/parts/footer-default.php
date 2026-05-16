<?php

use Mandy\MandyMustache;


$args = [
	'home_link' => home_link(),
];

// Fetch Main Menu
if (has_nav_menu('footer_nav')) {
	$args['footer_menu'] = wp_nav_menu([
		'theme_location'  => 'footer_nav',
		'container'       => 'div',
		'container_class' => 'footer-menu-wrapper',
		'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
		'menu_class'      => 'menu footer-menu group',
		'menu_id'         => 'footer-menu',
		'depth'           => 0,
		'echo'            => 0,
	]);
}

$args['copyright_text'] = str_replace('{year}', gmdate('Y'), get_option('options_copyright_text', ''));

// Render mustache template
if (class_exists('Mandy\MandyMustache')) {
	$footer_markup = MandyMustache::render('template/footer-default', $args, true);
	$html = apply_filters('footer_default_markup', $footer_markup);
	echo $html;
}
