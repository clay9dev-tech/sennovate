<?php

while(have_posts()) {
	the_post();
	do_action('render_blog_post_card', $post);
}
