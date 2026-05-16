<?php
/**
 * Register a custom block for Services Tabs Section.
 */

add_action( 'acf/init', 'register_services_tabs_section_block', 20 );
function register_services_tabs_section_block() {
	if ( function_exists( 'acf_register_block_type' ) ) {
		acf_register_block_type( array(
			'name'            => 'sennovate-service-tabs-section',
			'title'           => 'Sennovate Service Tabs Section',
			'description'     => 'A section with toggleable tabs for services and a CTA banner.',
			'render_callback' => 'sennovate_render_services_tabs_section_callback',
			'category'        => 'formatting',
			'icon'            => 'index-card',
			'keywords'        => array( 'services', 'tabs', 'toggle', 'cta' ),
			'supports'        => array(
				'align'  => true,
				'anchor' => true,
			),
		) );

		// Register Fields
		acf_add_local_field_group(array(
			'key' => 'group_services_tabs_section',
			'title' => 'Services Tabs Section Fields',
			'fields' => array(
				array(
					'key' => 'field_sts_title',
					'label' => 'Section Title',
					'name' => 'sts_title',
					'type' => 'text',
					'default_value' => 'From Identity to Incident. One Team Owns It.',
				),
				array(
					'key' => 'field_sts_subtitle',
					'label' => 'Section Subtitle',
					'name' => 'sts_subtitle',
					'type' => 'text',
					'default_value' => 'Four practices. One accountable team. Engineered as one system.',
				),
				// Tab 1: Cybersecurity
				array(
					'key' => 'field_sts_tab1_label',
					'label' => 'Tab 1 Label',
					'name' => 'sts_tab1_label',
					'type' => 'text',
					'default_value' => 'Cybersecurity Services',
				),
				array(
					'key' => 'field_sts_tab1_services',
					'label' => 'Tab 1 Services',
					'name' => 'sts_tab1_services',
					'type' => 'repeater',
					'sub_fields' => array(
						array(
							'key' => 'field_sts_tab1_service_icon',
							'label' => 'Icon',
							'name' => 'icon',
							'type' => 'image',
							'return_format' => 'array',
						),
						array(
							'key' => 'field_sts_tab1_service_label',
							'label' => 'Label',
							'name' => 'label',
							'type' => 'text',
						),
						array(
							'key' => 'field_sts_tab1_service_link',
							'label' => 'Link',
							'name' => 'link',
							'type' => 'link',
						),
					),
				),
				array(
					'key' => 'field_sts_tab1_image',
					'label' => 'Tab 1 Image',
					'name' => 'sts_tab1_image',
					'type' => 'image',
					'return_format' => 'array',
				),
				// Tab 2: Infrastructure
				array(
					'key' => 'field_sts_tab2_label',
					'label' => 'Tab 2 Label',
					'name' => 'sts_tab2_label',
					'type' => 'text',
					'default_value' => 'Infrastructure Services',
				),
				array(
					'key' => 'field_sts_tab2_services',
					'label' => 'Tab 2 Services',
					'name' => 'sts_tab2_services',
					'type' => 'repeater',
					'sub_fields' => array(
						array(
							'key' => 'field_sts_tab2_service_icon',
							'label' => 'Icon',
							'name' => 'icon',
							'type' => 'image',
							'return_format' => 'array',
						),
						array(
							'key' => 'field_sts_tab2_service_label',
							'label' => 'Label',
							'name' => 'label',
							'type' => 'text',
						),
						array(
							'key' => 'field_sts_tab2_service_link',
							'label' => 'Link',
							'name' => 'link',
							'type' => 'link',
						),
					),
				),
				array(
					'key' => 'field_sts_tab2_image',
					'label' => 'Tab 2 Image',
					'name' => 'sts_tab2_image',
					'type' => 'image',
					'return_format' => 'array',
				),
				// CTA Banner
				array(
					'key' => 'field_sts_cta_icon',
					'label' => 'CTA Icon',
					'name' => 'sts_cta_icon',
					'type' => 'image',
					'return_format' => 'array',
				),
				array(
					'key' => 'field_sts_cta_title',
					'label' => 'CTA Title',
					'name' => 'sts_cta_title',
					'type' => 'text',
					'default_value' => 'Strike Teams Built Into Your Contract.',
				),
				array(
					'key' => 'field_sts_cta_desc',
					'label' => 'CTA Description',
					'name' => 'sts_cta_desc',
					'type' => 'textarea',
					'default_value' => 'Strike Teams built for security and infrastructure breaches, audits, outages, migrations, and critical incidents.',
				),
				array(
					'key' => 'field_sts_cta_link',
					'label' => 'CTA Link',
					'name' => 'sts_cta_link',
					'type' => 'link',
				),
				array(
					'key' => 'field_sts_footer_link',
					'label' => 'Footer Button Link',
					'name' => 'sts_footer_link',
					'type' => 'link',
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'block',
						'operator' => '==',
						'value' => 'acf/services-tabs-section',
					),
				),
			),
		));
	}
}

/**
 * Render Callback.
 */
function sennovate_render_services_tabs_section_callback( $block, $content = '', $is_preview = false, $post_id = 0 ) {
	$title    = get_field( 'sts_title' ) ?: 'From Identity to Incident. One Team Owns It.';
	$subtitle = get_field( 'sts_subtitle' ) ?: 'Four practices. One accountable team. Engineered as one system.';
	
	$tab1_label = get_field( 'sts_tab1_label' ) ?: 'Cybersecurity Services';
	$tab1_services = get_field( 'sts_tab1_services' );
	$tab1_image = get_field( 'sts_tab1_image' );

	$tab2_label = get_field( 'sts_tab2_label' ) ?: 'Infrastructure Services';
	$tab2_services = get_field( 'sts_tab2_services' );
	$tab2_image = get_field( 'sts_tab2_image' );

	$cta_icon = get_field( 'sts_cta_icon' );
	$cta_title = get_field( 'sts_cta_title' ) ?: 'Strike Teams Built Into Your Contract.';
	$cta_desc = get_field( 'sts_cta_desc' ) ?: 'Strike Teams built for security and infrastructure breaches, audits, outages, migrations, and critical incidents. Pre-staffed and ready to deploy in minutes, not days.';
	$cta_link = get_field( 'sts_cta_link' );
	
	$footer_link = get_field( 'sts_footer_link' );

	$anchor     = ! empty( $block['anchor'] ) ? 'id="' . esc_attr( $block['anchor'] ) . '" ' : '';
	$class_name = 'sts-section' . ( ! empty( $block['className'] ) ? ' ' . $block['className'] : '' );
	?>

	<div <?php echo $anchor; ?> class="<?php echo esc_attr( $class_name ); ?>">
		<div class="sts-container">
			<div class="sts-header">
				<h2 class="sts-title"><?php echo esc_html( $title ); ?></h2>
				<p class="sts-subtitle"><?php echo esc_html( $subtitle ); ?></p>
			</div>

			<div class="sts-tabs-wrapper">
				<div class="sts-tabs-toggle">
					<button class="sts-tab-btn active" data-tab="tab1"><?php echo esc_html( $tab1_label ); ?></button>
					<button class="sts-tab-btn" data-tab="tab2"><?php echo esc_html( $tab2_label ); ?></button>
				</div>

				<div class="sts-tab-content active" id="tab1">
					<div class="sts-grid">
						<div class="sts-services-list">
							<?php if ( $tab1_services ) : foreach ( $tab1_services as $s ) : ?>
								<a href="<?php echo $s['link'] ? esc_url( $s['link']['url'] ) : '#'; ?>" class="sts-service-item">
									<div class="sts-service-icon-wrap">
										<?php if ( $s['icon'] ) : ?>
											<img src="<?php echo esc_url( $s['icon']['url'] ); ?>" alt="">
										<?php endif; ?>
									</div>
									<span class="sts-service-label"><?php echo esc_html( $s['label'] ); ?></span>
									<span class="sts-service-arrow"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="9" viewBox="0 0 16 9" fill="none">
  <path d="M10.1333 8.8667C10.1333 8.39677 10.5976 7.69503 11.0675 7.10603C11.6717 6.34603 12.3937 5.68293 13.2215 5.1769C13.8421 4.79753 14.5945 4.43337 15.2 4.43337M15.2 4.43337C14.5945 4.43337 13.8415 4.0692 13.2215 3.68983C12.3937 3.18317 11.6717 2.52007 11.0675 1.76133C10.5976 1.1717 10.1333 0.468699 10.1333 3.26633e-05M15.2 4.43337L1.23978e-05 4.43337" stroke="#051630" stroke-width="0.95"/>
</svg></span>
								</a>
							<?php endforeach; endif; ?>
						</div>
						<div class="sts-feature-image">
							<?php if ( $tab1_image ) : ?>
								<img src="<?php echo esc_url( $tab1_image['url'] ); ?>" alt="">
							<?php endif; ?>
						</div>
					</div>
				</div>

				<div class="sts-tab-content" id="tab2">
					<div class="sts-grid">
						<div class="sts-services-list">
							<?php if ( $tab2_services ) : foreach ( $tab2_services as $s ) : ?>
								<a href="<?php echo $s['link'] ? esc_url( $s['link']['url'] ) : '#'; ?>" class="sts-service-item">
									<div class="sts-service-icon-wrap">
										<?php if ( $s['icon'] ) : ?>
											<img src="<?php echo esc_url( $s['icon']['url'] ); ?>" alt="">
										<?php endif; ?>
									</div>
									<span class="sts-service-label"><?php echo esc_html( $s['label'] ); ?></span>
									<span class="sts-service-arrow"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="9" viewBox="0 0 16 9" fill="none">
  <path d="M10.1333 8.8667C10.1333 8.39677 10.5976 7.69503 11.0675 7.10603C11.6717 6.34603 12.3937 5.68293 13.2215 5.1769C13.8421 4.79753 14.5945 4.43337 15.2 4.43337M15.2 4.43337C14.5945 4.43337 13.8415 4.0692 13.2215 3.68983C12.3937 3.18317 11.6717 2.52007 11.0675 1.76133C10.5976 1.1717 10.1333 0.468699 10.1333 3.26633e-05M15.2 4.43337L1.23978e-05 4.43337" stroke="#051630" stroke-width="0.95"/>
</svg></span>
								</a>
							<?php endforeach; endif; ?>
						</div>
						<div class="sts-feature-image">
							<?php if ( $tab2_image ) : ?>
								<img src="<?php echo esc_url( $tab2_image['url'] ); ?>" alt="">
							<?php endif; ?>
						</div>
					</div>
				</div>
			</div>

			<div class="sts-cta-banner">
				<div class="sts-cta-icon-main">
					<?php if ( $cta_icon ) : ?>
						<img src="<?php echo esc_url( $cta_icon['url'] ); ?>" alt="">
					<?php endif; ?>
				</div>
				<div class="sts-cta-text">
					<h3 class="sts-cta-title"><?php echo esc_html( $cta_title ); ?></h3>
					<p class="sts-cta-desc"><?php echo esc_html( $cta_desc ); ?></p>
				</div>
				<div class="sts-cta-button">
					<?php if ( $cta_link ) : ?>
						<a href="<?php echo esc_url( $cta_link['url'] ); ?>" class="sts-btn-white">
							<?php echo esc_html( $cta_link['title'] ); ?> <span class="arrow"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="9" viewBox="0 0 16 9" fill="none">
  <path d="M10.1333 8.8667C10.1333 8.39677 10.5976 7.69503 11.0675 7.10603C11.6717 6.34603 12.3937 5.68293 13.2215 5.1769C13.8421 4.79753 14.5945 4.43337 15.2 4.43337M15.2 4.43337C14.5945 4.43337 13.8415 4.0692 13.2215 3.68983C12.3937 3.18317 11.6717 2.52007 11.0675 1.76133C10.5976 1.1717 10.1333 0.468699 10.1333 3.26633e-05M15.2 4.43337L1.23978e-05 4.43337" stroke="#051630" stroke-width="0.95"/>
</svg></span>
						</a>
					<?php else : ?>
						<a href="#" class="sts-btn-white">Learn more <span class="arrow"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="9" viewBox="0 0 16 9" fill="none">
  <path d="M10.1333 8.8667C10.1333 8.39677 10.5976 7.69503 11.0675 7.10603C11.6717 6.34603 12.3937 5.68293 13.2215 5.1769C13.8421 4.79753 14.5945 4.43337 15.2 4.43337M15.2 4.43337C14.5945 4.43337 13.8415 4.0692 13.2215 3.68983C12.3937 3.18317 11.6717 2.52007 11.0675 1.76133C10.5976 1.1717 10.1333 0.468699 10.1333 3.26633e-05M15.2 4.43337L1.23978e-05 4.43337" stroke="#051630" stroke-width="0.95"/>
</svg></span></a>
					<?php endif; ?>
				</div>
			</div>

			<div class="sts-footer">
				<?php if ( $footer_link ) : ?>
					<a href="<?php echo esc_url( $footer_link['url'] ); ?>" class="sts-btn-blue">
						<?php echo esc_html( $footer_link['title'] ); ?>
					</a>
				<?php else : ?>
					<a href="#" class="wp-block-button__link hss__cta">View All Services</a>
				<?php endif; ?>
			</div>
		</div>

		<style>
			.sts-section {
				padding: 80px 30px;
				background: #ffffff;
				font-family: 'Inter', sans-serif;
			}
			.sts-container {
				max-width: 1140px;
				margin: 0 auto;
			}
			.sts-header {
				text-align: center;
				margin-bottom: 40px;
			}
			.sts-title {
				font-family: 'Satoshi-Black', sans-serif;
				font-size: 40px;
				color: #051630;
				margin-bottom: 10px;
				line-height: 1.2;
				margin-top: 0;
			}
			.sts-subtitle {
				font-size: 18px;
				color: #505c6e;
			}
			.sts-tabs-wrapper {
				margin-bottom: 60px;
			}
			.sts-tabs-toggle {
				background: #f4f8fb;
				padding: 6px;
				border-radius: 100px;
				display: flex;
				width: fit-content;
				margin: 0 auto 40px;
				border: 1px solid #e3eefa;
			}
			.sts-tab-btn {
				padding: 12px 24px;
				border-radius: 100px;
				border: none;
				background: transparent;
				color: #505c6e;
				font-size: 16px;
				font-weight: 500;
				cursor: pointer;
				transition: all 0.3s ease;
			}
			.sts-tab-btn.active {
				background: #006FE3;
				color: #fff;
				box-shadow: 0 4px 15px rgba(0, 111, 227, 0.2);
			}
			.sts-tab-content {
				display: none;
			}
			.sts-tab-content.active {
				display: block;
				animation: fadeIn 0.5s ease;
			}
			@keyframes fadeIn {
				from { opacity: 0; transform: translateY(10px); }
				to { opacity: 1; transform: translateY(0); }
			}
			.sts-grid {
				display: grid;
				grid-template-columns: 1fr 1fr;
				gap: 40px;
				align-items: center;
			}
			.sts-services-list {
				background: #f4f8fb;
				border-radius: 20px;
				padding: 20px;
			}
			.sts-service-item {
				display: flex;
				align-items: center;
				padding: 20px;
				background: #fff;
				border-radius: 12px;
				margin-bottom: 12px;
				text-decoration: none;
				color: #051630;
				transition: all 0.3s ease;
				border: 1px solid transparent;
			}
			.sts-service-item:last-child {
				margin-bottom: 0;
			}
			.sts-service-item:hover {
				transform: translateX(10px);
				border-color: #006FE3;
				box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
			}
			.sts-service-icon-wrap {
				width: 40px;
				height: 40px;
				margin-right: 15px;
				flex-shrink: 0;
			}
			.sts-service-icon-wrap img {
				width: 100%;
				height: 100%;
				object-fit: contain;
			}
			.sts-service-label {
				font-size: 18px;
				font-weight: 600;
				flex-grow: 1;
			}
			.sts-service-arrow {
				font-size: 20px;
				color: #006FE3;
				opacity: 0.5;
				transition: all 0.3s ease;
			}
			.sts-service-item:hover .sts-service-arrow {
				opacity: 1;
				transform: translateX(5px);
			}
			.sts-feature-image img {
				width: 100%;
				border-radius: 24px;
				box-shadow: 0 20px 60px rgba(0, 0, 0, 0.1);
			}
			.sts-cta-banner {
				background: #006FE3;
				border-radius: 24px;
				padding: 40px 60px;
				display: flex;
				align-items: center;
				color: #fff;
				margin-bottom: 50px;
				position: relative;
				overflow: hidden;
			}
			.sts-cta-icon-main {
				width: 80px;
				margin-right: 40px;
				flex-shrink: 0;
			}
			.sts-cta-icon-main img {
				width: 100%;
				filter: brightness(0) invert(1);
			}
			.sts-cta-text {
				flex-grow: 1;
			}
			.sts-cta-title {
				font-family: 'Satoshi-Black', sans-serif;
				font-size: 28px;
				margin-bottom: 10px;
				color: #fff;
			}
			.sts-cta-desc {
				font-size: 16px;
				opacity: 0.9;
				max-width: 500px;
				line-height: 1.5;
			}
			.sts-cta-button {
				flex-shrink: 0;
			}


.sts-btn-white {
    background: #ffffff;
    color: var(--sennovate-dark-blue);
    padding: 9px 28px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 700;
    font-size: 16px;
    display: inline-flex;
    align-items: center;
    transition: all 0.3s ease;
    font-family: var(--font-satoshi-bold);
}

			.sts-btn-white:hover {
				transform: translateY(-3px);
				box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
				background: #f8fbff;
				color: var(--sennovate-dark-blue);
			}
			.sts-btn-white .arrow {
				margin-left: 10px;
				transition: transform 0.3s ease;
			}
			.sts-btn-white:hover .arrow {
				transform: translateX(5px);
			}

			.sts-footer { text-align: center; }

			@media (max-width: 991px) {
				.sts-grid { grid-template-columns: 1fr; gap: 30px; }
				.sts-cta-banner { flex-direction: column; text-align: center; padding: 40px; margin-bottom: 30px;}
				.sts-cta-icon-main { margin-right: 0;}
				.sts-cta-desc { margin: 0 auto 30px; }
				.sts-tabs-wrapper{
					margin-bottom: 30px;
				}
			}
			@media(max-width:767px){
			.sts-section{
				padding: 60px 30px;
			}	
			}
		</style>

		<script>
			document.addEventListener('DOMContentLoaded', function() {
				const section = document.querySelector('.sts-section');
				if (!section) return;

				const buttons = section.querySelectorAll('.sts-tab-btn');
				const contents = section.querySelectorAll('.sts-tab-content');

				buttons.forEach(btn => {
					btn.addEventListener('click', () => {
						const target = btn.dataset.tab;

						buttons.forEach(b => b.classList.remove('active'));
						contents.forEach(c => c.classList.remove('active'));

						btn.classList.add('active');
						section.querySelector('#' + target).classList.add('active');
					});
				});
			});
		</script>
	</div>
	<?php
}
