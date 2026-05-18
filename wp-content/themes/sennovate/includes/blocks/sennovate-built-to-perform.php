<?php
/**
 * Register a custom block for Sennovate Built to Perform Section.
 */

add_action( 'acf/init', 'register_sennovate_built_to_perform_block', 20 );
function register_sennovate_built_to_perform_block() {
	if ( function_exists( 'acf_register_block_type' ) ) {
		acf_register_block_type( array(
			'name'            => 'sennovate-built-to-perform',
			'title'           => 'Sennovate Built to Perform',
			'description'     => 'A section with a white card containing a list of items on the left and an image on the right.',
			'render_callback' => 'sennovate_render_built_to_perform_callback',
			'category'        => 'formatting',
			'icon'            => 'list-view',
			'keywords'        => array( 'built', 'perform', 'list', 'features' ),
			'supports'        => array(
				'align'  => true,
				'anchor' => true,
			),
		) );

		// Register Fields
		acf_add_local_field_group(array(
			'key' => 'group_sennovate_built_to_perform',
			'title' => 'Sennovate Built to Perform Fields',
			'fields' => array(
				array(
					'key' => 'field_btp_title',
					'label' => 'Title',
					'name' => 'btp_title',
					'type' => 'text',
					'default_value' => 'Built to Perform',
				),
				array(
					'key' => 'field_btp_subtitle',
					'label' => 'Subtitle',
					'name' => 'btp_subtitle',
					'type' => 'text',
					'default_value' => 'Real outcomes at every level of your SOC.',
				),
				array(
					'key' => 'field_btp_image',
					'label' => 'Side Image',
					'name' => 'btp_image',
					'type' => 'image',
					'return_format' => 'array',
				),
				array(
					'key' => 'field_btp_items',
					'label' => 'List Items',
					'name' => 'btp_items',
					'type' => 'repeater',
					'layout' => 'block',
					'button_label' => 'Add Item',
					'sub_fields' => array(
						array(
							'key' => 'field_btp_item_title',
							'label' => 'Item Title',
							'name' => 'title',
							'type' => 'text',
						),
						array(
							'key' => 'field_btp_item_desc',
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
						'value' => 'acf/sennovate-built-to-perform',
					),
				),
			),
		));
	}
}

/**
 * Render Callback.
 */
function sennovate_render_built_to_perform_callback( $block, $content = '', $is_preview = false, $post_id = 0 ) {
	$title    = get_field( 'btp_title' );
	$subtitle = get_field( 'btp_subtitle' );
	$image    = get_field( 'btp_image' );
	$items    = get_field( 'btp_items' );

	$anchor     = ! empty( $block['anchor'] ) ? 'id="' . esc_attr( $block['anchor'] ) . '" ' : '';
	$class_name = 'sennovate-built-to-perform-section ' . ( ! empty( $block['className'] ) ? ' ' . $block['className'] : '' );
	?>

	<section <?php echo $anchor; ?> class="<?php echo esc_attr( $class_name ); ?> alignfull">
		<div class="btp-container">
			<?php if ( $title || $subtitle ) : ?>
				<div class="btp-header">
					<?php if ( $title ) : ?>
						<h2 class="btp-title"><?php echo esc_html( $title ); ?></h2>
					<?php endif; ?>
					<?php if ( $subtitle ) : ?>
						<p class="btp-subtitle"><?php echo esc_html( $subtitle ); ?></p>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<div class="btp-content-card">
				<div class="btp-list-container">
					<?php if ( $items ) : ?>
						<?php foreach ( $items as $item ) : ?>
							<div class="btp-list-item">
								<?php if ( $item['title'] ) : ?>
									<h3 class="btp-item-title"><?php echo esc_html( $item['title'] ); ?></h3>
								<?php endif; ?>
								
								<?php if ( $item['description'] ) : ?>
									<p class="btp-item-desc"><?php echo wp_kses_post( $item['description'] ); ?></p>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>

				<?php if ( $image ) : ?>
					<div class="btp-image-wrapper">
						<img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" class="btp-main-image">
					</div>
				<?php endif; ?>
			</div>
		</div>

		<style>
			/* Sennovate Built to Perform Section Styles */
			.sennovate-built-to-perform-section {
				padding: 80px 30px;
				background-color: #F8FAFC; /* Light background */
			}

			.btp-container {
				max-width: 1200px;
				margin: 0 auto;
			}

			.btp-header {
				text-align: center;
				margin-bottom: 60px;
			}

			.btp-title {
				font-family: var(--sennovate-medium-font) !important;
				font-size: clamp(32px, 4vw, 48px) !important;
				margin: 0 0 12px;
				color: var(--Sennovate-Dark-blue, #051630);
				font-weight: 500 !important;
				line-height: 130% !important;
			}

			.btp-subtitle {
				margin: 0;
				color: var(--Sennovate-Dark-blue, #051630);
				font-size: var(--font-size-18, 18px) !important;
				font-weight: 400;
				line-height: 140% !important;
			}

			.btp-content-card {
				background: var(--Sennovate-white, #FFF);
				border-radius: 12px;
				border: 1px solid var(--Sennovate-stroke, #D7E2ED);
				display: flex;
				overflow: hidden;
				padding: 50px;
				gap: 50px;
				align-items: stretch;
			}

			.btp-list-container {
				flex: 1;
				display: flex;
				flex-direction: column;
				justify-content: center;
			}

			.btp-list-item {
				padding: 24px 0;
				border-bottom: 1px solid var(--Sennovate-stroke, #E2E8F0);
			}

			.btp-list-item:first-child {
				padding-top: 0;
			}

			.btp-list-item:last-child {
				padding-bottom: 0;
				border-bottom: none;
			}

			.btp-item-title {
				font-family: 'Satoshi-Bold', sans-serif !important;
				font-size: 20px !important;
				font-weight: 700 !important;
				margin: 0 0 12px;
				color: var(--Sennovate-Dark-blue, #051630);
			}

			.btp-item-desc {
				margin: 0;
				color: var(--Sennovate-Dark-blue, #051630);
				font-weight: 400;
				line-height: 150% !important;
				font-size: 16px;
			}

			.btp-image-wrapper {
				flex: 1;
				display: flex;
				align-items: center;
				justify-content: center;
				border-radius: 12px;
				overflow: hidden;
			}

			.btp-main-image {
				width: 100%;
				height: 100%;
				object-fit: contain;
			}

			/* Responsive Design */
			@media (max-width: 991px) {
				.btp-content-card {
					flex-direction: column;
					padding: 30px;
					gap: 30px;
				}
				
				.btp-image-wrapper {
					max-width: 100%;
					height: auto;
					min-height: 300px;
				}

				.btp-header {
					margin-bottom: 40px;
				}

				.sennovate-built-to-perform-section {
					padding: 60px 30px;
				}
			}

			@media (max-width: 767px) {
				.btp-content-card {
					padding: 20px;
				}
				
				.btp-item-title {
					font-size: 18px !important;
				}
			}
		</style>
	</section>
	<?php
}
