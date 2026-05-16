<?php
/**
 * Register a custom block for Sennovate Hero Banner.
 */

add_action( 'acf/init', 'register_sennovate_hero_banner_block', 20 );
function register_sennovate_hero_banner_block() {
	if ( function_exists( 'acf_register_block_type' ) ) {
		acf_register_block_type( array(
			'name'            => 'sennovate-hero-banner',
			'title'           => 'Sennovate Hero Banner',
			'description'     => 'A custom hero banner section for Sennovate.',
			'render_callback' => 'sennovate_render_hero_banner_callback',
			'category'        => 'formatting',
			'icon'            => 'format-image',
			'keywords'        => array( 'banner', 'home', 'hero' ),
			'supports'        => array(
				'align'  => true,
				'anchor' => true,
			),
		) );

		// Register Fields
		acf_add_local_field_group(array(
			'key' => 'group_sennovate_hero_banner',
			'title' => 'Sennovate Hero Banner Fields',
			'fields' => array(
				array(
					'key' => 'field_banner_title',
					'label' => 'Title',
					'name' => 'banner_title',
					'type' => 'text',
					'default_value' => 'See it. Own it. Solve it.',
				),
				array(
					'key' => 'field_banner_description',
					'label' => 'Description',
					'name' => 'banner_description',
					'type' => 'textarea',
					'default_value' => 'Google-native managed SecOps, identity, cloud, offensive security, and GRC under one accountable team.',
				),
				array(
					'key' => 'field_banner_primary_button',
					'label' => 'Primary Button',
					'name' => 'banner_primary_button',
					'type' => 'link',
				),
				array(
					'key' => 'field_banner_secondary_button',
					'label' => 'Secondary Button',
					'name' => 'banner_secondary_button',
					'type' => 'link',
				),
				array(
					'key' => 'field_banner_image',
					'label' => 'Image',
					'name' => 'banner_image',
					'type' => 'image',
					'return_format' => 'array',
				),
				array(
					'key' => 'field_banner_video_url',
					'label' => 'Video URL (YouTube/Vimeo)',
					'name' => 'banner_video_url',
					'type' => 'text',
				),
				array(
					'key' => 'field_banner_video_display_type',
					'label' => 'Video Display Type',
					'name' => 'banner_video_display_type',
					'type' => 'select',
					'choices' => array(
						'popup' => 'Popup (Modal)',
						'inline' => 'Inline (Embed)',
					),
					'default_value' => 'popup',
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'block',
						'operator' => '==',
						'value' => 'acf/sennovate-hero-banner',
					),
				),
			),
		));
	}
}

/**
 * Render Callback.
 */
function sennovate_render_hero_banner_callback( $block, $content = '', $is_preview = false, $post_id = 0 ) {
	$title      = get_field( 'banner_title' ) ?: 'See it. Own it. Solve it.';
	$desc       = get_field( 'banner_description' ) ?: 'Google-native managed SecOps, identity, cloud, offensive security, and GRC under one accountable team.';
	$primary    = get_field( 'banner_primary_button' );
	$secondary  = get_field( 'banner_secondary_button' );
	$image      = get_field( 'banner_image' );
	$video_url  = get_field( 'banner_video_url' );
	$video_type = get_field( 'banner_video_display_type' ) ?: 'popup';

	// Helper to convert YouTube/Vimeo URLs to embed format for popups/iframes
	if ( ! function_exists( 'sennovate_get_video_embed_url' ) ) {
		function sennovate_get_video_embed_url( $url, $autoplay = false ) {
			$params = $autoplay ? '?autoplay=1&mute=1' : '';
			if ( strpos( $url, 'youtube.com' ) !== false || strpos( $url, 'youtu.be' ) !== false ) {
				preg_match( '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/i', $url, $match );
				return isset( $match[1] ) ? 'https://www.youtube.com/embed/' . $match[1] . $params : $url;
			} elseif ( strpos( $url, 'vimeo.com' ) !== false ) {
				preg_match( '/vimeo\.com\/(?:video\/)?([0-9]+)/i', $url, $match );
				$v_params = $autoplay ? '?autoplay=1&muted=1' : '';
				return isset( $match[1] ) ? 'https://player.vimeo.com/video/' . $match[1] . $v_params : $url;
			}
			return $url;
		}
	}

	$display_video_url = $video_url ? sennovate_get_video_embed_url( $video_url, ( $video_type === 'inline' ) ) : '';

	$anchor     = ! empty( $block['anchor'] ) ? 'id="' . esc_attr( $block['anchor'] ) . '" ' : '';
	$class_name = 'home-banner-wrapper' . ( ! empty( $block['className'] ) ? ' ' . $block['className'] : '' );
	?>

	<div <?php echo $anchor; ?> class="<?php echo esc_attr( $class_name ); ?>">
		<div class="home-banner-section alignfull">
			<div class="home-banner">
				<div class="home-banner-container">
					<div class="wp-block-columns alignfull are-vertically-aligned-center">
						<div class="wp-block-column is-vertically-aligned-center content-col">
							<h1 class="wp-block-heading"><?php echo esc_html( $title ); ?></h1>
							<p class="has-xlarge-font-size"><?php echo esc_html( $desc ); ?></p>
							
							<div class="wp-block-buttons">
							<?php if ( $primary ) : ?>
								<div class="wp-block-button">
									<a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $primary['url'] ); ?>" target="<?php echo esc_attr( $primary['target'] ); ?>">
										<?php echo esc_html( $primary['title'] ); ?>
									</a>
								</div>
							<?php else : ?>
								<div class="wp-block-button">
									<a class="wp-block-button__link wp-element-button" href="https://sennovate.clay9dev.com/schedule-a-demo/">Get a Security Assessment</a>
								</div>
							<?php endif; ?>

							<?php if ( $secondary ) : ?>
								<div class="wp-block-button secondary-button">
									<a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $secondary['url'] ); ?>" target="<?php echo esc_attr( $secondary['target'] ); ?>">
										<?php echo esc_html( $secondary['title'] ); ?>
									</a>
								</div>
							<?php else : ?>
								<div class="wp-block-button secondary-button">
									<a class="wp-block-button__link wp-element-button" href="#">See Our Process</a>
								</div>
							<?php endif; ?>
						</div>
						</div>

						<div class="wp-block-column is-vertically-aligned-center image-col">
							<div class="banner-media-wrapper">
								<?php if ( $video_url && $video_type === 'inline' ) : ?>
									<iframe src="<?php echo esc_url( $display_video_url ); ?>" frameborder="0" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>
								<?php else : ?>
									<figure class="wp-block-image size-full <?php echo ( $video_url && $video_type === 'popup' ) ? 'has-video-popup' : ''; ?>">
										<?php if ( $video_url && $video_type === 'popup' ) : ?>
											<a href="<?php echo esc_url( $display_video_url ); ?>" class="video-popup-link modaal" data-modaal-type="video">
												<!-- <span class="play-button-overlay">
													<svg width="60" height="60" viewBox="0 0 60 60" fill="none" xmlns="http://www.w3.org/2000/svg">
														<circle cx="30" cy="30" r="30" fill="white" fill-opacity="0.8"/>
														<path d="M40 30L25 38.6603L25 21.3397L40 30Z" fill="#006FE3"/>
													</svg>
												</span> -->
										<?php endif; ?>

										<?php if ( $image ) : ?>
											<img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>">
										<?php else : ?>
											<img src="https://sennovate.clay9dev.com/wp-content/uploads/2026/05/sennovate-home-banner.png" alt="Sennovate Home Hero Banner">
										<?php endif; ?>

										<?php if ( $video_url && $video_type === 'popup' ) : ?>
											</a>
										<?php endif; ?>
									</figure>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<style>
		/* Block Specific Styles */
		.home-banner-section.alignfull {
			margin: 0 !important;
			width: 100vw !important;
			position: relative;
			left: 50% !important;
			margin-left: -50vw !important;
			overflow: hidden;
		}
		.home-banner-wrapper { padding-top: 0 !important; }
		.home-banner-wrapper img {
    width: 100%;
    max-width: 558px;
}
.home-banner-wrapper .wp-block-columns {
    gap: 40px;
}

		.home-banner-wrapper .home-banner {
			padding: 120px 30px 60px !important;
			border: 10px solid #ffffff;
			border-radius: 24px;
			background: linear-gradient(104deg, #E7EEFA -21.37%, #FBFDFF 38.7%, #DFF2FF 101.47%) !important;
			margin: 0 8px;
		}

		header.header .header-content {
			max-width: 1315px;
			margin: 0 auto;
			position: relative;
			border-radius: 12px;
			border: 0.5px solid #CCE4FD;
			background: #FFF;
			display: flex;
			padding: 10px 20px;
			justify-content: space-between !important;
			align-items: center;
		}

		.home-banner-container {
			max-width: 1140px;
			margin: 0 auto;
		}

		.home-banner-wrapper .home-banner h1 {
			font-family: 'Satoshi-Black', sans-serif !important;
			font-size: clamp(2rem, 5vw, 3.625rem) !important;
			color: #051630 !important;
			margin-bottom: 20px !important;
			line-height: 120% !important;
			letter-spacing: -1.16px !important;
			max-width: 420px;
		}

		.home-banner-wrapper .home-banner p {
			font-family: 'Inter', sans-serif !important;
			color: #051630 !important;
			max-width: 420px !important;
			margin-top: 15px;
			font-weight: 400;
			line-height: 140% !important;
		}

		.home-banner-wrapper .home-banner .wp-block-buttons {
			margin-top: 40px !important;
			display: flex;
			gap: 16px;
		}

		.home-banner-wrapper .home-banner .wp-block-button:first-child .wp-block-button__link {
			background: #006FE3 !important;
			color: #fff !important;
			border-radius: 6px !important;
			padding: 12px 28px !important;
			font-weight: 600 !important;
			border: none !important;
		}

		.home-banner-wrapper .home-banner .wp-block-button.secondary-button .wp-block-button__link {
			background: #fff !important;
			color: #051630 !important;
			border: 1px solid #d7e2ed !important;
			border-radius: 6px !important;
			padding: 12px 28px !important;
			font-weight: 600 !important;
		}

		.home-banner-wrapper .home-banner .wp-block-image {
			margin: 0 !important;
		}

		.banner-media-wrapper {
			position: relative;
			width: 100%;
			max-width: 558px;
			margin: 0 auto;
		}

		.banner-media-wrapper iframe {
			width: 100% !important;
			aspect-ratio: 16/9;
			border-radius: 24px;
			/* border: 8px solid #fff;
			box-shadow: 0 20px 50px rgba(0, 0, 0, 0.05); */
		}

		.has-video-popup {
			position: relative;
			cursor: pointer;
		}

		.video-popup-link {
			display: block;
			position: relative;
		}

		.play-button-overlay {
			position: absolute;
			top: 50%;
			left: 50%;
			transform: translate(-50%, -50%);
			z-index: 2;
			transition: transform 0.3s ease;
		}

		.video-popup-link:hover .play-button-overlay {
			transform: translate(-50%, -50%) scale(1.1);
		}

		.home-banner-wrapper .content-col {
			flex-basis: 100%;
		}
		
		.home-banner-wrapper .image-col {
			flex-basis: 100%;
		}

		@media(max-width: 1024px) {
			.home-banner-wrapper .home-banner {
				padding: 100px 30px 40px !important;
				margin:0;
			}
		}

		@media(max-width:991px) {
			.home-banner-wrapper .home-banner .wp-block-buttons > .wp-block-button a {
				width: 100%;
			}
			.home-banner-wrapper .home-banner .wp-block-buttons {
				flex-direction: column;
			}
			.home-banner-wrapper .home-banner p{max-width:100% !important;}
			.home-banner-wrapper .home-banner {
				padding: 40px 30px 40px !important;
			}
		}

		@media(max-width: 767px){
			.home-banner-wrapper .wp-block-columns {
				gap: 25px;
			}
			.home-banner-wrapper {
    margin-top: 20px;
}
		}

		@media screen and (max-width: 480px) {
			.home-banner-wrapper .home-banner {
				padding: 30px !important;
			}
		}
	</style>
	<?php
}
