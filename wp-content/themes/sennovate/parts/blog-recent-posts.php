<?php
// Fetch recent posts
$term = get_the_terms($post, 'category');

$exclude_events_diary = true;
if ($term && !is_wp_error($term)) {
	foreach ($term as $category) {
		if ($category->slug === 'events-diary') {
			$exclude_events_diary = false;
			break;
		}
	}
}

$args = array(
	'post_type'      => 'post',
	'posts_per_page' => 4,
	'post_status'    => 'publish',
	'orderby'        => 'date',
	'order'          => 'DESC',
	'post__not_in'   => array( get_the_ID() ),
);

// Conditionally filter by category
if ($exclude_events_diary) {
	// Exclude 'events-diary'
	$events_diary_term = get_category_by_slug('events-diary');
	if ($events_diary_term) {
		$args['category__not_in'] = array($events_diary_term->term_id);
	}
} else {
	// Only include 'events-diary'
	$args['category_name'] = 'events-diary';
}

$recent_posts = new WP_Query($args);
?>

<?php if ($recent_posts->have_posts()) : ?>
<div class="blog-recent-post-widget">
	<h2 class="wp-block-heading eyebrow-underline">Editor's Picks</h2>
	<div class="sidebar-posts-wrapper">
		<?php while ($recent_posts->have_posts()) : $recent_posts->the_post(); ?>
			<a href="<?php the_permalink(); ?>" class="sidebar-post">
				<div class="sidebar-post-thumbnail">
					<?php if (has_post_thumbnail()) : ?>
						<?php the_post_thumbnail('thumbnail', ['alt' => esc_attr(get_the_title())]); ?>
					<?php else : ?>
						<img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/images/no-thumbnail.png'); ?>" alt="No Image Available">
					<?php endif; ?>
				</div>
				<div class="sidebar-post-content">
					<h3 class="sidebar-post-title"><?php the_title(); ?></h3>
				</div>
			</a>
		<?php endwhile; ?>
	</div>
</div>
<?php else : ?>
	<p>No Recent Posts Found.</p>
<?php endif; ?>

<?php wp_reset_postdata(); ?>
