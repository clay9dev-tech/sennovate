<?php
/**
 * Register a custom block for Sennovate Engagement Models.
 */

add_action( 'acf/init', 'register_sennovate_engagement_models_block', 20 );
function register_sennovate_engagement_models_block() {
	if ( function_exists( 'acf_register_block_type' ) ) {
		acf_register_block_type( array(
			'name'            => 'sennovate-engagement-models',
			'title'           => 'Sennovate Engagement Models',
			'description'     => 'A 3-column grid of engagement model pricing/feature cards.',
			'render_callback' => 'sennovate_render_engagement_models_callback',
			'category'        => 'formatting',
			'icon'            => 'columns',
			'keywords'        => array( 'engagement', 'models', 'pricing', 'sennovate' ),
			'supports'        => array(
				'align'  => true,
				'anchor' => true,
			),
		) );

		// Register Fields
		acf_add_local_field_group(array(
			'key' => 'group_sennovate_engagement_models',
			'title' => 'Sennovate Engagement Models Fields',
			'fields' => array(
				array(
					'key' => 'field_sem_header_title',
					'label' => 'Section Header Title',
					'name' => 'sem_header_title',
					'type' => 'text',
					'default_value' => 'Three Engagement Models. One Relationship.',
				),
				array(
					'key' => 'field_sem_header_subtitle',
					'label' => 'Section Header Subtitle',
					'name' => 'sem_header_subtitle',
					'type' => 'text',
				),
				array(
					'key' => 'field_sem_cards',
					'label' => 'Engagement Cards',
					'name' => 'sem_cards',
					'type' => 'repeater',
					'layout' => 'block',
					'max' => 3,
					'sub_fields' => array(
						array(
							'key' => 'field_sem_card_label_text',
							'label' => 'Label Text',
							'name' => 'label_text',
							'type' => 'text',
						),
						array(
							'key' => 'field_sem_card_label_color',
							'label' => 'Label Text Color',
							'name' => 'label_text_color',
							'type' => 'color_picker',
						),
						array(
							'key' => 'field_sem_card_label_bg',
							'label' => 'Label Background Color',
							'name' => 'label_bg_color',
							'type' => 'color_picker',
						),
						array(
							'key' => 'field_sem_card_title',
							'label' => 'Card Title',
							'name' => 'title',
							'type' => 'text',
						),
						array(
							'key' => 'field_sem_card_desc',
							'label' => 'Description',
							'name' => 'description',
							'type' => 'textarea',
							'rows' => 3,
						),
						array(
							'key' => 'field_sem_card_features',
							'label' => 'Features List',
							'name' => 'features',
							'type' => 'repeater',
							'layout' => 'table',
							'sub_fields' => array(
								array(
									'key' => 'field_sem_card_feature_text',
									'label' => 'Feature Text',
									'name' => 'text',
									'type' => 'text',
								),
							),
						),
						array(
							'key' => 'field_sem_card_button',
							'label' => 'Button',
							'name' => 'button',
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
						'value' => 'acf/sennovate-engagement-models',
					),
				),
			),
		));
	}
}

/**
 * Render Callback.
 */
function sennovate_render_engagement_models_callback( $block, $content = '', $is_preview = false, $post_id = 0 ) {
	$h_title    = get_field( 'sem_header_title' );
	$h_subtitle = get_field( 'sem_header_subtitle' );
	$cards      = get_field( 'sem_cards' );

	$anchor     = ! empty( $block['anchor'] ) ? 'id="' . esc_attr( $block['anchor'] ) . '" ' : '';
	$class_name = 'sem-section' . ( ! empty( $block['className'] ) ? ' ' . $block['className'] : '' );
	?>

	<div <?php echo $anchor; ?> class="<?php echo esc_attr( $class_name ); ?> alignfull">
		<div class="sem-container">
			
			<div class="sem-header">
				<?php if ( $h_title ) : ?><h2 class="sem-header-title"><?php echo esc_html( $h_title ); ?></h2><?php endif; ?>
				<?php if ( $h_subtitle ) : ?><p class="sem-header-subtitle"><?php echo esc_html( $h_subtitle ); ?></p><?php endif; ?>
			</div>

			<div class="sem-grid">
				<?php if ( $cards ) : foreach ( $cards as $card ) : 
					$label_color = $card['label_text_color'] ?: '#006FE3';
					$label_bg    = $card['label_bg_color'] ?: '#E5F0FA';
				?>
					<div class="sem-card">
						<?php if ( $card['label_text'] ) : ?>
							<div class="sem-card-label" style="color: <?php echo esc_attr($label_color); ?>; background-color: <?php echo esc_attr($label_bg); ?>;">
								<?php echo esc_html( $card['label_text'] ); ?>
							</div>
						<?php endif; ?>
						
						<h3 class="sem-card-title"><?php echo esc_html( $card['title'] ); ?></h3>
						
						<?php if ( $card['description'] ) : ?>
							<p class="sem-card-desc"><?php echo esc_html( $card['description'] ); ?></p>
						<?php endif; ?>

						<?php if ( !empty($card['features']) ) : ?>
							<ul class="sem-features-list">
								<?php foreach ( $card['features'] as $feature ) : ?>
									<li>
										<span class="sem-check-icon">
											<svg xmlns="http://www.w3.org/2000/svg" width="16" height="9" viewBox="0 0 16 9" fill="none">
												<path d="M10.1333 8.8667C10.1333 8.39677 10.5976 7.69503 11.0675 7.10603C11.6717 6.34603 12.3937 5.68293 13.2215 5.1769C13.8421 4.79753 14.5945 4.43337 15.2 4.43337M15.2 4.43337C14.5945 4.43337 13.8415 4.0692 13.2215 3.68983C12.3937 3.18317 11.6717 2.52007 11.0675 1.76133C10.5976 1.1717 10.1333 0.468699 10.1333 3.26633e-05M15.2 4.43337L1.23978e-05 4.43337" stroke="currentColor" stroke-width="0.95"/>
											</svg>
										</span>
										<?php echo esc_html( $feature['text'] ); ?>
									</li>
								<?php endforeach; ?>
							</ul>
						<?php endif; ?>

						<div class="sem-card-footer">
							<?php if ( $card['button'] ) : ?>
								<a href="<?php echo esc_url( $card['button']['url'] ); ?>" class="sem-btn-blue" target="<?php echo esc_attr( $card['button']['target'] ); ?>">
									<?php echo esc_html( $card['button']['title'] ); ?> 
									<span class="arrow">
										<svg xmlns="http://www.w3.org/2000/svg" width="16" height="9" viewBox="0 0 16 9" fill="none">
											<path d="M10.1333 8.8667C10.1333 8.39677 10.5976 7.69503 11.0675 7.10603C11.6717 6.34603 12.3937 5.68293 13.2215 5.1769C13.8421 4.79753 14.5945 4.43337 15.2 4.43337M15.2 4.43337C14.5945 4.43337 13.8415 4.0692 13.2215 3.68983C12.3937 3.18317 11.6717 2.52007 11.0675 1.76133C10.5976 1.1717 10.1333 0.468699 10.1333 3.26633e-05M15.2 4.43337L1.23978e-05 4.43337" stroke="currentColor" stroke-width="0.95"/>
										</svg>
									</span>
								</a>
							<?php endif; ?>
						</div>
					</div>
				<?php endforeach; endif; ?>
			</div>

		</div>

		<style>
			.sem-section {
				padding: 80px 30px;
				font-family: 'Inter', sans-serif;
				background-color: #ffffff;
			}
			.sem-container {
				max-width: 1200px;
				margin: 0 auto;
			}
			.sem-header {
				text-align: center;
				margin-bottom: 60px;
			}
			.sem-header-title {
				font-family: var(--sennovate-medium-font) !important;
				font-size: clamp(32px, 4vw, 46px) !important;
				line-height: 1.3 !important;
				color: var(--Sennovate-Dark-blue, #051630) !important;
				font-weight: 500 !important;
				margin:0;
			}
			.sem-header-subtitle {
				font-size: 18px !important;
				margin: 0;
				line-height: 1.4 !important;
				font-weight: 400;
				color: #505c6e;
			}

			.sem-grid {
				display: grid;
				grid-template-columns: repeat(3, 1fr);
				gap: 30px;
			}

			.sem-card {
    padding: 24px 24px 40px;
    display: flex;
    flex-direction: column;
    transition: all 0.3s ease;
    border-radius: 12px;
    border: 1px solid var(--Sennovate-stroke, #D7E2ED);
    background: var(--Sennovate-white, #FFF);
}
			.sem-card:hover {
				box-shadow: 0 10px 30px rgba(0, 111, 227, 0.08);
				border-color: #006FE3;
				transform: translateY(-4px);
			}

			.sem-card-label {
    display: inline-block;
    padding: 10px 15px;
    border-radius: 6px;
    text-transform: uppercase;
    width: fit-content;
    font-family: var(--sennovate-medium-font);
    font-size: 14px;
    font-weight: 500;
    line-height: 140%;
}

			.sem-card-title {
    font-family: var(--font-satoshi-bold) !important;
    font-size: 24px !important;
    color: var(--Sennovate-Dark-blue, #051630) !important;
    margin-bottom: 16px !important;
    line-height: 1.3 !important;
    margin: 20px 0 24px;
    font-weight: 700 !important;
}

			.sem-card-desc {
    margin: 0;
    color: var(--700, #374559);
    font-weight: 400;
    line-height: 140% !important;
}

			.sem-features-list {
    list-style: none;
    margin: 24px 0 40px 0 !important;
    flex-grow: 1;
}
			.sem-section.alignfull .sem-features-list li {
    display: flex;
    align-items: flex-start;
    color: var(--Sennovate-Dark-blue, #051630);
    margin-bottom: 16px;
    padding-left: 0 !important;
    font-size: 16px !important;
    font-weight: 400;
    line-height: 140% !important;
}
			.sem-features-list li::before{content: unset !important;}
			.sem-features-list li:last-child {
				margin-bottom: 0;
			}
			.sem-check-icon {
				margin-right: 12px;
				color: #505c6e;
				display: flex;
				align-items: center;
				margin-top: 5px;
			}
			.sem-check-icon svg {
				width: 16px;
				height: 16px;
			}

			.sem-card-footer {
				margin-top: auto;
			}
			.sem-btn-blue {
    display: flex;
    justify-content: center;
    align-items: center;
    background: #006FE3;
    color: #ffffff;
    padding: 10px 20px;
    border-radius: 6px;
    text-decoration: none;
    transition: 0.3s;
    width: 100%;
    gap: 10px;
    box-sizing: border-box;
    text-align: center;
    font-family: var(--sennovate-medium-font);
    font-size: 18px;
    font-weight: 500;
    line-height: 120%;
}
			.sem-btn-blue:hover {
				background: #005BB9;
				color: #ffffff;
			}
			.sem-btn-blue .arrow {
				display: flex;
				align-items: center;
				transition: transform 0.3s;
			}
			.sem-btn-blue:hover .arrow {
				transform: translateX(5px);
			}

			/* Responsive */
			@media (max-width: 991px) {
				.sem-grid { grid-template-columns: repeat(2, 1fr); }
				.sem-section {
    padding: 60px 30px;}
			}
			@media (max-width: 767px) {
				.sem-grid { grid-template-columns: 1fr; }
				.sem-card { padding: 30px 20px; }
			}
		</style>
	</div>
	<?php
}
