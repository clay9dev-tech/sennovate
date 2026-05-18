<?php
/**
 * Register a custom block for Sennovate Fully Connected Section.
 */

add_action( 'acf/init', 'register_sennovate_fully_connected_block', 20 );
function register_sennovate_fully_connected_block() {
	if ( function_exists( 'acf_register_block_type' ) ) {
		acf_register_block_type( array(
			'name'            => 'sennovate-fully-connected',
			'title'           => 'Sennovate Fully Connected',
			'description'     => 'A section with a solid blue background, a title, subtitle, and a 4-column grid of features separated by thin vertical lines.',
			'render_callback' => 'sennovate_render_fully_connected_callback',
			'category'        => 'formatting',
			'icon'            => 'networking',
			'keywords'        => array( 'security', 'connected', 'grid', 'features', 'blue' ),
			'supports'        => array(
				'align'  => true,
				'anchor' => true,
			),
		) );

		// Register Fields
		acf_add_local_field_group(array(
			'key' => 'group_sennovate_fully_connected',
			'title' => 'Sennovate Fully Connected Fields',
			'fields' => array(
				array(
					'key' => 'field_fc_title',
					'label' => 'Title',
					'name' => 'fc_title',
					'type' => 'text',
					'default_value' => 'Security, Fully Connected',
				),
				array(
					'key' => 'field_fc_subtitle',
					'label' => 'Subtitle',
					'name' => 'fc_subtitle',
					'type' => 'text',
					'default_value' => 'Identity coverage across every system that matters to your business.',
				),
				array(
					'key' => 'field_fc_features',
					'label' => 'Features',
					'name' => 'fc_features',
					'type' => 'repeater',
					'layout' => 'block',
					'button_label' => 'Add Feature',
					'sub_fields' => array(
						array(
							'key' => 'field_fc_feature_icon',
							'label' => 'Icon (White SVG/Image recommended)',
							'name' => 'icon',
							'type' => 'image',
							'return_format' => 'url',
						),
						array(
							'key' => 'field_fc_feature_title',
							'label' => 'Feature Title',
							'name' => 'title',
							'type' => 'text',
						),
						array(
							'key' => 'field_fc_feature_desc',
							'label' => 'Feature Description',
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
						'value' => 'acf/sennovate-fully-connected',
					),
				),
			),
		));
	}
}

/**
 * Render Callback.
 */
function sennovate_render_fully_connected_callback( $block, $content = '', $is_preview = false, $post_id = 0 ) {
	$title    = get_field( 'fc_title' );
	$subtitle = get_field( 'fc_subtitle' );
	$features = get_field( 'fc_features' );

	$anchor     = ! empty( $block['anchor'] ) ? 'id="' . esc_attr( $block['anchor'] ) . '" ' : '';
	$class_name = 'sennovate-fully-connected-section ' . ( ! empty( $block['className'] ) ? ' ' . $block['className'] : '' );
	?>

	<section <?php echo $anchor; ?> class="<?php echo esc_attr( $class_name ); ?> alignfull">
		<div class="fc-container">
			<?php if ( $title || $subtitle ) : ?>
				<div class="fc-header">
					<?php if ( $title ) : ?>
						<h2 class="fc-title"><?php echo esc_html( $title ); ?></h2>
					<?php endif; ?>
					<?php if ( $subtitle ) : ?>
						<p class="fc-subtitle"><?php echo esc_html( $subtitle ); ?></p>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<?php if ( $features ) : ?>
				<div class="fc-grid">
					<?php foreach ( $features as $feature ) : ?>
						<div class="fc-item">
							<?php if ( $feature['icon'] ) : ?>
								<div class="fc-icon-wrapper">
									<img src="<?php echo esc_url( $feature['icon'] ); ?>" alt="<?php echo esc_attr( $feature['title'] ); ?>" class="fc-icon">
								</div>
							<?php endif; ?>
							
							<?php if ( $feature['title'] ) : ?>
								<h3 class="fc-item-title"><?php echo esc_html( $feature['title'] ); ?></h3>
							<?php endif; ?>
							
							<?php if ( $feature['description'] ) : ?>
								<p class="fc-item-desc"><?php echo wp_kses_post( $feature['description'] ); ?></p>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>

		<style>
			/* Sennovate Fully Connected Section Styles */
			.sennovate-fully-connected-section {
				background-color: var(--Sennovate-primary-blue, #006FE3);
				padding: 100px 30px;
				color: #ffffff;
			}

			.fc-container {
				max-width: 1200px;
				margin: 0 auto;
			}

			.fc-header {
				text-align: center;
				margin-bottom: 60px;
			}

			.fc-title {
				font-family: var(--sennovate-medium-font) !important;
				font-size: clamp(32px, 4vw, 48px) !important;
				margin: 0 0 16px;
				color: #ffffff !important;
				font-weight: 500 !important;
				line-height: 130% !important;
			}

			.fc-subtitle {
				margin: 0;
				color: #ffffff !important;
				font-size: var(--font-size-18, 18px) !important;
				font-weight: 400;
				line-height: 140% !important;
				opacity: 0.9;
			}

			.fc-grid {
				display: flex;
				justify-content: space-between;
				align-items: stretch;
			}

			.fc-item {
				flex: 1;
				padding: 0 30px;
				border-right: 1px solid rgba(255, 255, 255, 0.4);
			}

			.fc-item:first-child {
				padding-left: 0;
			}

			.fc-item:last-child {
				padding-right: 0;
				border-right: none;
			}

			.fc-icon-wrapper {
				width: 56px;
				height: 56px;
				margin-bottom: 24px;
				display: flex;
				align-items: center;
				justify-content: flex-start;
			}

			.fc-icon {
				max-width: 100%;
				max-height: 100%;
				object-fit: contain;
			}

			.fc-item-title {
				font-family: 'Satoshi-Bold', sans-serif !important;
				font-size: 22px !important;
				font-weight: 700 !important;
				margin: 0 0 12px;
				color: #ffffff !important;
			}

			.fc-item-desc {
				margin: 0;
				color: #ffffff !important;
				font-weight: 400;
				line-height: 150% !important;
				font-size: 16px;
				opacity: 0.9;
			}

			/* Responsive Design */
			@media (max-width: 991px) {
				.fc-grid {
					flex-wrap: wrap;
					gap: 40px 0;
				}
				
				.fc-item {
					flex: 0 0 50%;
				}

				.fc-item:nth-child(2),
				.fc-item:last-child {
					border-right: none;
				}

				.fc-item:nth-child(1),
				.fc-item:nth-child(3) {
					padding-left: 0;
					padding-right: 30px;
				}
				
				.fc-item:nth-child(2),
				.fc-item:nth-child(4) {
					padding-left: 30px;
					padding-right: 0;
				}
			}

			@media (max-width: 767px) {
				.sennovate-fully-connected-section {
					padding: 60px 30px;
				}

				.fc-grid {
					flex-direction: column;
					gap: 0;
				}

				.fc-item {
					flex: 0 0 100%;
					border-right: none !important;
					border-bottom: 1px solid rgba(255, 255, 255, 0.4);
					padding: 40px 0 !important;
				}

				.fc-item:first-child {
					padding-top: 0 !important;
				}

				.fc-item:last-child {
					padding-bottom: 0 !important;
					border-bottom: none;
				}
				
				.fc-item-title {
					font-size: 20px !important;
				}
			}
		</style>
	</section>
	<?php
}
