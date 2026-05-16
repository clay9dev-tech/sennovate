<?php

use Mandy\MandyMustache;

$args = [
	'home_link' => home_link(),
];

// Render mustache template
if (class_exists('Mandy\MandyMustache')) {
	$header_markup = MandyMustache::render('template/header-landing', $args, true);
	$html = apply_filters('header_landing_markup', $header_markup);
	echo $html;
}
