<?php
/**
 * Register a custom block for Sennovate Reasons Grid.
 */

add_action( 'acf/init', 'register_sennovate_reasons_grid_block', 20 );
function register_sennovate_reasons_grid_block() {
	if ( function_exists( 'acf_register_block_type' ) ) {
		acf_register_block_type( array(
			'name'            => 'sennovate-reasons-grid',
			'title'           => 'Sennovate Reasons Grid',
			'description'     => 'A section with a 3x2 checkerboard grid of reasons why customers choose Sennovate.',
			'render_callback' => 'sennovate_render_reasons_grid_callback',
			'category'        => 'formatting',
			'icon'            => 'grid-view',
			'keywords'        => array( 'reasons', 'grid', 'checkerboard', 'features', 'why' ),
			'supports'        => array(
				'align'  => true,
				'anchor' => true,
			),
		) );

		// Register Fields
		acf_add_local_field_group(array(
			'key' => 'group_sennovate_reasons_grid',
			'title' => 'Sennovate Reasons Grid Fields',
			'fields' => array(
				array(
					'key' => 'field_rg_title',
					'label' => 'Title',
					'name' => 'rg_title',
					'type' => 'textarea',
					'rows' => 2,
					'default_value' => 'Why Security Teams<br><strong>Choose Sennovate</strong>',
					'instructions' => 'Use &lt;strong&gt; tags to make parts of the text bolder, and &lt;br&gt; for line breaks.',
				),
				array(
					'key' => 'field_rg_subtitle',
					'label' => 'Subtitle',
					'name' => 'rg_subtitle',
					'type' => 'text',
					'default_value' => 'Six reasons decision makers choose and trust Sennovate.',
				),
				array(
					'key' => 'field_rg_reasons',
					'label' => 'Reasons Cards',
					'name' => 'rg_reasons',
					'type' => 'repeater',
					'layout' => 'block',
					'button_label' => 'Add Reason',
					'sub_fields' => array(
						array(
							'key' => 'field_rg_card_icon',
							'label' => 'Icon (PNG or SVG)',
							'name' => 'icon',
							'type' => 'image',
							'return_format' => 'url',
						),
						array(
							'key' => 'field_rg_card_title',
							'label' => 'Card Title',
							'name' => 'title',
							'type' => 'text',
						),
						array(
							'key' => 'field_rg_card_desc',
							'label' => 'Card Description',
							'name' => 'description',
							'type' => 'textarea',
							'rows' => 3,
						),
					),
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'block',
						'operator' => '==',
						'value' => 'acf/sennovate-reasons-grid',
					),
				),
			),
		));
	}
}

/**
 * Render Callback.
 */
function sennovate_render_reasons_grid_callback( $block, $content = '', $is_preview = false, $post_id = 0 ) {
	$title    = get_field( 'rg_title' );
	$subtitle = get_field( 'rg_subtitle' );
	$reasons  = get_field( 'rg_reasons' );

	$anchor     = ! empty( $block['anchor'] ) ? 'id="' . esc_attr( $block['anchor'] ) . '" ' : '';
	$class_name = 'sennovate-reasons-grid-section ' . ( ! empty( $block['className'] ) ? ' ' . $block['className'] : '' );
	?>

	<section <?php echo $anchor; ?> class="<?php echo esc_attr( $class_name ); ?> alignfull">
		<div class="rg-container">
			<?php if ( $title || $subtitle ) : ?>
				<div class="rg-header">
					<?php if ( $title ) : ?>
						<h2 class="rg-title"><?php echo wp_kses_post( $title ); ?></h2>
					<?php endif; ?>
					<?php if ( $subtitle ) : ?>
						<p class="rg-subtitle"><?php echo esc_html( $subtitle ); ?></p>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ( $reasons ) : ?>
				<div class="rg-grid-container">
					<?php foreach ( $reasons as $reason ) : ?>
						<div class="rg-card">
							<?php if ( $reason['icon'] ) : ?>
								<div class="rg-icon-wrapper">
									<img src="<?php echo esc_url( $reason['icon'] ); ?>" alt="<?php echo esc_attr( $reason['title'] ); ?>" class="rg-icon">
								</div>
							<?php endif; ?>
							
							<?php if ( $reason['title'] ) : ?>
								<h3 class="rg-card-title"><?php echo esc_html( $reason['title'] ); ?></h3>
							<?php endif; ?>
							
							<?php if ( $reason['description'] ) : ?>
								<p class="rg-card-desc"><?php echo wp_kses_post( $reason['description'] ); ?></p>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>

		<style>
			/* Sennovate Reasons Grid Section Styles */
			.sennovate-reasons-grid-section {
				padding: 100px 30px;
				background-color: var(--Sennovate-white, #FFF);
			}

			.rg-container {
				max-width: 1200px;
				margin: 0 auto;
			}

			.rg-header {
				text-align: center;
				margin-bottom: 60px;
			}

			.rg-title {
				font-family: var(--sennovate-medium-font) !important;
				font-size: clamp(32px, 4vw, 48px) !important;
				margin: 0 0 16px;
				color: var(--Sennovate-Dark-blue, #051630);
				font-weight: 400 !important;
				line-height: 120% !important;
			}

			.rg-title strong {
				font-weight: 700 !important;
				font-family: 'Satoshi-Bold', sans-serif !important;
			}

			.rg-subtitle {
				margin: 0;
				color: var(--Sennovate-Dark-blue, #051630);
				font-size: var(--font-size-18, 18px) !important;
				font-weight: 400;
				line-height: 140% !important;
				opacity: 0.8;
			}

			/* Grid Layout */
			.rg-grid-container {
				display: grid;
				grid-template-columns: repeat(3, 1fr);
				gap: 0;
				border-radius: 16px;
				overflow: hidden;
			}

			.rg-card {
				padding: 60px 50px;
				display: flex;
				flex-direction: column;
				justify-content: flex-start;
				align-items: flex-start;
			}

			.rg-icon-wrapper {
				width: 56px;
				height: 56px;
				margin-bottom: 30px;
				display: flex;
				align-items: center;
				justify-content: flex-start;
			}

			.rg-icon {
				max-width: 100%;
				max-height: 100%;
				object-fit: contain;
			}

			.rg-card-title {
				font-family: 'Satoshi-Bold', sans-serif !important;
				font-size: 22px !important;
				font-weight: 700 !important;
				margin: 0 0 16px;
			}

			.rg-card-desc {
				margin: 0;
				font-weight: 400;
				line-height: 150% !important;
				font-size: 16px;
			}

			/* Checkerboard Coloring */
			/* Odd Items: Light Gray */
			.rg-card:nth-child(odd) {
				background-color: #F4F7FB;
			}
			.rg-card:nth-child(odd) .rg-card-title,
			.rg-card:nth-child(odd) .rg-card-desc {
				color: var(--Sennovate-Dark-blue, #051630);
			}

			/* Even Items: Solid Blue */
			.rg-card:nth-child(even) {
				background-color: var(--Sennovate-primary-blue, #006FE3);
			}
			.rg-card:nth-child(even) .rg-card-title,
			.rg-card:nth-child(even) .rg-card-desc {
				color: #ffffff;
			}
			.rg-card:nth-child(even) .rg-card-desc {
				opacity: 0.9;
			}

			/* Responsive Design */
			@media (max-width: 991px) {
				.rg-grid-container {
					grid-template-columns: repeat(2, 1fr);
				}
				.rg-card {
					padding: 40px 30px;
				}
			}

			@media (max-width: 767px) {
				.sennovate-reasons-grid-section {
					padding: 60px 30px;
				}
				.rg-grid-container {
					grid-template-columns: 1fr;
				}
				.rg-card {
					padding: 40px 20px;
				}
			}
		</style>
	</section>
	<?php
}
