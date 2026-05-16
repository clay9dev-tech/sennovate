<?php
global $post;
$class = !has_post_thumbnail() ? ' has-no-thumbnail' : '';
$term = get_the_terms($post, 'category');
$post_id = get_the_ID();
$author_id = get_the_author_meta('ID');
?>

<section class="wp-block-group alignfull detail-banner">
	<div class="wp-block-group alignwide detail-banner-wrap">
		<div class="wp-block-columns are-vertically-aligned-center reverse-stacking-order is-content-justification-space-between detail-banner-columns ">
			<div class="wp-block-column detail-banner-left">
				<div class="blog-hero-heading">
					<h6 class="wp-block-heading">
						<a href="<?php echo get_term_link((int) $term[0]->term_id, 'category'); ?>" class="type-link"><?php echo strtoupper($term[0]->name); ?></a>
					</h6>
					<h1 class="wp-block-heading blog-title"><?php the_title(); ?></h1>
					<div class="post-date"><?php echo get_the_date('j M, Y'); ?></div>
				</div>
			</div>
			<div class="wp-block-column detail-banner-right">
				<?php echo get_the_post_thumbnail( $post_id, 'large' ); ?>
			</div>
		</div>
	</div>
</section>
