<?php
/**
 * Register a custom block for Sennovate Page Hero.
 */

add_action( 'acf/init', 'register_sennovate_page_hero_block', 20 );
function register_sennovate_page_hero_block() {
	if ( function_exists( 'acf_register_block_type' ) ) {
		acf_register_block_type( array(
			'name'            => 'sennovate-page-hero',
			'title'           => 'Sennovate Page Hero',
			'description'     => 'A versatile hero section with solid blue background and rounded bottom corners.',
			'render_callback' => 'sennovate_render_page_hero_callback',
			'category'        => 'formatting',
			'icon'            => 'welcome-view-site',
			'keywords'        => array( 'hero', 'banner', 'page', 'header' ),
			'supports'        => array(
				'align'  => true,
				'anchor' => true,
			),
		) );

		// Register Fields
		acf_add_local_field_group(array(
			'key' => 'group_sennovate_page_hero',
			'title' => 'Sennovate Page Hero Fields',
			'fields' => array(
				array(
					'key' => 'field_sph_layout',
					'label' => 'Layout Format',
					'name' => 'hero_layout',
					'type' => 'select',
					'choices' => array(
						'centered' => 'Centered Content',
						'split'    => 'Split (Content Left, Image Right)',
					),
					'default_value' => 'centered',
					'wrapper' => array('width' => '50'),
				),
				array(
					'key' => 'field_sph_style',
					'label' => 'Style Variant',
					'name' => 'hero_style',
					'type' => 'select',
					'choices' => array(
						'vibrant-blue' => 'Vibrant Blue (Solid)',
						'soft-gradient' => 'Soft Gradient (Home Style)',
					),
					'default_value' => 'vibrant-blue',
					'wrapper' => array('width' => '50'),
				),
				array(
					'key' => 'field_sph_label',
					'label' => 'Label (Small text above title)',
					'name' => 'hero_label',
					'type' => 'text',
					'placeholder' => 'e.g. Managed Security Services',
				),
				array(
					'key' => 'field_sph_title',
					'label' => 'Title',
					'name' => 'hero_title',
					'type' => 'textarea',
					'rows' => 2,
					'required' => 1,
				),
				array(
					'key' => 'field_sph_description',
					'label' => 'Description',
					'name' => 'hero_description',
					'type' => 'textarea',
					'rows' => 3,
				),
				array(
					'key' => 'field_sph_primary_button',
					'label' => 'Primary Button',
					'name' => 'hero_primary_button',
					'type' => 'link',
				),
				array(
					'key' => 'field_sph_image',
					'label' => 'Hero Image',
					'name' => 'hero_image',
					'type' => 'image',
					'return_format' => 'array',
					'conditional_logic' => array(
						array(
							array(
								'field' => 'field_sph_layout',
								'operator' => '==',
								'value' => 'split',
							),
						),
					),
				),
				array(
					'key' => 'field_sph_floating_cards',
					'label' => 'Floating Cards (Stats/Badges)',
					'name' => 'hero_floating_cards',
					'type' => 'repeater',
					'layout' => 'table',
					'button_label' => 'Add Card',
					'conditional_logic' => array(
						array(
							array(
								'field' => 'field_sph_layout',
								'operator' => '==',
								'value' => 'split',
							),
						),
					),
					'sub_fields' => array(
						array(
							'key' => 'field_sph_card_label',
							'label' => 'Label',
							'name' => 'label',
							'type' => 'text',
						),
						array(
							'key' => 'field_sph_card_value',
							'label' => 'Value',
							'name' => 'value',
							'type' => 'text',
						),
						array(
							'key' => 'field_sph_card_icon',
							'label' => 'Icon',
							'name' => 'icon',
							'type' => 'image',
							'return_format' => 'url',
						),
						array(
							'key' => 'field_sph_card_pos',
							'label' => 'Position',
							'name' => 'position',
							'type' => 'select',
							'choices' => array(
								'top-right' => 'Top Right',
								'bottom-left' => 'Bottom Left',
								'bottom-right' => 'Bottom Right',
							),
						),
					),
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'block',
						'operator' => '==',
						'value' => 'acf/sennovate-page-hero',
					),
				),
			),
		));
	}
}

/**
 * Render Callback.
 */
function sennovate_render_page_hero_callback( $block, $content = '', $is_preview = false, $post_id = 0 ) {
	$layout     = get_field( 'hero_layout' ) ?: 'centered';
	$style      = get_field( 'hero_style' ) ?: 'vibrant-blue';
	$label      = get_field( 'hero_label' );
	$title      = get_field( 'hero_title' );
	$desc       = get_field( 'hero_description' );
	$primary    = get_field( 'hero_primary_button' );
	$image      = get_field( 'hero_image' );
	$cards      = get_field( 'hero_floating_cards' );

	$anchor     = ! empty( $block['anchor'] ) ? 'id="' . esc_attr( $block['anchor'] ) . '" ' : '';
	$class_name = 'sennovate-page-hero-wrapper ' . $layout . ' ' . $style . ( ! empty( $block['className'] ) ? ' ' . $block['className'] : '' );
	?>

	<div <?php echo $anchor; ?> class="<?php echo esc_attr( $class_name ); ?> alignfull">
		<div class="hero-bg-container">
			<div class="hero-content-container">
				
				<?php if ( $layout === 'centered' ) : ?>
					<div class="hero-centered-content">
						<?php if ( $label ) : ?>
							<div class="hero-label"><?php echo esc_html( $label ); ?></div>
						<?php endif; ?>
						
						<h1 class="hero-title"><?php echo wp_kses_post( $title ); ?></h1>
						
						<?php if ( $desc ) : ?>
							<p class="hero-desc"><?php echo esc_html( $desc ); ?></p>
						<?php endif; ?>
						
						<?php if ( $primary ) : ?>
							<div class="hero-cta">
								<a href="<?php echo esc_url( $primary['url'] ); ?>" class="btn-primary-white" target="<?php echo esc_attr( $primary['target'] ); ?>">
									<?php echo esc_html( $primary['title'] ); ?>
								</a>
							</div>
						<?php endif; ?>
					</div>
				<?php else : ?>
					<div class="hero-split-content">
						<div class="hero-text-col">
							<?php if ( $label ) : ?>
								<div class="hero-label"><?php echo esc_html( $label ); ?></div>
							<?php endif; ?>
							
							<h1 class="hero-title"><?php echo wp_kses_post( $title ); ?></h1>
							
							<?php if ( $desc ) : ?>
								<p class="hero-desc"><?php echo esc_html( $desc ); ?></p>
							<?php endif; ?>
							
							<?php if ( $primary ) : ?>
								<div class="hero-cta">
									<a href="<?php echo esc_url( $primary['url'] ); ?>" class="btn-primary-white" target="<?php echo esc_attr( $primary['target'] ); ?>">
										<?php echo esc_html( $primary['title'] ); ?>
									</a>
								</div>
							<?php endif; ?>
						</div>
						
						<div class="hero-image-col">
							<div class="hero-media-wrapper">
								<?php if ( $image ) : ?>
									<img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" class="main-hero-img">
								<?php endif; ?>
								
								<?php if ( $cards ) : ?>
									<?php foreach ( $cards as $card ) : ?>
										<div class="floating-card <?php echo esc_attr( $card['position'] ); ?>">
											<?php if ( $card['icon'] ) : ?>
												<img src="<?php echo esc_url( $card['icon'] ); ?>" alt="icon" class="card-icon">
											<?php endif; ?>
											<div class="card-text">
												<?php if ( $card['value'] ) : ?><span class="card-value"><?php echo esc_html( $card['value'] ); ?></span><?php endif; ?>
												<?php if ( $card['label'] ) : ?><span class="card-label"><?php echo esc_html( $card['label'] ); ?></span><?php endif; ?>
											</div>
										</div>
									<?php endforeach; ?>
								<?php endif; ?>
							</div>
						</div>
					</div>
				<?php endif; ?>

			</div>
		</div>

		<style>
			/* Page Hero Component Styles */
.sennovate-page-hero-wrapper {
    margin: 0 !important;
    width: 100vw !important;
    position: relative;
    left: 50% !important;
    margin-left: -50vw !important;
    overflow: hidden;
    padding-top: 0 !important;
}

			.hero-bg-container {
            background: #006FE3;
    color: #ffffff;
    position: relative;
    overflow: hidden;
    border-radius: 18px;
    padding: 120px 30px 60px !important;
    border: 10px solid #ffffff;
    margin: 0 8px;
}

			.sennovate-page-hero-wrapper.soft-gradient .hero-bg-container {
				background: linear-gradient(104deg, #E7EEFA -21.37%, #FBFDFF 38.7%, #DFF2FF 101.47%);
				color: #051630;
			}

			.hero-content-container {
				max-width: 1200px;
				margin: 0 auto;
			}

			/* Centered Layout */
			.hero-centered-content {
				text-align: center;
				max-width: 800px;
				margin: 0 auto;
				    justify-content: center;
			}

			/* Split Layout */
			.hero-split-content {
				display: flex;
				align-items: center;
				gap: 60px;
			}
			.hero-text-col { flex: 1; }
			.hero-image-col { flex: 1; }

			/* Typography */
.hero-label {
    text-transform: uppercase;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    justify-content: inherit;
    gap: 10px;
    color: var(--Sennovate-white, #FFF);
    font-family: var(--sennovate-medium-font);
    font-size: 14px;
    font-style: normal;
    font-weight: 500;
    line-height: 140%;
}
			.hero-label::before {
				content: '';
				width: 8px;
				height: 8px;
				background: #fff;
				display: inline-block;
			}
			.sennovate-page-hero-wrapper.soft-gradient .hero-label::before { background: #006FE3; }

.hero-title {
    font-family: 'Satoshi-Black', sans-serif !important;
    font-size: clamp(32px, 5vw, 58px) !important;
    line-height: 1.2 !important;
    color: inherit !important;
    letter-spacing: -1.16px !important;
    font-weight: 900 !important;
    margin: 0 0 16px !important;
}


			.hero-desc {
				font-size: clamp(16px, 2vw, 20px) !important;
				line-height: 1.5 !important;
				margin-bottom: 40px !important;
				max-width: 685px;
				margin-left: auto;
				margin-right: auto;
			}

			/* Button */
			.btn-primary-white {
    background-color: #ffffff !important;
    color: var(--sennovate-dark-blue) !important;
    padding: 10px 24px !important;
    border-radius: 6px !important;
    font-weight: 700 !important;
    font-size: 16px !important;
    text-decoration: none !important;
    display: inline-block;
    transition: all 0.3s ease;
	line-height: 120% !important;
	font-family: var(--font-satoshi-bold);
}
			.btn-primary-white:hover {
				transform: translateY(-2px);
				box-shadow: 0 10px 20px rgba(0,0,0,0.1);
			}
			.sennovate-page-hero-wrapper.soft-gradient .btn-primary-white {
				background: #006FE3;
				color: #fff;
			}

			/* Image & Floating Cards */
			.hero-media-wrapper {
				position: relative;
				width: 100%;
			}
			.main-hero-img {
				width: 100%;
				height: auto;
			}

			.floating-card {
				position: absolute;
				background: #fff;
				color: #051630;
				padding: 12px 16px;
				border-radius: 12px;
				box-shadow: 0 15px 30px rgba(0,0,0,0.1);
				display: flex;
				align-items: center;
				gap: 12px;
				z-index: 2;
			}
			.floating-card.top-right { top: 20px; right: -20px; }
			.floating-card.bottom-left { bottom: 40px; left: -30px; }
			.floating-card.bottom-right { bottom: 20px; right: 10px; }

			.card-icon { width: 32px; height: 32px; }
			.card-value { font-weight: 700; font-size: 18px; display: block; }
			.card-label { font-size: 12px; opacity: 0.7; }

			/* Responsive */
			@media(max-width: 1024px){
				.hero-bg-container{
					padding: 100px 30px 40px !important;
        margin: 0;
				}
			}
			@media (max-width: 991px) {
				.hero-split-content {
					flex-direction: column;
					text-align: center;
				}
				.hero-desc { margin-left: auto; }
				.hero-bg-container { padding: 40px 30px 40px !important;}
				.floating-card { position: relative; top: auto !important; left: auto !important; right: auto !important; margin: 10px auto; width: fit-content; }
			}

			@media (max-width: 767px) {
				.hero-bg-container { padding: 30px !important; }
			}
		</style>
	</div>
	<?php
}
