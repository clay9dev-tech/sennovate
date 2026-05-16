<?php
/**
 * Register a custom block for Sennovate Feature Grid.
 */

add_action( 'acf/init', 'register_sennovate_feature_grid_block', 20 );
function register_sennovate_feature_grid_block() {
	if ( function_exists( 'acf_register_block_type' ) ) {
		acf_register_block_type( array(
			'name'            => 'sennovate-feature-grid',
			'title'           => 'Sennovate Feature Grid',
			'description'     => 'A feature section with a main content column and a list of service cards.',
			'render_callback' => 'sennovate_render_feature_grid_callback',
			'category'        => 'formatting',
			'icon'            => 'grid-view',
			'keywords'        => array( 'feature', 'grid', 'services', 'sennovate' ),
			'supports'        => array(
				'align'  => true,
				'anchor' => true,
			),
		) );

		// Register Fields
		acf_add_local_field_group(array(
			'key' => 'group_sennovate_feature_grid',
			'title' => 'Sennovate Feature Grid Fields',
			'fields' => array(
				array(
					'key' => 'field_sfg_header_title',
					'label' => 'Section Header Title',
					'name' => 'sfg_header_title',
					'type' => 'text',
					'default_value' => 'From Identity To Incident. One Team Owns It.',
				),
				array(
					'key' => 'field_sfg_header_subtitle',
					'label' => 'Section Header Subtitle',
					'name' => 'sfg_header_subtitle',
					'type' => 'text',
					'default_value' => 'Four practices. One accountable team. Engineered as one system.',
				),
				array(
					'key' => 'field_sfg_bg_color',
					'label' => 'Section Background Color',
					'name' => 'sfg_bg_color',
					'type' => 'color_picker',
					'default_value' => '#F7F9FC',
				),
				array(
					'key' => 'field_sfg_alignment',
					'label' => 'Content Alignment',
					'name' => 'sfg_alignment',
					'type' => 'select',
					'choices' => array(
						'content-left' => 'Main Content Left / List Right',
						'content-right' => 'List Left / Main Content Right',
					),
					'default_value' => 'content-left',
				),
				array(
					'key' => 'field_sfg_main_card_bg_color',
					'label' => 'Main Card Background Color',
					'name' => 'sfg_main_card_bg_color',
					'type' => 'color_picker',
					'default_value' => '#FFFFFF',
				),
				array(
					'key' => 'field_sfg_items_bg_color',
					'label' => 'Service Items Background Color',
					'name' => 'sfg_items_bg_color',
					'type' => 'color_picker',
					'default_value' => '#FFFFFF',
				),
				// Main Content Column
				array(
					'key' => 'field_sfg_main_title',
					'label' => 'Main Column Title',
					'name' => 'sfg_main_title',
					'type' => 'text',
					'default_value' => 'Cybersecurity Services',
				),
				array(
					'key' => 'field_sfg_main_desc',
					'label' => 'Main Column Description',
					'name' => 'sfg_main_desc',
					'type' => 'textarea',
				),
				array(
					'key' => 'field_sfg_main_image',
					'label' => 'Main Column Image',
					'name' => 'sfg_main_image',
					'type' => 'image',
					'return_format' => 'array',
				),
				// Service List Column
				array(
					'key' => 'field_sfg_service_list',
					'label' => 'Service List',
					'name' => 'sfg_service_list',
					'type' => 'repeater',
					'layout' => 'block',
					'button_label' => 'Add Service Item',
					'sub_fields' => array(
						array(
							'key' => 'field_sfg_item_icon',
							'label' => 'Icon',
							'name' => 'icon',
							'type' => 'image',
							'return_format' => 'url',
						),
						array(
							'key' => 'field_sfg_item_title',
							'label' => 'Title',
							'name' => 'title',
							'type' => 'text',
						),
						array(
							'key' => 'field_sfg_item_desc',
							'label' => 'Description',
							'name' => 'description',
							'type' => 'text',
						),
						array(
							'key' => 'field_sfg_item_link',
							'label' => 'Link (Optional)',
							'name' => 'link',
							'type' => 'link',
						),
					),
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'block',
						'operator' => '==',
						'value' => 'acf/sennovate-feature-grid',
					),
				),
			),
		));
	}
}

/**
 * Render Callback.
 */
function sennovate_render_feature_grid_callback( $block, $content = '', $is_preview = false, $post_id = 0 ) {
	$h_title    = get_field( 'sfg_header_title' );
	$h_subtitle = get_field( 'sfg_header_subtitle' );
	$bg_color   = get_field( 'sfg_bg_color' ) ?: '#F7F9FC';
	$card_bg    = get_field( 'sfg_main_card_bg_color' ) ?: '#FFFFFF';
	$items_bg   = get_field( 'sfg_items_bg_color' ) ?: '#FFFFFF';
	$alignment  = get_field( 'sfg_alignment' ) ?: 'content-left';
	
	$main_title = get_field( 'sfg_main_title' );
	$main_desc  = get_field( 'sfg_main_desc' );
	$main_image = get_field( 'sfg_main_image' );
	
	$services   = get_field( 'sfg_service_list' );

	$anchor     = ! empty( $block['anchor'] ) ? 'id="' . esc_attr( $block['anchor'] ) . '" ' : '';
	$class_name = 'sfg-section ' . $alignment . ( ! empty( $block['className'] ) ? ' ' . $block['className'] : '' );
	?>

	<div <?php echo $anchor; ?> class="<?php echo esc_attr( $class_name ); ?> alignfull" style="background-color: <?php echo esc_attr( $bg_color ); ?>;">
		<div class="sfg-container">
			
			<?php if ( $h_title || $h_subtitle ) : ?>
				<div class="sfg-header">
					<?php if ( $h_title ) : ?><h2 class="sfg-header-title"><?php echo esc_html( $h_title ); ?></h2><?php endif; ?>
					<?php if ( $h_subtitle ) : ?><p class="sfg-header-subtitle"><?php echo esc_html( $h_subtitle ); ?></p><?php endif; ?>
				</div>
			<?php endif; ?>

			<div class="sfg-main-card" style="background-color: <?php echo esc_attr($card_bg); ?>;">
				<div class="sfg-grid">
					
					<div class="sfg-content-col">
						<div class="sfg-content-info">
							<h3 class="sfg-main-title"><?php echo esc_html( $main_title ); ?></h3>
							<p class="sfg-main-desc"><?php echo esc_html( $main_desc ); ?></p>
						</div>
						<div class="sfg-main-image-wrap">
							<?php if ( $main_image ) : ?>
								<img src="<?php echo esc_url( $main_image['url'] ); ?>" alt="<?php echo esc_attr( $main_image['alt'] ); ?>">
							<?php endif; ?>
						</div>
					</div>

					<div class="sfg-list-col">
						<?php if ( $services ) : foreach ( $services as $item ) : ?>
							<div class="sfg-service-item" style="background-color: <?php echo esc_attr($items_bg); ?>;">
								<div class="sfg-item-icon">
									<?php if ( $item['icon'] ) : ?>
										<img src="<?php echo esc_url( $item['icon'] ); ?>" alt="">
									<?php endif; ?>
								</div>
								<div class="sfg-item-text">
									<h4 class="sfg-item-title"><?php echo esc_html( $item['title'] ); ?></h4>
									<p class="sfg-item-desc"><?php echo esc_html( $item['description'] ); ?></p>
								</div>
								<div class="sfg-item-arrow">
									<svg xmlns="http://www.w3.org/2000/svg" width="16" height="9" viewBox="0 0 16 9" fill="none">
										<path d="M10.1333 8.8667C10.1333 8.39677 10.5976 7.69503 11.0675 7.10603C11.6717 6.34603 12.3937 5.68293 13.2215 5.1769C13.8421 4.79753 14.5945 4.43337 15.2 4.43337M15.2 4.43337C14.5945 4.43337 13.8415 4.0692 13.2215 3.68983C12.3937 3.18317 11.6717 2.52007 11.0675 1.76133C10.5976 1.1717 10.1333 0.468699 10.1333 3.26633e-05M15.2 4.43337L1.23978e-05 4.43337" stroke="#051630" stroke-width="0.95"/>
									</svg>
								</div>
								<?php if ( !empty($item['link']) ) : ?>
									<a href="<?php echo esc_url($item['link']['url']); ?>" class="sfg-item-link-overlay" target="<?php echo esc_attr($item['link']['target']); ?>"></a>
								<?php endif; ?>
							</div>
						<?php endforeach; endif; ?>
					</div>

				</div>
			</div>

		</div>

		<style>
			.sfg-section {
				padding: 80px 30px;
				font-family: 'Inter', sans-serif;
			}
			.sfg-container {
				max-width: 1200px;
				margin: 0 auto;
			}
			.sfg-header {
				text-align: center;
				margin-bottom: 60px;
			}
			.sfg-header-title {
				font-family: var(--sennovate-medium-font) !important;
				font-size: clamp(32px, 4vw, 48px) !important;
				color: var(--sennovate-dark-blue) !important;
				margin-bottom: 16px !important;
				line-height: 1.25 !important;
				margin-top: 0;
				font-weight: 500 !important;
				max-width: 572px;
				margin-left: auto;
				margin-right: auto;
			}
			.sfg-header-subtitle {
				font-size: 18px !important;
				color: var(--Sennovate-Dark-blue, #051630);
				font-weight: 400;
				line-height: 1.5 !important;
				margin: 0;
			}

			.sfg-main-card {
				background: #ffffff;
				border-radius: 12px;
				padding: 40px;
			}

			.sfg-grid {
				display: grid;
				grid-template-columns: 1fr 1fr;
				gap: 60px;
				align-items: center;
			}

			/* Alignment Logic */
			.sfg-section.content-right .sfg-content-col { order: 2; }
			.sfg-section.content-right .sfg-list-col { order: 1; }

			.sfg-main-title {
				font-family: var(--font-satoshi-bold) !important;
				margin-bottom: 20px !important;
				color: var(--Sennovate-Dark-blue, #051630) !important;
				font-weight: 700 !important;
				line-height: 130% !important;
				margin: 0;
			}
			.sfg-main-desc {
				color: var(--Sennovate-Dark-blue, #051630);
				font-weight: 400;
				line-height: 140% !important;
				margin: 0;
			}

			.sfg-content-info {
				margin-bottom: 52px;
			}

			/* Service List Items */
			.sfg-service-item {
				display: flex;
				align-items: center;
				padding: 24px;
				margin-bottom: 16px;
				position: relative;
				transition: all 0.3s ease;
				border-radius: 12px;
				border: 0.5px solid var(--Sennovate-stroke, #D7E2ED);
				gap: 24px;
			}
			.sfg-service-item:last-child {
				margin-bottom: 0;
			}
			.sfg-service-item:hover {
				border-color: #006FE3;
				box-shadow: 0 10px 25px rgba(0, 111, 227, 0.08);
				transform: translateY(-2px);
			}

			.sfg-item-icon {
				width: 48px;
				height: 48px;
				flex-shrink: 0;
			}
			.sfg-item-icon img {
				width: 100%;
				height: 100%;
				object-fit: contain;
			}
			.sfg-item-text {
				flex-grow: 1;
			}
			.sfg-item-title {
				font-family: var(--sennovate-medium-font) !important;
				margin-bottom: 6px !important;
				margin: 0;
				color: var(--Sennovate-Dark-blue, #051630) !important;
				font-size: 22px !important;
				font-weight: 500 !important;
				line-height: 130% !important;
			}
			.sfg-item-desc {
				color: var(--Sennovate-Dark-blue, #051630);
				font-weight: 400;
				line-height: 140% !important;
				margin: 0;
			}

			.sfg-item-arrow {
				flex-shrink: 0;
				opacity: 0;
				transform: translateX(-10px);
				transition: all 0.3s ease;
			}
			.sfg-service-item:hover .sfg-item-arrow {
				opacity: 1;
				transform: translateX(0);
			}
			.sfg-service-item:hover .sfg-item-arrow svg {
				width: 24px;
				height: 100%;
			}

			.sfg-item-link-overlay {
				position: absolute;
				top: 0; left: 0; width: 100%; height: 100%;
				z-index: 1;
			}

			/* Responsive */
			@media (max-width: 991px) {
				.sfg-grid { grid-template-columns: 1fr; gap: 40px; }
				.sfg-main-card { padding: 40px 20px; }
				.sfg-section.content-right .sfg-content-col { order: 1; }
				.sfg-section.content-right .sfg-list-col { order: 2; }
				.sfg-header-title{max-width: 410px;}
				.sfg-content-col {text-align: center;}
			}
			@media(max-width: 767px){
			.sfg-section {padding: 60px 30px;}
			.sfg-service-item {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
}
.sfg-item-title{font-size: 20px !important}
.sfg-service-item{padding: 16px; gap: 10px;}
.sfg-header{margin-bottom: 30px;}
.sfg-content-info {margin-bottom: 32px;}
			}
			
		</style>
	</div>
	<?php
}
