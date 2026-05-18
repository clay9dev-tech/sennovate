<?php
/**
 * Register a custom block for Sennovate Add Value Section.
 */

add_action( 'acf/init', 'register_sennovate_add_value_block', 20 );
function register_sennovate_add_value_block() {
	if ( function_exists( 'acf_register_block_type' ) ) {
		acf_register_block_type( array(
			'name'            => 'sennovate-add-value',
			'title'           => 'Sennovate Add Value',
			'description'     => 'A section displaying a title, subtitle, grid of 4 cards on the left, and an image on the right.',
			'render_callback' => 'sennovate_render_add_value_callback',
			'category'        => 'formatting',
			'icon'            => 'grid-view',
			'keywords'        => array( 'value', 'features', 'cards', 'grid' ),
			'supports'        => array(
				'align'  => true,
				'anchor' => true,
			),
		) );

		// Register Fields
		acf_add_local_field_group(array(
			'key' => 'group_sennovate_add_value',
			'title' => 'Sennovate Add Value Fields',
			'fields' => array(
				array(
					'key' => 'field_sav_title',
					'label' => 'Title',
					'name' => 'value_title',
					'type' => 'text',
					'default_value' => 'How We Add Value',
				),
				array(
					'key' => 'field_sav_subtitle',
					'label' => 'Subtitle',
					'name' => 'value_subtitle',
					'type' => 'text',
					'default_value' => 'Three layers. One outcome your team works on what matters.',
				),
				array(
					'key' => 'field_sav_image',
					'label' => 'Side Image',
					'name' => 'value_image',
					'type' => 'image',
					'return_format' => 'array',
				),
				array(
					'key' => 'field_sav_cards',
					'label' => 'Value Cards',
					'name' => 'value_cards',
					'type' => 'repeater',
					'layout' => 'block',
					'button_label' => 'Add Card',
					'sub_fields' => array(
						array(
							'key' => 'field_sav_card_icon',
							'label' => 'Icon (SVG or Image)',
							'name' => 'icon',
							'type' => 'image',
							'return_format' => 'url',
						),
						array(
							'key' => 'field_sav_card_title',
							'label' => 'Card Title',
							'name' => 'title',
							'type' => 'text',
						),
						array(
							'key' => 'field_sav_card_desc',
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
						'value' => 'acf/sennovate-add-value',
					),
				),
			),
		));
	}
}

/**
 * Render Callback.
 */
function sennovate_render_add_value_callback( $block, $content = '', $is_preview = false, $post_id = 0 ) {
	$title    = get_field( 'value_title' );
	$subtitle = get_field( 'value_subtitle' );
	$image    = get_field( 'value_image' );
	$cards    = get_field( 'value_cards' );

	$anchor     = ! empty( $block['anchor'] ) ? 'id="' . esc_attr( $block['anchor'] ) . '" ' : '';
	$class_name = 'sennovate-add-value-section ' . ( ! empty( $block['className'] ) ? ' ' . $block['className'] : '' );
	?>

	<section <?php echo $anchor; ?> class="<?php echo esc_attr( $class_name ); ?> alignfull">
		<div class="add-value-container">
			<?php if ( $title || $subtitle ) : ?>
				<div class="add-value-header">
					<?php if ( $title ) : ?>
						<h2 class="add-value-title"><?php echo esc_html( $title ); ?></h2>
					<?php endif; ?>
					<?php if ( $subtitle ) : ?>
						<p class="add-value-subtitle"><?php echo esc_html( $subtitle ); ?></p>
					<?php endif; ?>
				</div>
			<?php endif; ?>

			<div class="add-value-content-grid">
				<div class="add-value-cards-container">
					<?php if ( $cards ) : ?>
						<?php foreach ( $cards as $card ) : ?>
							<div class="add-value-card">
								<?php if ( $card['icon'] ) : ?>
									<div class="card-icon-wrapper">
										<img src="<?php echo esc_url( $card['icon'] ); ?>" alt="<?php echo esc_attr( $card['title'] ); ?>" class="card-icon">
									</div>
								<?php endif; ?>
								
								<?php if ( $card['title'] ) : ?>
									<h3 class="card-title"><?php echo esc_html( $card['title'] ); ?></h3>
								<?php endif; ?>
								
								<?php if ( $card['description'] ) : ?>
									<p class="card-desc"><?php echo wp_kses_post( $card['description'] ); ?></p>
								<?php endif; ?>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>

				<?php if ( $image ) : ?>
					<div class="add-value-image-wrapper">
						<img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>" class="add-value-main-image">
					</div>
				<?php endif; ?>
			</div>
		</div>

		<style>
			/* Sennovate Add Value Section Styles */
			.sennovate-add-value-section {
				padding: 80px 30px;
				background-color: #ffffff;
			}

			.add-value-container {
				max-width: 1200px;
				margin: 0 auto;
			}

			.add-value-header {
				text-align: center;
				margin-bottom: 60px;
			}

			.add-value-title {
    font-family: var(--sennovate-medium-font) !important;
    font-size: clamp(32px, 4vw, 48px) !important;
    margin: 0 0 12px;
    color: var(--Sennovate-Dark-blue, #051630);
    font-weight: 500 !important;
    line-height: 130% !important;
}

.add-value-subtitle {
    margin: 0;
    color: var(--Sennovate-Dark-blue, #051630);
    font-size: var(--font-size-18, 18px) !important;
    font-weight: 400;
    line-height: 140% !important;
}

			.add-value-content-grid {
				display: flex;
				gap: 24px;
				align-items: stretch;
			}

			.add-value-cards-container {
				flex: 1;
				display: grid;
				grid-template-columns: repeat(2, 1fr);
				gap: 24px;
			}

.add-value-card {
    padding: 30px;
    transition: box-shadow 0.3s ease;
    border-radius: 12px;
    border: 1px solid var(--Sennovate-stroke, #D7E2ED);
    background: var(--Sennovate-white, #FFF);
}

			.add-value-card:hover {
				box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
			}

.card-icon-wrapper {
    width: 40px;
    height: 40px;
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    justify-content: flex-start;
}

			.card-icon {
				max-width: 100%;
				max-height: 100%;
				object-fit: contain;
			}

.card-title {
    font-family: 'Satoshi-Bold', sans-serif !important;
    font-size: 24px !important;
    font-weight: 700 !important;
    margin: 0 0 18px;
    color: var(--Sennovate-Dark-blue, #051630);
}

			.card-desc {
    margin: 0;
    color: var(--Sennovate-Dark-blue, #051630);
    font-weight: 400;
    line-height: 150% !important;
}

			.add-value-image-wrapper {
				/* flex: 1; */
				display: flex;
				max-width: 488px;
			}

			.add-value-main-image {
				width: 100%;
				height: 100%;
				object-fit: cover;
				border-radius: 16px;
			}

			/* Responsive Design */
			@media (max-width: 991px) {
				.add-value-content-grid {
					flex-direction: column;
				}
				
				.add-value-image-wrapper {
					height: 400px;
				}
				.add-value-image-wrapper{max-width:100%;}
				    .add-value-main-image {
        object-fit: cover;
        object-position: top;
    }
				.add-value-header{
					margin-bottom: 40px;
					}

					.sennovate-add-value-section {
						padding: 60px 30px;
					}
			}

			@media (max-width: 767px) {

				.add-value-cards-container {
					grid-template-columns: 1fr;
				}
				
				.add-value-image-wrapper {
					height: 300px;
				}
			}
		</style>
	</section>
	<?php
}
