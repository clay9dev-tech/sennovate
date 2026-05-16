<?php
/**
 * Register a custom block for Sennovate Testimonial Slider.
 */

add_action( 'acf/init', 'register_sennovate_testimonial_slider_block', 20 );
function register_sennovate_testimonial_slider_block() {
	if ( function_exists( 'acf_register_block_type' ) ) {
		acf_register_block_type( array(
			'name'            => 'sennovate-testimonial-slider',
			'title'           => 'Sennovate Testimonial Slider',
			'description'     => 'A testimonial slider that bleeds to the right edge of the screen.',
			'render_callback' => 'sennovate_render_testimonial_slider_callback',
			'category'        => 'formatting',
			'icon'            => 'format-quote',
			'keywords'        => array( 'testimonial', 'slider', 'sennovate', 'quotes' ),
			'supports'        => array(
				'align'  => true,
				'anchor' => true,
			),
		) );

		// Register Fields
		acf_add_local_field_group(array(
			'key' => 'group_sennovate_testimonial_slider',
			'title' => 'Sennovate Testimonial Slider Fields',
			'fields' => array(
				array(
					'key' => 'field_sts_section_title',
					'label' => 'Section Title',
					'name' => 'sts_section_title',
					'type' => 'text',
					'default_value' => 'The Work, In Their Words.',
				),
				array(
					'key' => 'field_sts_section_subtitle',
					'label' => 'Section Subtitle',
					'name' => 'sts_section_subtitle',
					'type' => 'text',
					'default_value' => 'Customers, in their own voice.',
				),
				array(
					'key' => 'field_sts_slides',
					'label' => 'Slides',
					'name' => 'sts_slides',
					'type' => 'repeater',
					'layout' => 'block',
					'sub_fields' => array(
						array(
							'key' => 'field_sts_slide_image',
							'label' => 'Person Image',
							'name' => 'image',
							'type' => 'image',
							'return_format' => 'array',
						),
						array(
							'key' => 'field_sts_slide_category',
							'label' => 'Category Label',
							'name' => 'category',
							'type' => 'text',
							'default_value' => 'IT Services',
						),
						array(
							'key' => 'field_sts_slide_quote',
							'label' => 'Quote',
							'name' => 'quote',
							'type' => 'textarea',
						),
						array(
							'key' => 'field_sts_slide_attribution',
							'label' => 'Attribution (Name, Title, Company)',
							'name' => 'attribution',
							'type' => 'text',
						),
					),
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'block',
						'operator' => '==',
						'value' => 'acf/sennovate-testimonial-slider',
					),
				),
			),
		));
	}
}

/**
 * Render Callback.
 */
function sennovate_render_testimonial_slider_callback( $block, $content = '', $is_preview = false, $post_id = 0 ) {
	$title    = get_field( 'sts_section_title' ) ?: 'The Work, In Their Words.';
	$subtitle = get_field( 'sts_section_subtitle' ) ?: 'Customers, in their own voice.';
	$slides   = get_field( 'sts_slides' );

	$anchor     = ! empty( $block['anchor'] ) ? 'id="' . esc_attr( $block['anchor'] ) . '" ' : '';
	$class_name = 'sts-slider-section' . ( ! empty( $block['className'] ) ? ' ' . $block['className'] : '' );
	?>

	<div <?php echo $anchor; ?> class="<?php echo esc_attr( $class_name ); ?>">
		<div class="sts-slider-container">
			<div class="sts-slider-header">
				<h2 class="sts-slider-title"><?php echo esc_html( $title ); ?></h2>
				<p class="sts-slider-subtitle"><?php echo esc_html( $subtitle ); ?></p>
			</div>
		</div>

		<div class="sts-swiper-outer">
			<div class="swiper sennovate-testimonial-swiper">
				<div class="swiper-wrapper">
					<?php if ( $slides ) : foreach ( $slides as $slide ) : 
						$image = $slide['image'];
						$category = $slide['category'];
						$quote = $slide['quote'];
						$attribution = $slide['attribution'];
					?>
						<div class="swiper-slide">
							<div class="sts-testimonial-card">
								<div class="sts-testimonial-image">
									<?php if ( $image ) : ?>
										<img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>">
									<?php endif; ?>
								</div>
								<div class="sts-testimonial-content">
									<div class="sts-testimonial-category">
										<span class="dot"></span> <?php echo esc_html( $category ); ?>
									</div>
									<p class="sts-testimonial-quote">"<?php echo esc_html( $quote ); ?>"</p>
									<div class="sts-testimonial-footer">
										<span class="attribution"><?php echo esc_html( $attribution ); ?></span>
									</div>
								</div>
							</div>
						</div>
					<?php endforeach; endif; ?>
				</div>
			</div>

			<div class="swiper-pagination sts-pagination"></div>
		</div>

		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
		<style>
			:root {
				--hss-off: max(20px, calc((100vw - 1180px) / 2));
			}
			.sts-slider-section {
				background: #fff;
				font-family: 'Inter', sans-serif;
				overflow: visible;
				width: 100%;
				padding: 80px 30px;
			}
			.sts-slider-container {
				width: 100% !important;
				max-width: 1180px !important;
				margin: 0 auto !important;
				position: relative;
			}
			.sts-slider-header {
				text-align: center;
				margin: 0 auto 50px;
				width: 100%;
			}
			.sts-slider-title {
				font-family: var(--sennovate-medium-font) !important;
				color: #051630;
				margin: 0 0 16px;
				font-weight: 500 !important;
				font-size: 42px;
			}
			.sts-slider-subtitle {
				font-size: 18px !important;
				color: var(--sennovate-dark-blue);
				line-height: 1.5 !important;
				margin: 0;
			}

			.sts-swiper-outer {
				    position: relative;
    width: 100vw;
    left: 50%;
    margin-left: -50vw;
			}

			.sennovate-testimonial-swiper {
				padding: 10px 0 60px var(--hss-off) !important;
				/* overflow: visible !important; */
				box-sizing: border-box;
			}

			.swiper-slide {
				width: auto !important;
				opacity: 0.4;
				transition: opacity 0.3s ease;
			}
			.swiper-slide-active, .swiper-slide-next {
				opacity: 1;
			}

			.sts-testimonial-card {
				display: flex;
				background: transparent;
				width: 1040px;
				max-width: 85vw;
				gap: 24px;
				align-items: stretch;
			}

			.sts-testimonial-image {
				width: 360px;
				flex-shrink: 0;
				border-radius: 20px;
				overflow: hidden;
				height: 480px;
			}
			.sts-testimonial-image img {
				width: 100%;
				height: 100%;
				object-fit: cover;
			}

			.sts-testimonial-content {
    padding: 40px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
	    justify-content: space-between;
    position: relative;
    border-radius: 12px;
    background: var(--100, #F3F8FD);
}

			.sts-testimonial-category {
    display: flex;
    align-items: center;
    color: var(--sennovate-dark-blue);
    font-size: 18px;
    line-height: 1.4;
    font-weight: 400;
}
			.sts-testimonial-category .dot {
				width: 8px;
				height: 8px;
				background: #006FE3;
				border-radius: 50%;
				margin-right: 10px;
			}

			.sts-testimonial-quote {
    font-size: 22px !important;
    color: var(--sennovate-dark-blue);
    line-height: 1.3 !important;
    margin-bottom: 40px;
    font-weight: 500;
    font-family: var(--sennovate-medium-font) !important;
	text-align: left;
}

			.sts-testimonial-footer {
    padding-top: 30px;
    border-top: 1px solid var(--Sennovate-stroke, #D7E2ED);
    font-size: 16px;
	text-align: left;
}
			.sts-testimonial-footer .attribution {
    color: var(--sennovate-dark-blue);
    font-size: 16px;
    line-height: 1.3;
    font-weight: 400;
}

			.sts-pagination {
    position: relative !important;
    bottom: 0 !important;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 5px;
    z-index: 10;
}
			.sts-pagination .swiper-pagination-bullet {
				width: 12px;
    height: 12px;
    opacity: 1;
    margin: 0 !important;
    transition: all 0.3s ease;
    border-radius: 10px;
    background: var(--300, #CCE2F9);
			}
			.sts-pagination .swiper-pagination-bullet-active {
				width: 29px;
    border-radius: 10px;
    background: var(--600, #006FE3);
    height: 12px;
			}

			@media (max-width: 1024px) {
.sts-testimonial-card{max-width: calc(95vw - 40px);}
			}
			@media(max-width: 991px){
				.sts-testimonial-card{
				    flex-direction: column;
				}
				.sts-testimonial-image {
				width: 100%;
			}
			}
			@media(max-width:767px){
			    .sts-testimonial-image {
        width: 100%;
        height: auto;
    }
	.sts-testimonial-image img {
    width: 100%;
    height: auto;
    object-fit: cover;
}	
.sts-testimonial-content {
    padding: 20px;
}
			}
		</style>

		<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
		<script>
			document.addEventListener('DOMContentLoaded', function() {
				const hssOff = parseInt(getComputedStyle(document.documentElement).getPropertyValue('--hss-off')) || 20;

const swiper = new Swiper('.sennovate-testimonial-swiper', {
    slidesPerView: 'auto',
    spaceBetween: 20,
    grabCursor: true,
    centeredSlides: false,
    slidesOffsetBefore: hssOff,
    pagination: {
        el: '.sts-pagination',
        clickable: true,
    },
    breakpoints: {
        320: { spaceBetween: 16 },
        1024: { spaceBetween: 20 }
    }
});
			});
		</script>
	</div>
	<?php
}
