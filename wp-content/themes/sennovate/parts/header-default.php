<?php

use Mandy\MandyMustache;

$args = [
	'home_link'        => home_link(),
	'site_link'        => site_url(),
	'acq_announcement' => get_field('acqueon_announcement_content', 'option'),
];

// Fetch Main Menu
if (has_nav_menu('main_nav')) {
	$args['main_menu'] = wp_nav_menu([
		'theme_location'  => 'main_nav',
		'container'       => 'div',
		'container_class' => 'main-menu-wrapper',
		'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
		'menu_class'      => 'menu main-menu group',
		'menu_id'         => 'main-menu',
		'depth'           => 0,
		'echo'            => 0,
	]);
}

if (has_nav_menu('main_utility')) {
	$args['utility_menu'] = wp_nav_menu([
		'theme_location'  => 'main_utility',
		'container'       => 'div',
		'container_class' => 'utility-menu-wrapper',
		'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
		'menu_class'      => 'menu utility-menu group',
		'menu_id'         => 'utility-menu',
		'depth'           => 0,
		'echo'            => 0,
	]);
}

if (has_nav_menu('main_mob_nav')) {
	$args['main_mob_nav'] = wp_nav_menu([
		'theme_location'  => 'main_mob_nav',
		'container'       => 'div',
		'container_class' => 'mobile-utility-menu-wrapper',
		'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
		'menu_class'      => 'menu mobile-utility-menu group',
		'menu_id'         => 'mobile-utility-menu',
		'depth'           => 0,
		'echo'            => 0,
	]);
}

$toggle_icon = '
<svg width="24px" height="24px" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" class="hamburger">
	<line class="top-bun" x1="0" y1="12" x2="24" y2="12"></line>
	<line class="burger" x1="0" y1="12" x2="24" y2="12"></line>
	<line class="bottom-bun" x1="0" y1="12" x2="24" y2="12"></line>
</svg>
';
$args['toggle_icon'] = apply_filters('skeletor_toggle_icon_html', $toggle_icon);

// Render mustache template
if (class_exists('Mandy\MandyMustache')) {
	$header_markup = MandyMustache::render('template/header-default', $args, true);
	$html = apply_filters('header_default_markup', $header_markup);
	echo $html;
}
