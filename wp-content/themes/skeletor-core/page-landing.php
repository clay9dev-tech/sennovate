<?php
/*
 * Template Name: Landing Page
 */

$page_args = [
	'page_template' => 'landing',
];

get_header(null, (isset($page_args) ? $page_args : false));

if (post_password_required()) {
	get_template_part('parts/content-protected');
} else {
	if (have_posts()) {
		the_post();
		the_content();
	}
}

get_footer(null, (isset($page_args) ? $page_args : false));
