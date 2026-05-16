<?php
global $post;
get_header();

$qo = get_queried_object();

do_action('before_archive_loop', $qo);

$archive_post_classes = ['archive-posts'];
if (is_a($qo, 'WP_Post_Type')) {
	$archive_post_classes[] = sprintf('post-type-%s', $qo->name);
}
$archive_post_classes = apply_filters('archive_loop_classes', $archive_post_classes, $qo);
?>
<div class='post-main'>

	<?php do_action('render_filter'); ?>

	<div class="post-content">
		<?php do_action('render_archive_grid_section_title'); ?>
		<section class="archive-posts">
			<?php if ( have_posts() ) : ?>
				<?php
				$post_index = 1;
				while ( have_posts() ) {
					the_post();
					do_action('before_archive_post', $post, $post_index);
					echo Post_Card::render(['post' => $post]);
					do_action('after_archive_post', $post, $post_index);
					$post_index++;
				}
				?>
			<?php else : ?>
				<div class="no-posts">
					<p><?php esc_html_e('No posts found.', 'your-textdomain'); ?></p>
				</div>
			<?php endif; ?>
		</section>

		<?php 
		// Pagination
		\Mandy\Skeletor\News\Template::archive_pagination();
		?>
	</div>
</div>

<?php
do_action('after_archive_loop', $qo);

get_footer();
