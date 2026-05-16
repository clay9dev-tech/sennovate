<?php
/**
 * Register a custom block for Sennovate Values Grid.
 */

add_action( 'acf/init', 'register_sennovate_values_grid_block', 20 );
function register_sennovate_values_grid_block() {
	if ( function_exists( 'acf_register_block_type' ) ) {
		acf_register_block_type( array(
			'name'            => 'sennovate-values-grid',
			'title'           => 'Sennovate Values Grid',
			'description'     => 'A 3-column grid of cards with images, titles, and descriptions.',
			'render_callback' => 'sennovate_render_values_grid_callback',
			'category'        => 'formatting',
			'icon'            => 'grid-view',
			'keywords'        => array( 'values', 'grid', 'cards', 'sennovate' ),
			'supports'        => array(
				'align'  => true,
				'anchor' => true,
			),
		) );

		// Register Fields
		acf_add_local_field_group(array(
			'key' => 'group_sennovate_values_grid',
			'title' => 'Sennovate Values Grid Fields',
			'fields' => array(
				array(
					'key' => 'field_svg_title',
					'label' => 'Section Title',
					'name' => 'svg_title',
					'type' => 'text',
					'default_value' => "We Don't Compete on Products. We Win on Ownership.",
				),
				array(
					'key' => 'field_svg_subtitle',
					'label' => 'Section Subtitle',
					'name' => 'svg_subtitle',
					'type' => 'text',
					'default_value' => 'Why security leaders choose Sennovate.',
				),
				array(
					'key' => 'field_svg_cards',
					'label' => 'Cards',
					'name' => 'svg_cards',
					'type' => 'repeater',
					'layout' => 'block',
					'sub_fields' => array(
						array(
							'key' => 'field_svg_card_image',
							'label' => 'Card Image',
							'name' => 'image',
							'type' => 'image',
							'return_format' => 'array',
						),
						array(
							'key' => 'field_svg_card_title',
							'label' => 'Card Title',
							'name' => 'title',
							'type' => 'text',
						),
						array(
							'key' => 'field_svg_card_desc',
							'label' => 'Card Description',
							'name' => 'description',
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
						'value' => 'acf/sennovate-values-grid',
					),
				),
			),
		));
	}
}

/**
 * Render Callback.
 */
function sennovate_render_values_grid_callback( $block, $content = '', $is_preview = false, $post_id = 0 ) {
	$title    = get_field( 'svg_title' ) ?: "We Don't Compete on Products. We Win on Ownership.";
	$subtitle = get_field( 'svg_subtitle' ) ?: 'Why security leaders choose Sennovate.';
	$cards    = get_field( 'svg_cards' );

	$anchor     = ! empty( $block['anchor'] ) ? 'id="' . esc_attr( $block['anchor'] ) . '" ' : '';
	$class_name = 'svg-section' . ( ! empty( $block['className'] ) ? ' ' . $block['className'] : '' );
	?>

	<div <?php echo $anchor; ?> class="<?php echo esc_attr( $class_name ); ?> alignfull">
		<div class="svg-container">
			<div class="svg-header">
				<h2 class="svg-title"><?php echo esc_html( $title ); ?></h2>
				<p class="svg-subtitle"><?php echo esc_html( $subtitle ); ?></p>
			</div>

			<?php if ( $cards ) : ?>
				<div class="svg-grid">
					<?php foreach ( $cards as $card ) : 
						$image = $card['image'];
						$c_title = $card['title'];
						$c_desc = $card['description'];
					?>
						<div class="svg-card">
							<div class="svg-card-inner">
								<div class="svg-card-image">
									<?php if ( $image ) : ?>
										<img src="<?php echo esc_url( $image['url'] ); ?>" alt="<?php echo esc_attr( $image['alt'] ); ?>">
									<?php endif; ?>
								</div>
								<div class="svg-card-content">
									<h3 class="svg-card-title"><?php echo esc_html( $c_title ); ?></h3>
									<p class="svg-card-desc"><?php echo esc_html( $c_desc ); ?></p>
								</div>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		</div>

		<style>
			.svg-section {
				background: var(--Sennovate-blue, #006FE3);
				padding: 80px 30px;
				color: #ffffff;
				font-family: 'Inter', sans-serif;
			}
			.svg-container {
				max-width: 1140px;
				margin: 0 auto;
			}
			.svg-header {
				text-align: center;
				margin-bottom: 60px;
			}
.svg-title {
    font-family: var(--sennovate-medium-font) !important;
    color: #ffffff;
    margin-bottom: 15px;
    max-width: 699px;
    margin-left: auto;
    margin-right: auto;
    margin-top: 0;
    font-weight: 500 !important;
}

			.svg-card-title {
    font-family: var(--font-satoshi-bold) !important;
    font-size: 24px !important;
    color: var(--sennovate-dark-blue);
        margin: 0 0 24px;
    line-height: 1.4 !important;
}
			.svg-subtitle {
    font-weight: 400;
    line-height: 1.4 !important;
    margin-bottom: 0;
    margin-top: 16px;
}
			.svg-grid {
				display: grid;
				grid-template-columns: repeat(3, 1fr);
				gap: 40px;
			}
			.svg-card {
    overflow: hidden;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    display: flex;
    flex-direction: column;
    border-radius: 12px;
    background: var(--Sennovate-white, #FFF);
}

.svg-card-content {
    padding: 32px;
}

card:hover {
					transform: translateY(-10px);
				box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
			}
			.svg-card-inner {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}
			.svg-card-image {
				height: 200px;
				display: flex;
				align-items: center;
				justify-content: center;
			}
			.svg-card-image img {
				max-width: 100%;
				max-height: 100%;
				object-fit: contain;
			}
			.svg-card-title {
				font-family: 'Satoshi-Black', sans-serif;
				font-size: 24px;
				color: #051630;
				margin-bottom: 12px;
				line-height: 1.3;
			}
			.svg-card-desc {
    color: var(--sennovate-dark-blue);
    margin: 0;
    font-weight: 400;
    line-height: 1.4 !important;
}

			@media (max-width: 1024px) {
				.svg-grid { grid-template-columns: repeat(2, 1fr); }
				.svg-section{padding: 80px 30px;}
			}
			@media(max-width:991px){
				.svg-grid{gap: 20px;}
			}
			@media (max-width: 768px) {
				.svg-section { padding: 60px 30px; }
				.svg-grid { grid-template-columns: 1fr; max-width: 450px; margin: 0 auto; }
				.svg-title { font-size: 32px; }
			}
		</style>
	</div>
	<?php
}
