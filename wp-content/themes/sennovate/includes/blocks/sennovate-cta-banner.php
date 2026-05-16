<?php
/**
 * Register a custom block for Sennovate CTA Banner.
 */

add_action( 'acf/init', 'register_sennovate_cta_banner_block', 20 );
function register_sennovate_cta_banner_block() {
	if ( function_exists( 'acf_register_block_type' ) ) {
		acf_register_block_type( array(
			'name'            => 'sennovate-cta-banner',
			'title'           => 'Sennovate CTA Banner',
			'description'     => 'A large CTA banner with a striped blue background.',
			'render_callback' => 'sennovate_render_cta_banner_callback',
			'category'        => 'formatting',
			'icon'            => 'megaphone',
			'keywords'        => array( 'cta', 'banner', 'sennovate', 'call to action' ),
			'supports'        => array(
				'align'  => true,
				'anchor' => true,
			),
		) );

		// Register Fields
		acf_add_local_field_group(array(
			'key' => 'group_sennovate_cta_banner',
			'title' => 'Sennovate CTA Banner Fields',
			'fields' => array(
				array(
					'key' => 'field_scb_title',
					'label' => 'Main Title',
					'name' => 'scb_title',
					'type' => 'text',
					'default_value' => "Share Your Stack. We'll Show You the Plan.",
				),
				array(
					'key' => 'field_scb_subtitle',
					'label' => 'Subtitle',
					'name' => 'scb_subtitle',
					'type' => 'textarea',
					'default_value' => "A conversation about your stack, what's working, and where Sennovate adds value.",
				),
				array(
					'key' => 'field_scb_button',
					'label' => 'Button',
					'name' => 'scb_button',
					'type' => 'link',
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'block',
						'operator' => '==',
						'value' => 'acf/sennovate-cta-banner',
					),
				),
			),
		));
	}
}

/**
 * Render Callback.
 */
function sennovate_render_cta_banner_callback( $block, $content = '', $is_preview = false, $post_id = 0 ) {
	$title    = get_field( 'scb_title' ) ?: "Share Your Stack. We'll Show You the Plan.";
	$subtitle = get_field( 'scb_subtitle' ) ?: "A conversation about your stack, what's working, and where Sennovate adds value.";
	$button   = get_field( 'scb_button' );

	$anchor     = ! empty( $block['anchor'] ) ? 'id="' . esc_attr( $block['anchor'] ) . '" ' : '';
	$class_name = 'scb-section' . ( ! empty( $block['className'] ) ? ' ' . $block['className'] : '' );
	?>

	<div <?php echo $anchor; ?> class="<?php echo esc_attr( $class_name ); ?> alignfull">
		<div class="scb-container">
			<div class="scb-banner">
				<div class="scb-content">
					<h2 class="scb-title"><?php echo esc_html( $title ); ?></h2>
					<p class="scb-subtitle"><?php echo esc_html( $subtitle ); ?></p>
					
					<div class="scb-button-wrap">
						<?php if ( $button ) : ?>
							<a href="<?php echo esc_url( $button['url'] ); ?>" class="scb-btn-white" target="<?php echo esc_attr( $button['target'] ); ?>">
								<?php echo esc_html( $button['title'] ); ?> <span class="arrow">→</span>
							</a>
						<?php else : ?>
							<a href="#" class="scb-btn-white">Book a Call <span class="arrow"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
  <path d="M14.1335 18.2954C14.1335 17.8255 14.5978 17.1237 15.0677 16.5347C15.6719 15.7747 16.3939 15.1116 17.2217 14.6056C17.8423 14.2262 18.5947 13.8621 19.2002 13.8621M19.2002 13.8621C18.5947 13.8621 17.8417 13.4979 17.2217 13.1185C16.3939 12.6119 15.6719 11.9488 15.0677 11.19C14.5978 10.6004 14.1335 9.89741 14.1335 9.42874M19.2002 13.8621H4.0002" stroke="#051630" stroke-width="0.95"/>
</svg></span></a>
						<?php endif; ?>
					</div>
				</div>
			</div>

		</div>

		<style>
			.scb-section {
    background: #fff;
    font-family: 'Inter', sans-serif;
    padding-bottom: 80px;
}
			.scb-container {
				max-width: 1140px;
				margin: 0 auto;
				position: relative;
			}
			.scb-banner {
			padding: 60px;
    text-align: center;
    color: #ffffff;
    position: relative;
    overflow: hidden;
    border-radius: 24px;
    background: #005BB9;
}
			.scb-banner::before {
				content: '';
				position: absolute;
				top: 0;
				left: 0;
				width: 100%;
				height: 100%;
				background: url(https://sennovate.clay9dev.com/wp-content/uploads/2026/05/cta-banner-bg.webp) center / cover no-repeat;
				opacity: 0.5;
				mix-blend-mode: soft-light;
				z-index: 1;
			}
			.scb-content {
				position: relative;
				z-index: 2;
				max-width: 800px;
				margin: 0 auto;
			}
			.scb-title {
    font-family: var(--font-satoshi-bold) !important;
    line-height: 1.3 !important;
    color: #ffffff;
    margin: 0 auto 20px;
    max-width: 590px;
}
			.scb-subtitle {
    font-size: 20px !important;
    margin-bottom: 40px;
    max-width: 495px;
    margin-left: auto;
    margin-right: auto;
    line-height: 1.4 !important;
    margin-top: 0;
    font-weight: 400;
}

.scb-btn-white {
    background: #ffffff;
    color: var(--sennovate-dark-blue);
    padding: 9px 36px;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 700;
    font-size: 16px;
    display: inline-flex;
    align-items: center;
    transition: all 0.3s ease;
    font-family: var(--font-satoshi-bold);
}

			.scb-btn-white:hover {
				transform: translateY(-3px);
				box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
				background: #f8fbff;
				color: var(--sennovate-dark-blue);
			}
			.scb-btn-white .arrow {
				margin-left: 10px;
				transition: transform 0.3s ease;
				height: 32px;
			}
			.scb-btn-white .arrow svg {
    height: 30px;
    width: 30px;
}
			.scb-btn-white:hover .arrow {
				transform: translateX(5px);
			}
@media(max-width: 1024px){
	.scb-banner {
   border-radius: 0;
}
}

			@media (max-width: 768px) {
				.scb-banner {
					padding: 60px 30px;
				}
				.scb-section {
					padding: 0 0 40px 0;
				}
			}
		</style>
	</div>
	<?php
}
