<?php
global $post;
$term = get_the_terms($post, 'category');
if ($term[0]->slug !== 'events-diary') :
// Related Blogs
$args = array(
	'post_type'      => 'post',
	'posts_per_page' => 4,
	'post__not_in'   => array( get_the_ID() ),
	'orderby'        => 'rand',
);

$latest_query = new WP_Query($args);
?>
<?php if ($latest_query->have_posts()) : ?>
<div class="blog-related-post-widget">
	<div class="blog-related-buttons">
		<h2 class="wp-block-heading">Related Posts</h2>
		<hr class="wp-block-separator has-text-color has-light-grey-color has-alpha-channel-opacity has-light-grey-background-color has-background">
		<div class="wp-block-buttons is-content-justification-right is-layout-flex wp-container-core-buttons-is-layout-d445cf74 wp-block-buttons-is-layout-flex">
			<div class="wp-block-button is-style-text">
				<a class="wp-block-button__link has-neutral-700-color has-text-color has-link-color has-medium-font-size has-custom-font-size wp-element-button" href="<?php echo site_url(); ?>/blog">
					View All Post
				</a>
			</div>
		</div>
	</div>

	<div class="sidebar-posts-wrapper blog-cards">
	<?php while ( $latest_query->have_posts() ) : $latest_query->the_post(); ?>
        <div class="blog-card all">
			<div class="image">
				<?php
				echo get_the_post_thumbnail( $latest_query->ID, 'large', [
					'alt' => get_the_title( $latest_query->ID )
				] );
				?>
			</div>
			<div class="content">
				<div class="tags">
					<div class="tag">
					<?php
						$categories = get_the_terms( $latest_query->ID, 'category' );
						foreach( $categories as $category ) {
							echo $category->name;
						}
						$author_id = get_the_author_meta( 'ID' );
					?>
					</div>
				</div>
				<h2 class="title">
					<?php echo esc_html( get_the_title( $latest_query->ID ) ); ?>
				</h2>
				<p class="description">
					<?php 
						$excerpt = get_the_excerpt($latest_query->ID); 
						$trimmed = wp_trim_words( $excerpt, 12, '...' ); // 15 words, ends with ...
						echo esc_html( $trimmed );
					?>
				</p>
				<div class="author">
					<?php echo get_avatar( $author_id, 24, '', get_the_author(), ['class' => [ 'author-image', 'rounded-circle' ]] ); ?>
					<span class="author-name">
						<?php echo esc_html( get_the_author_meta( 'display_name', $latest_query->post_author ) ); ?>
					</span>
				</div>
			</div>
        	<a href="<?php echo esc_url( get_permalink( $latest_query->ID ) ); ?>" class="link"   aria-label="<?php echo esc_attr( get_the_title( $latest_query->ID ) ); ?>"></a>
        </div>
        <?php endwhile; wp_reset_postdata(); ?>
	</div>
</div>
<?php else : ?>
	<p>No Related Blogs Found.</p>
<?php endif; ?>

<?php 
	wp_reset_postdata();
	endif;
?>
