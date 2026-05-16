<?php
/**
 * Register a custom block for Sennovate Strike Team.
 */

add_action( 'acf/init', 'register_sennovate_strike_team_block', 20 );
function register_sennovate_strike_team_block() {
	if ( function_exists( 'acf_register_block_type' ) ) {
		acf_register_block_type( array(
			'name'            => 'sennovate-strike-team',
			'title'           => 'Sennovate Strike Team',
			'description'     => 'A specialized section for Strike Teams with a main content area and a 4-card feature grid.',
			'render_callback' => 'sennovate_render_strike_team_callback',
			'category'        => 'formatting',
			'icon'            => 'shield',
			'keywords'        => array( 'strike', 'team', 'security', 'sennovate' ),
			'supports'        => array(
				'align'  => true,
				'anchor' => true,
			),
		) );

		// Register Fields
		acf_add_local_field_group(array(
			'key' => 'group_sennovate_strike_team',
			'title' => 'Sennovate Strike Team Fields',
			'fields' => array(
				array(
					'key' => 'field_sst_header_title',
					'label' => 'Section Header Title',
					'name' => 'sst_header_title',
					'type' => 'text',
					'default_value' => 'Strike Teams. On-demand security experts. Built into your contract.',
				),
				array(
					'key' => 'field_sst_header_subtitle',
					'label' => 'Section Header Subtitle',
					'name' => 'sst_header_subtitle',
					'type' => 'text',
					'default_value' => 'Strike teams for breaches, audits, and critical incidents. Ready to deploy.',
				),
				array(
					'key' => 'field_sst_bg_color',
					'label' => 'Section Background Color',
					'name' => 'sst_bg_color',
					'type' => 'color_picker',
					'default_value' => '#006FE3',
				),
				// Left Column
				array(
					'key' => 'field_sst_left_logo',
					'label' => 'Left Column Logo/Icon',
					'name' => 'sst_left_logo',
					'type' => 'image',
					'return_format' => 'array',
				),
				array(
					'key' => 'field_sst_left_title',
					'label' => 'Left Column Title',
					'name' => 'sst_left_title',
					'type' => 'text',
					'default_value' => 'Build Your Security Strike Team',
				),
				array(
					'key' => 'field_sst_left_desc',
					'label' => 'Left Column Description',
					'name' => 'sst_left_desc',
					'type' => 'textarea',
				),
				array(
					'key' => 'field_sst_left_button',
					'label' => 'Left Column Button',
					'name' => 'sst_left_button',
					'type' => 'link',
				),
				// Right Grid
				array(
					'key' => 'field_sst_features',
					'label' => 'Feature Cards (Right Side)',
					'name' => 'sst_features',
					'type' => 'repeater',
					'layout' => 'block',
					'max' => 4,
					'sub_fields' => array(
						array(
							'key' => 'field_sst_feature_icon',
							'label' => 'Icon',
							'name' => 'icon',
							'type' => 'image',
							'return_format' => 'url',
						),
						array(
							'key' => 'field_sst_feature_title',
							'label' => 'Title',
							'name' => 'title',
							'type' => 'text',
						),
						array(
							'key' => 'field_sst_feature_desc',
							'label' => 'Description',
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
						'value' => 'acf/sennovate-strike-team',
					),
				),
			),
		));
	}
}

/**
 * Render Callback.
 */
function sennovate_render_strike_team_callback( $block, $content = '', $is_preview = false, $post_id = 0 ) {
	$h_title    = get_field( 'sst_header_title' );
	$h_subtitle = get_field( 'sst_header_subtitle' );
	$bg_color   = get_field( 'sst_bg_color' ) ?: '#006FE3';
	
	$left_logo  = get_field( 'sst_left_logo' );
	$left_title = get_field( 'sst_left_title' );
	$left_desc  = get_field( 'sst_left_desc' );
	$left_btn   = get_field( 'sst_left_button' );
	
	$features   = get_field( 'sst_features' );

	$anchor     = ! empty( $block['anchor'] ) ? 'id="' . esc_attr( $block['anchor'] ) . '" ' : '';
	$class_name = 'sst-section' . ( ! empty( $block['className'] ) ? ' ' . $block['className'] : '' );
	?>

	<div <?php echo $anchor; ?> class="<?php echo esc_attr( $class_name ); ?> alignfull" style="background-color: <?php echo esc_attr( $bg_color ); ?>;">
		<div class="sst-container">
			
			<div class="sst-header">
				<h2 class="sst-header-title"><?php echo wp_kses_post( $h_title ); ?></h2>
				<p class="sst-header-subtitle"><?php echo esc_html( $h_subtitle ); ?></p>
			</div>

			<div class="sst-main-card">
				<div class="sst-grid">
					
					<div class="sst-content-col">
						<div class="sst-logo-wrap">
							<?php if ( $left_logo ) : ?>
								<img src="<?php echo esc_url( $left_logo['url'] ); ?>" alt="">
							<?php endif; ?>
						</div>
						<h3 class="sst-main-title"><?php echo esc_html( $left_title ); ?></h3>
						<p class="sst-main-desc"><?php echo esc_html( $left_desc ); ?></p>
						
						<?php if ( $left_btn ) : ?>
							<div class="sst-cta-wrap">
								<a href="<?php echo esc_url( $left_btn['url'] ); ?>" class="wp-block-button__link hss__cta" target="<?php echo esc_attr( $left_btn['target'] ); ?>">
									<?php echo esc_html( $left_btn['title'] ); ?> 
									<span class="arrow">
										<svg xmlns="http://www.w3.org/2000/svg" width="16" height="9" viewBox="0 0 16 9" fill="none">
											<path d="M10.1333 8.8667C10.1333 8.39677 10.5976 7.69503 11.0675 7.10603C11.6717 6.34603 12.3937 5.68293 13.2215 5.1769C13.8421 4.79753 14.5945 4.43337 15.2 4.43337M15.2 4.43337C14.5945 4.43337 13.8415 4.0692 13.2215 3.68983C12.3937 3.18317 11.6717 2.52007 11.0675 1.76133C10.5976 1.1717 10.1333 0.468699 10.1333 3.26633e-05M15.2 4.43337L1.23978e-05 4.43337" stroke="white" stroke-width="0.95"/>
										</svg>
									</span>
								</a>
							</div>
						<?php endif; ?>
					</div>

					<div class="sst-features-col">
						<div class="sst-features-grid">
							<?php if ( $features ) : foreach ( $features as $f ) : ?>
								<div class="sst-feature-card">
									<div class="sst-f-icon">
										<?php if ( $f['icon'] ) : ?>
											<img src="<?php echo esc_url( $f['icon'] ); ?>" alt="">
										<?php endif; ?>
									</div>
									<h4 class="sst-f-title"><?php echo esc_html( $f['title'] ); ?></h4>
									<p class="sst-f-desc"><?php echo esc_html( $f['description'] ); ?></p>
								</div>
							<?php endforeach; endif; ?>
						</div>
					</div>

				</div>
			</div>

		</div>

		<style>
			.sst-section {
				padding: 80px 30px;
				font-family: 'Inter', sans-serif;
				color: #ffffff;
			}
			.sst-container {
				max-width: 1200px;
				margin: 0 auto;
			}
			.sst-header {
				text-align: center;
				max-width: 800px;
				margin: 0 auto 60px;
			}
			.sst-header-title {
    font-family: var(--sennovate-medium-font) !important;
    font-size: clamp(32px, 4vw, 46px) !important;
    line-height: 1.3 !important;
    margin-bottom: 16px !important;
    color: var(--Sennovate-white, #FFF);
    text-align: center;
    font-weight: 500 !important;
    margin: 0;
}
			.sst-header-subtitle {
    font-size: 18px !important;
    margin: 0;
    line-height: 1.4 !important;
    font-weight: 400;
}

			.sst-main-card {
				background: #ffffff;
				color: #051630;
				border-radius: 24px;
				padding: 60px;
				box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
			}

			.sst-grid {
				display: grid;
				grid-template-columns: 1fr 1.5fr;
				gap: 60px;
				align-items: center;
			}

			.sst-logo-wrap {
				width: 104px;
				margin-bottom: 29px;
			}
			.sst-logo-wrap img { width: 100%; height: auto; }

			.sst-main-title {
    font-family: var(--font-satoshi-bold) !important;
    color: var(--sennovate-dark-blue) !important;
    margin-bottom: 24px !important;
    line-height: 1.3 !important;
    margin: 0;
    font-weight: 700 !important;
}
			.sst-main-desc {
    margin: 0 0 40px;
    color: var(--Sennovate-Dark-blue, #051630);
    font-weight: 400;
    line-height: 140% !important;
}

			.wp-block-button__link.hss__cta .arrow {
				display: inline-flex;
				align-items: center;
				transition: transform 0.3s ease;
				margin-left: 10px;
				height: 32px;
			}
			.wp-block-button__link.hss__cta .arrow svg {
				width: 24px;
				height: 24px;
			}
			.wp-block-button__link.hss__cta:hover .arrow { transform: translateX(5px); }

			/* Features Grid */
			.sst-features-grid {
				display: grid;
				grid-template-columns: 1fr 1fr;
				gap: 24px;
			}
			.sst-feature-card {
    padding: 24px;
    transition: 0.3s;
    border-radius: 12px;
    border: var(--stroke-weight-1, 1px) solid var(--Sennovate-stroke, #D7E2ED);
    background: var(--Sennovate-white, #FFF);
    display: flex;
    align-items: flex-start;
    gap: 10px;
    align-self: stretch;
    flex-direction: column;
}
			.sst-feature-card:hover {
				background: #ffffff;
				border-color: #006FE3;
				box-shadow: 0 10px 30px rgba(0, 111, 227, 0.05);
				transform: translateY(-3px);
			}

			.sst-f-icon {
				width: 40px;
				height: 40px;
				margin-bottom: 20px;
			}
			.sst-f-icon img { width: 100%; height: 100%; object-fit: contain; }

.sst-f-title {
    font-family: 'Satoshi-Bold', sans-serif !important;
    font-size: 24px !important;
    color: #051630 !important;
    margin-bottom: 12px !important;
    font-weight: 700 !important;
    line-height: 1.3 !important;
    margin: 0 0 6px;
}
			.sst-f-desc {
    margin: 0;
    color: var(--Sennovate-Dark-blue, #051630);
    font-weight: 400;
    line-height: 140% !important;
}
			a.wp-block-button__link.hss__cta {
    display: inline-flex;
    align-items: center;
}

			/* Responsive */
			@media (max-width: 1024px) {
				.sst-grid { grid-template-columns: 1fr; gap: 50px; }
				.sst-main-card { padding: 40px 30px; }
				.sst-content-col {
					text-align: center;
					max-width: 800px;
					margin: auto;
				}
				.sst-logo-wrap { margin: 0 auto 30px; }
			}
			@media(max-width: 991px){
				.sst-section {
    padding: 60px 30px;}
			}

			@media (max-width: 767px) {
				.sst-features-grid { grid-template-columns: 1fr; }
				.sst-header-title { font-size: 32px !important; }
				.sst-main-card { padding: 30px 20px; }
				.sst-header {
    margin: 0 auto 40px;
}

			}
		</style>
	</div>
	<?php
}
