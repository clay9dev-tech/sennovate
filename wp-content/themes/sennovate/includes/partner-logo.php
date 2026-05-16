<?php
/**
 * Register a custom block for displaying a Partner Logos
 *
 * @package Acqueon
 */
add_action('acf/init', 'partner_register_logo_slider_blocks');
function partner_register_logo_slider_blocks() {
	// check function exists.
	if( function_exists('acf_register_block_type') ) {
		acf_register_block_type(array(
			'name'              => 'partner-logos',
			'title'             => __('Client Logos'),
			'description'       => __('A custom brand logo for partner block.'),
			'render_callback'   => 'acf_block_render_partner_logos_callback',
			'category'          => 'formatting',
		));
	}
}

/**
 * Brand Logo Block Callback Function.
 *
 * @param   array $block The block settings and attributes.
 * @param   string $content The block inner HTML (empty).
 * @param   bool $is_preview True during AJAX preview.
 * @param   (int|string) $post_id The post ID this block is saved to.
 */
function acf_block_render_partner_logos_callback( $block, $content = '', $is_preview = false, $post_id = 0, $wp_block = false, $context = false ) {
	if( have_rows('partner_logos') ):
	?>
	<div class="client-logos-section">
		<div class="client-logos-grid">
			<?php while( have_rows('partner_logos') ): the_row();
				$logo = get_sub_field('logo_image');
				$link = get_sub_field('logo_link');
				$alt_text = !empty($logo['alt']) ? $logo['alt'] : 'Partner Logo';
			?>
			<div class="client-logo-card">
				<?php if($link): ?>
					<a href="<?php echo esc_url($link); ?>" target="_blank" rel="noopener">
						<img src="<?php echo esc_url($logo['url']); ?>" alt="<?php echo esc_attr($alt_text); ?>">
					</a>
				<?php else: ?>
					<img src="<?php echo esc_url($logo['url']); ?>" alt="<?php echo esc_attr($alt_text); ?>">
				<?php endif; ?>
			</div>
			<?php endwhile; ?>
		</div>
	</div>
	<?php
	endif;
}
