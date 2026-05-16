<?php
/**
 * Register a custom block for Operating Model Grid.
 */

add_action( 'acf/init', 'register_operating_model_grid_block', 20 );
function register_operating_model_grid_block() {
	if ( function_exists( 'acf_register_block_type' ) ) {
		acf_register_block_type( array(
			'name'            => 'sennovate-operating-model-grid',
			'title'           => 'Sennovate Operating Model Grid',
			'description'     => 'A grid of cards with a special hover effect for operating models.',
			'render_callback' => 'sennovate_render_operating_model_grid_callback',
			'category'        => 'formatting',
			'icon'            => 'grid-view',
			'keywords'        => array( 'grid', 'model', 'operating', 'cards' ),
			'supports'        => array(
				'align'  => true,
				'anchor' => true,
			),
		) );

		// Register Fields
		acf_add_local_field_group(array(
			'key' => 'group_operating_model_grid',
			'title' => 'Operating Model Grid Fields',
			'fields' => array(
				array(
					'key' => 'field_omg_section_title',
					'label' => 'Section Title',
					'name' => 'omg_section_title',
					'type' => 'text',
					'default_value' => 'Your Priorities. Our Operating Model.',
				),
				array(
					'key' => 'field_omg_section_subtitle',
					'label' => 'Section Subtitle',
					'name' => 'omg_section_subtitle',
					'type' => 'text',
					'default_value' => 'How we run security operations and why it pays for itself.',
				),
				array(
					'key' => 'field_omg_cards',
					'label' => 'Cards',
					'name' => 'omg_cards',
					'type' => 'repeater',
					'layout' => 'block',
					'sub_fields' => array(
						array(
							'key' => 'field_omg_card_icon',
							'label' => 'Icon',
							'name' => 'card_icon',
							'type' => 'image',
							'return_format' => 'array',
						),
						array(
							'key' => 'field_omg_card_title',
							'label' => 'Title',
							'name' => 'card_title',
							'type' => 'text',
						),
						array(
							'key' => 'field_omg_card_description',
							'label' => 'Description',
							'name' => 'card_description',
							'type' => 'textarea',
						),
					),
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'block',
						'operator' => '==',
						'value' => 'acf/sennovate-operating-model-grid',
					),
				),
			),
		));
	}
}

/**
 * Render Callback.
 */
function sennovate_render_operating_model_grid_callback( $block, $content = '', $is_preview = false, $post_id = 0 ) {
	$title    = get_field( 'omg_section_title' )    ?: 'Your Priorities. Our Operating Model.';
	$subtitle = get_field( 'omg_section_subtitle' ) ?: 'How we run security operations and why it pays for itself.';
	$cards    = get_field( 'omg_cards' );

	$anchor     = ! empty( $block['anchor'] ) ? 'id="' . esc_attr( $block['anchor'] ) . '" ' : '';
	$class_name = 'omg-section' . ( ! empty( $block['className'] ) ? ' ' . $block['className'] : '' );
	?>

	<div <?php echo $anchor; ?> class="<?php echo esc_attr( $class_name ); ?> alignfull">
		<div class="omg-container">
			<div class="omg-header">
				<h2 class="omg-title"><?php echo esc_html( $title ); ?></h2>
				<p class="omg-subtitle"><?php echo esc_html( $subtitle ); ?></p>
			</div>

			<?php if ( $cards ) : ?>
				<div class="omg-grid">
					<?php foreach ( $cards as $card ) : 
						$icon = $card['card_icon'];
						$c_title = $card['card_title'];
						$c_desc = $card['card_description'];
					?>
						<div class="omg-card">
							<div class="omg-card-inner">
								<div class="omg-card-front">
									<?php if ( $icon ) : ?>
										<div class="omg-card-icon">
											<img src="<?php echo esc_url( $icon['url'] ); ?>" alt="<?php echo esc_attr( $icon['alt'] ); ?>">
										</div>
									<?php endif; ?>
									<h3 class="omg-card-title"><?php echo esc_html( $c_title ); ?></h3>
								</div>
								<div class="omg-card-hover">
									<h3 class="omg-card-title-hover"><?php echo esc_html( $c_title ); ?></h3>
									<p class="omg-card-description"><?php echo esc_html( $c_desc ); ?></p>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>

		<style>
			.omg-section {
				background-color: #006FE3;
				padding: 80px 30px;
				color: #ffffff;
				font-family: 'Inter', sans-serif;
			}
			.omg-container {
				max-width: 1140px;
				margin: 0 auto;
			}
			.omg-header {
				text-align: center;
				margin-bottom: 60px;
			}
			.omg-title {
    font-family: var(--sennovate-medium-font) !important;
    margin-bottom: 15px;
    color: #ffffff;
    margin-top: 0;
    line-height: 1.3 !important;
    font-weight: 500 !important;
}
			.omg-subtitle {
    font-size: 18px !important;
    margin: 0 auto;
    font-weight: 400;
    line-height: 1.5 !important;
}
			.omg-grid {
				display: grid;
				grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
				gap: 20px;
			}
			.omg-card {
				background: #ffffff;
				border-radius: 12px;
				min-height: 280px;
				position: relative;
				overflow: hidden;
				transition: all 0.3s ease;
				cursor: pointer;
			}
			.omg-card-inner {
				padding: 32px;
				height: 100%;
				display: flex;
				flex-direction: column;
				justify-content: space-between;
				position: relative;
			}
			.omg-card-front {
				height: 100%;
				display: flex;
				flex-direction: column;
				justify-content: space-between;
				transition: all 0.4s ease;
			}
			.omg-card-icon {
				width: 50px;
				height: 50px;
				margin-bottom: 20px;
			}
			.omg-card-icon img {
				width: 100%;
				height: 100%;
				object-fit: contain;
			}
			.omg-card-title, .omg-card-title-hover {
				font-family: var(--font-satoshi-bold) !important;
				color: var(--sennovate-dark-blue) !important;
				font-size: 24px !important;
				margin: 0;
				line-height: 1.3 !important;
				font-weight: 700 !important;
			}
			.omg-card-hover {
				position: absolute;
				top: 0;
				left: 0;
				width: 100%;
				height: 100%;
				padding: 30px;
				background: #ffffff;
				display: flex;
				flex-direction: column;
				opacity: 0;
				visibility: hidden;
				transform: translateY(20px);
				transition: all 0.4s ease;
			}
			.omg-card:hover .omg-card-front {
				opacity: 0;
				transform: translateY(-20px);
			}
			.omg-card:hover .omg-card-hover {
				opacity: 1;
				visibility: visible;
				transform: translateY(0);
			}
			.omg-card-title-hover {
				font-family: 'Satoshi-Black', sans-serif;
				color: #051630;
				font-size: 1.2rem;
				margin-bottom: 15px;
			}
			.omg-card-description {
    color: var(--sennovate-dark-blue);
    font-size: 18px !important;
    line-height: 1.4 !important;
    margin: 0;
    font-weight: 400;
}

@media (max-width: 1024px) {
	.omg-grid {
	    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
		gap: 30px;
	}
	.omg-card {
		min-height: auto;
		cursor: default;
	}
	.omg-card-inner {
		padding: 20px;
		display: block;
	}
	.omg-card-front {
		opacity: 1 !important;
		transform: none !important;
		height: auto;
		display: block;
	}
	.omg-card-front .omg-card-title {
		display: none; /* Hide the bottom title on mobile */
	}
	.omg-card-icon {
		margin-bottom: 15px;
	}
	.omg-card-hover {
		position: static;
		padding: 0;
		opacity: 1 !important;
		visibility: visible !important;
		transform: none !important;
		display: block;
		height: auto;
	}
	.omg-card-title-hover {
		margin-top: 0;
		margin-bottom: 12px;
	}
}

			@media (max-width: 768px) {
				.omg-section { padding: 60px 30px; }
				.omg-grid { 
					grid-template-columns: 1fr; 
					max-width: 400px;
					margin: 0 auto;
				}
			}
		</style>
	</div>
	<?php
}
