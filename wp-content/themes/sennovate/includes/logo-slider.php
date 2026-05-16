<?php
/**
 * Register a custom block for displaying a logo slider.
 *
 * @package Acqueon
 */
add_action('acf/init', 'my_register_logo_slider_blocks');
function my_register_logo_slider_blocks() {
	// check function exists.
	if( function_exists('acf_register_block_type') ) {
		// register a Logo Slider block.
		acf_register_block_type(array(
			'name'              => 'brand-logo-slider',
			'title'             => __('Brand Logo Slider'),
			'description'       => __('A custom brand logo slider block.'),
			'render_callback'   => 'my_acf_block_render_logo_slider_callback',
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
function my_acf_block_render_logo_slider_callback( $block, $content = '', $is_preview = false, $post_id = 0, $wp_block = false, $context = false ) {

	// Load values and handle defaults.
	$heading = get_field('brand_slider_heading') ?: 'Your heading here...';

	?>
	<div id="brand-slide-homepage" class="logos-homepage">
	<div class="logos-homepage__container">
		<?php if ($heading) : ?>
			<div class="logos-homepage__title">
				<p><?php echo esc_html($heading); ?></p>
			</div>
		<?php endif; ?>
		<div class="logos-homepage__slides">
			<?php if( have_rows('logo_slides') ): ?>
				<div class="logos-homepage__track">
				<?php while( have_rows('logo_slides') ): the_row();
					$image = get_sub_field('logo_slide');
					?>
					<div class="logos-homepage__slide">
						<img decoding="async" src="<?php echo $image; ?>" alt="Acqueon Brand Logo" loading="lazy" fetchpriority="low">
					</div>
				<?php endwhile; ?>
				<?php reset_rows(); while( have_rows('logo_slides') ): the_row();
					$image = get_sub_field('logo_slide'); ?>
					<div class="logos-homepage__slide">
						<img decoding="async" src="<?php echo $image; ?>" alt="Acqueon Brand Logo" loading="lazy" fetchpriority="low">
					</div>
				<?php endwhile; ?>
				</div>
			<?php endif; ?>
		</div>
	</div>
	</div>
	<style type="text/css">
		@-webkit-keyframes slide {
			from {
				-webkit-transform: translateX(0);
				transform: translateX(0)
			}

			to {
				-webkit-transform: translateX(-50%);
				transform: translateX(-50%)
			}
		}

		@keyframes slide {
			from {
				-webkit-transform: translateX(0);
				transform: translateX(0)
			}

			to {
				-webkit-transform: translateX(-50%);
				transform: translateX(-50%)
			}
		}
		.logos-homepage {
			display: flex;
			justify-content: center;
			max-width: 100%;
			padding: 40px 0;
		}

		.logos-homepage__container {
			display: flex;
			flex-direction: row;
			justify-content: center;
			align-items: center;
			gap: 0;
			align-self: stretch;
			width: 100%;
			max-width: var(--wp--style--global--wide-size);
			margin: 0 auto;
			overflow: hidden;
		}

		.logos-homepage__title {
			flex-shrink: 0;
			border-right: 1px solid #D9D9D9;
			padding-right: 30px;
			margin-right: 30px;
			max-width: 115px;

			
			@media(max-width: 768px) {
				padding-right: 0;
        margin-right: 0;
        max-width: 100%;
        margin-bottom: 20px;
        text-align: center;
        border: 0;
			}

			p {
				font-size: 1rem;
				line-height: 1.4;
				margin: 0;
				color: var(--sennovate-dark-blue);
				font-family: var(--sennovate-medium-font);
			}
		}

		.logos-homepage__slides {
			flex-grow: 1;
			overflow: hidden;
			display: flex;
			align-items: center;
			mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
			-webkit-mask-image: linear-gradient(to right, transparent, black 10%, black 90%, transparent);
		}

		.logos-homepage__track {
			display: flex;
			align-items: center;
			white-space: nowrap;
			-webkit-animation: 30s slide infinite linear;
			animation: 30s slide infinite linear;
		}
		
		.logos-homepage__track:hover {
			-webkit-animation-play-state: paused;
			animation-play-state: paused;
		}

		.logos-homepage__slide {
			display: flex;
			align-items: center;
			padding: 0 30px;
			flex-shrink: 0;
			
			@media(max-width: 768px) {
				padding: 0 20px;
			}
		}

		.logos-homepage__slide img {
			height: auto;
			max-width: 180px;
			object-fit: contain;
			display: block;
		}

		@media(max-width: 767px) {
			.logos-homepage__container{flex-direction: column;}
		}

	</style>
	<?php
}
