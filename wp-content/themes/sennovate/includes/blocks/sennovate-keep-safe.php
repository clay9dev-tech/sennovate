<?php
/**
 * Register a custom block for Sennovate Keep Safe Section.
 */

add_action( 'acf/init', 'register_sennovate_keep_safe_block', 20 );
function register_sennovate_keep_safe_block() {
	if ( function_exists( 'acf_register_block_type' ) ) {
		acf_register_block_type( array(
			'name'            => 'sennovate-keep-safe',
			'title'           => 'Sennovate Keep Safe',
			'description'     => 'A section with a list of items featuring icons on the left, and a main image on the right.',
			'render_callback' => 'sennovate_render_keep_safe_callback',
			'category'        => 'formatting',
			'icon'            => 'shield',
			'keywords'        => array( 'safe', 'security', 'list', 'icons', 'process' ),
			'supports'        => array(
				'align'  => true,
				'anchor' => true,
			),
		) );

		// Register Fields
		acf_add_local_field_group(array(
			'key' => 'group_sennovate_keep_safe',
			'title' => 'Sennovate Keep Safe Fields',
			'fields' => array(
				array(
					'key' => 'field_ks_title',
					'label' => 'Title',
					'name' => 'ks_title',
					'type' => 'text',
					'default_value' => 'How We Keep You Safe',
				),
				array(
					'key' => 'field_ks_subtitle',
					'label' => 'Subtitle',
					'name' => 'ks_subtitle',
					'type' => 'text',
					'default_value' => 'From signed contract to live SOC in days, not months.',
				),
				array(
					'key' => 'field_ks_image',
					'label' => 'Side Image',
					'name' => 'ks_image',
					'type' => 'image',
					'return_format' => 'array',
				),
				array(
					'key' => 'field_ks_items',
					'label' => 'Process Items',
					'name' => 'ks_items',
					'type' => 'repeater',
					'layout' => 'block',
					'button_label' => 'Add Item',
					'sub_fields' => array(
						array(
							'key' => 'field_ks_item_icon',
							'label' => 'Icon',
							'name' => 'icon',
							'type' => 'image',
							'return_format' => 'url',
						),
						array(
							'key' => 'field_ks_item_title',
							'label' => 'Item Title',
							'name' => 'title',
							'type' => 'text',
						),
						array(
							'key' => 'field_ks_item_desc',
							'label' => 'Item Description',
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
						'value' => 'acf/sennovate-keep-safe',
					),
				),
			),
		));
	}
}

/**
 * Render Callback.
 */
function sennovate_render_keep_safe_callback( $block, $content = '', $is_preview = false, $post_id = 0 ) {
	$title    = get_field( 'ks_title' );
	$subtitle = get_field( 'ks_subtitle' );
	$image    = get_field( 'ks_image' );
	$items    = get_field( 'ks_items' );

	$anchor     = ! empty( $block['anchor'] ) ? 'id="' . esc_attr( $block['anchor'] ) . '" ' : '';
	$class_name = 'sennovate-keep-safe-section ' . ( ! empty( $block['className'] ) ? ' ' . $block['className'] : '' );
	?>

	<section <?php echo $anchor; ?> class="<?php echo esc_attr( $class_name ); ?> alignfull">
		<div class="ks-container">
			<?php if ( $title || $subtitle ) : ?>
				<div class="ks-header">
					<?php if ( $title ) : ?>
						<h2 class="ks-title"><?php echo esc_html( $title ); ?></h2>
					<?php endif; ?>
					<?php if ( $subtitle ) : ?>
						<p class="ks-subtitle"><?php echo esc_html( $subtitle ); ?></p>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<div class="ks-content-grid">
				<div class="ks-list-container">
					<?php if ( $items ) : ?>
						<?php foreach ( $items as $item ) : ?>
							<div class="ks-list-item">
								<?php if ( $item['icon'] ) : ?>
									<div class="ks-icon-box">
										<img src="<?php echo esc_url( $item['icon'] ); ?>" alt="<?php echo esc_attr( $item['title'] ); ?>" class="ks-icon">
									</div>
								<?php endif; ?>
								
								<div class="ks-text-box">
									<?php if ( $item['title'] ) : ?>
										<h3 class="ks-item-title"><?php echo esc_html( $item['title'] ); ?></h3>
									<?php endif; ?>
									
									<?php if ( $item['description'] ) : ?>
										<p class="ks-item-desc"><?php echo wp_kses_post( $item['description'] ); ?></p>
									<?php endif; ?>
								</div>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>

				<?php if ( $image ) : ?>
					<div class="ks-image-wrapper">
						<img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" class="ks-main-image">
					</div>
				<?php endif; ?>
			</div>
		</div>

		<style>
			/* Sennovate Keep Safe Section Styles */
			.sennovate-keep-safe-section {
				padding: 100px 30px;
				background-color: var(--Sennovate-white, #FFF);
			}

			.ks-container {
				max-width: 1200px;
				margin: 0 auto;
			}

			.ks-header {
				text-align: center;
				margin-bottom: 70px;
			}

			.ks-title {
				font-family: var(--sennovate-medium-font) !important;
				font-size: clamp(32px, 4vw, 48px) !important;
				margin: 0 0 16px;
				color: var(--Sennovate-Dark-blue, #051630);
				font-weight: 500 !important;
				line-height: 130% !important;
			}

			.ks-subtitle {
				margin: 0;
				color: var(--Sennovate-Dark-blue, #051630);
				font-size: var(--font-size-18, 18px) !important;
				font-weight: 400;
				line-height: 140% !important;
				opacity: 0.8;
			}

			.ks-content-grid {
				display: flex;
				gap: 80px;
				align-items: center;
			}

			.ks-list-container {
				flex: 1;
				display: flex;
				flex-direction: column;
				gap: 40px;
			}

			.ks-list-item {
				display: flex;
				gap: 24px;
				align-items: flex-start;
			}

			.ks-icon-box {
				width: 80px;
				height: 80px;
				background-color: #F5F8FC;
				border-radius: 16px;
				display: flex;
				align-items: center;
				justify-content: center;
				flex-shrink: 0;
			}

			.ks-icon {
				width: 32px;
				height: 32px;
				object-fit: contain;
			}

			.ks-text-box {
				flex: 1;
				padding-top: 8px; /* Alignment tweak with icon */
			}

			.ks-item-title {
				font-family: 'Satoshi-Bold', sans-serif !important;
				font-size: 24px !important;
				font-weight: 700 !important;
				margin: 0 0 10px;
				color: var(--Sennovate-Dark-blue, #051630);
			}

			.ks-item-desc {
				margin: 0;
				color: var(--Sennovate-Dark-blue, #051630);
				font-weight: 400;
				line-height: 150% !important;
				font-size: 16px;
				opacity: 0.9;
			}

			.ks-image-wrapper {
				flex: 1;
				max-width: 580px;
				display: flex;
				justify-content: center;
			}

			.ks-main-image {
				width: 100%;
				height: auto;
				border-radius: 20px;
				object-fit: cover;
			}

			/* Responsive Design */
			@media (max-width: 991px) {
				.ks-content-grid {
					flex-direction: column-reverse;
					gap: 50px;
				}
				
				.ks-image-wrapper {
					max-width: 100%;
					width: 100%;
				}

				.ks-header {
					margin-bottom: 50px;
				}

				.sennovate-keep-safe-section {
					padding: 60px 30px;
				}
			}

			@media (max-width: 767px) {
				.ks-list-item {
					gap: 16px;
				}

				.ks-icon-box {
					width: 60px;
					height: 60px;
					border-radius: 12px;
				}

				.ks-icon {
					width: 24px;
					height: 24px;
				}

				.ks-item-title {
					font-size: 20px !important;
				}
			}
		</style>
	</section>
	<?php
}
