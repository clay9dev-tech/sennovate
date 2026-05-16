<?php
/**
 * Register a custom block for displaying a Career slider.
 *
 * @package Acqueon
 */
add_action('acf/init', 'my_register_career_slider_blocks');
function my_register_career_slider_blocks() {
	// check function exists.
	if( function_exists('acf_register_block_type') ) {
		// register a Logo Slider block.
		acf_register_block_type(array(
			'name'              => 'career-slider',
			'title'             => __('Career Slider'),
			'description'       => __('A custom career slider block.'),
			'render_callback'   => 'my_acf_block_render_career_slider_callback',
			'category'          => 'formatting',
		));
	}
}

/**
 * Brand Logo Slider Block Callback Function.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */
function my_acf_block_render_career_slider_callback( $block, $content = '', $is_preview = false, $post_id = 0, $wp_block = false, $context = false ) {
	?>
	<div id="career-slides" class="career-slides">
		<div class="career-slides-container">
			<div class="swiper career-grid-slider">
				<?php if( have_rows('career_slides') ): ?>
					<div class="swiper-wrapper">
						<?php while( have_rows('career_slides') ): the_row();
							?>
							<div class="swiper-slide">
								<div class="career-grid">
									<div class="career-big">
										<img src="<?php echo get_sub_field('career_image_1'); ?>" alt="Career Image 1">
									</div>
									<div class="career-small">
										<img src="<?php echo get_sub_field('career_image_2'); ?>" alt="Career Image 2">
										<img src="<?php echo get_sub_field('career_image_3'); ?>" alt="Life Image 3">
									</div>
								</div>
							</div>
						<?php endwhile; ?>
					</div>
				<?php endif; ?>
				<!-- Pagination -->
  			<div class="swiper-pagination"></div>
		</div>
	</div>
	<?php
}
