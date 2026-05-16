<?php
/* Template Name: Blog */

global $post;
get_header();

$qo = get_queried_object();

//do_action('before_archive_loop', $qo);
if ( have_posts() ) : while ( have_posts() ) : the_post();       
  the_content();
endwhile; endif;
 
$archive_post_classes = ['archive-posts category-lists'];
if (is_a($qo, 'WP_Post_Type')) {
	$archive_post_classes[] = sprintf('post-type-%s', $qo->name);
}
$archive_post_classes = apply_filters('archive_loop_classes', $archive_post_classes, $qo);
?>
<div class='post-main'>

	<?php do_action('render_filter'); ?>

	<div class="post-content">
		<section class="archive-posts">
			<?php
			$args = [
				'post_type' => 'post',
				'category__not_in' => 1
			];

			$wp_query = new WP_Query($args);

			if ($wp_query->have_posts()) {
				$post_index = 1;
				while ($wp_query->have_posts()) {
					global $post;
					$wp_query->the_post();
					do_action('before_archive_post', $post, $post_index);
					do_action('the_archive_post', $post);
					do_action('after_archive_post', $post, $post_index);
					$post_index++;
				}
				wp_reset_postdata();
			} else {
				echo '<p>No posts found.</p>';
			}
			?>
		</section>
		<?php do_action('render_pagination', $wp_query); ?>

	</div>
</div>
<?php
do_action('after_archive_loop', $qo);

get_footer();
