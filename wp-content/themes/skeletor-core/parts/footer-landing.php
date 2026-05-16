<?php

use Mandy\MandyMustache;

// Render mustache template
if (class_exists('Mandy\MandyMustache')) {
	$footer_markup = MandyMustache::render('template/footer-landing', $args, true);
	$html = apply_filters('footer_landing_markup', $footer_markup);
	echo $html;
}
