<?php
/*
* Blog Hero Banner Section
*/

$term_id = get_queried_object_id(); // Or use $category->term_id
$thumb_url = get_field('category_thumbnail', 'category_' . $term_id);
?>

<section class="wp-block-group alignfull is-layout-constrained wp-block-group-is-layout-constrained archive-banner">
	<div class="wp-block-group alignwide is-layout-constrained wp-block-group-is-layout-constrained archive-banner-wrap" style="background-image: url('<?php echo esc_url($thumb_url); ?>');">
		<div class="wp-block-columns alignwide are-vertically-aligned-center is-content-justification-center is-layout-flex wp-container-core-columns-is-layout-1 wp-block-columns-is-layout-flex">
			<div class="wp-block-column is-vertically-aligned-center is-layout-flow wp-block-column-is-layout-flow">
				<?php if (!is_search()) : ?>
					<h1 class="wp-block-heading has-text-align-center">
						<?php
							$category = get_category($term_id);
							if (!is_wp_error($category)) {
								echo $category->name;
							}
						?>
					</h1>
				<?php else : ?>
					<h1 class="wp-block-heading has-text-align-center"><?php printf( esc_html__( 'Search Results for: %s', 'your-text-domain' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
				<?php endif; ?>
			</div>
		</div>
	</div>
</section>
