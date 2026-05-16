<?php
global $post;

get_header();

if (post_password_required()) {
	get_template_part('parts/content-protected');
} else {
	if (have_posts()) {
		the_post();
		
		do_action('before_post_content', $post);
		the_content();
		do_action('after_post_content', $post);
	}
}

get_footer();
