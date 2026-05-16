<?php
/**
 * Register a custom block for Sennovate Partners Grid.
 */

add_action( 'acf/init', 'register_sennovate_partners_grid_block', 20 );
function register_sennovate_partners_grid_block() {
	if ( function_exists( 'acf_register_block_type' ) ) {
		acf_register_block_type( array(
			'name'            => 'sennovate-partners-grid',
			'title'           => 'Sennovate Partners Grid',
			'description'     => 'A grid of partner logos with a "See All Partners" button.',
			'render_callback' => 'sennovate_render_partners_grid_callback',
			'category'        => 'formatting',
			'icon'            => 'groups',
			'keywords'        => array( 'partners', 'logos', 'grid', 'sennovate' ),
			'supports'        => array(
				'align'  => true,
				'anchor' => true,
			),
		) );

		// Register Fields
		acf_add_local_field_group(array(
			'key' => 'group_sennovate_partners_grid',
			'title' => 'Sennovate Partners Grid Fields',
			'fields' => array(
				array(
					'key' => 'field_spg_title',
					'label' => 'Section Title',
					'name' => 'spg_title',
					'type' => 'text',
					'default_value' => "We Don't Resell. We Operate.",
				),
				array(
					'key' => 'field_spg_subtitle',
					'label' => 'Section Subtitle',
					'name' => 'spg_subtitle',
					'type' => 'text',
					'default_value' => 'Direct partnerships with the platforms we deploy and run in production daily.',
				),
				array(
					'key' => 'field_spg_logos',
					'label' => 'Logos',
					'name' => 'spg_logos',
					'type' => 'repeater',
					'layout' => 'table',
					'sub_fields' => array(
						array(
							'key' => 'field_spg_logo_image',
							'label' => 'Logo Image',
							'name' => 'logo',
							'type' => 'image',
							'return_format' => 'array',
						),
						array(
							'key' => 'field_spg_logo_link',
							'label' => 'Logo Link',
							'name' => 'link',
							'type' => 'url',
						),
					),
				),
				array(
					'key' => 'field_spg_button',
					'label' => 'Button',
					'name' => 'spg_button',
					'type' => 'link',
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'block',
						'operator' => '==',
						'value' => 'acf/sennovate-partners-grid',
					),
				),
			),
		));
	}
}

/**
 * Render Callback.
 */
function sennovate_render_partners_grid_callback( $block, $content = '', $is_preview = false, $post_id = 0 ) {
	$title    = get_field( 'spg_title' ) ?: "We Don't Resell. We Operate.";
	$subtitle = get_field( 'spg_subtitle' ) ?: 'Direct partnerships with the platforms we deploy and run in production daily.';
	$logos    = get_field( 'spg_logos' );
	$button   = get_field( 'spg_button' );

	$anchor     = ! empty( $block['anchor'] ) ? 'id="' . esc_attr( $block['anchor'] ) . '" ' : '';
	$class_name = 'spg-section' . ( ! empty( $block['className'] ) ? ' ' . $block['className'] : '' );
	?>

	<div <?php echo $anchor; ?> class="<?php echo esc_attr( $class_name ); ?>">
		<div class="spg-container">
			<div class="spg-header">
				<h2 class="spg-title"><?php echo esc_html( $title ); ?></h2>
				<p class="spg-subtitle"><?php echo esc_html( $subtitle ); ?></p>
			</div>

			<?php if ( $logos ) : ?>
				<div class="spg-grid">
					<?php foreach ( $logos as $item ) : 
						$logo = $item['logo'];
						$link = $item['link'];
					?>
						<div class="spg-logo-item">
							<?php if ( $link ) : ?>
								<a href="<?php echo esc_url( $link ); ?>" target="_blank" rel="noopener noreferrer">
							<?php endif; ?>
								
								<?php if ( $logo ) : ?>
									<img src="<?php echo esc_url( $logo['url'] ); ?>" alt="<?php echo esc_attr( $logo['alt'] ); ?>">
								<?php endif; ?>

							<?php if ( $link ) : ?>
								</a>
							<?php endif; ?>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<?php if ( $button ) : ?>
				<div class="spg-footer">
					<a href="<?php echo esc_url( $button['url'] ); ?>" class="wp-block-button__link hss__cta" target="<?php echo esc_attr( $button['target'] ); ?>">
						<?php echo esc_html( $button['title'] ); ?>
					</a>
				</div>
			<?php endif; ?>
		</div>

		<style>
			.spg-section {
				padding: 80px 30px;
				background: #fff;
				font-family: 'Inter', sans-serif;
			}

			.spg-header {
				text-align: center;
				margin-bottom: 60px;
			}
			.spg-title {
    font-family: var(--sennovate-medium-font) !important;
    color: #051630;
    margin: 0 0 16px;
    font-weight: 500 !important;
}
			.spg-subtitle {
    font-size: 18px !important;
    color: var(--sennovate-dark-blue);
    line-height: 1.5 !important;
}
			.spg-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    border: 1px solid #D7E2ED;
    overflow: hidden;
    margin-bottom: 60px;
}
			.spg-logo-item {
				display: flex;
				align-items: center;
				justify-content: center;
				padding: 40px 20px;
				background: #fff;
				border-right: 1px solid #E3EEFA;
				transition: all 0.3s ease;
			}
			.spg-logo-item:last-child {
				border-right: none;
			}
			.spg-logo-item a {
				display: flex;
				align-items: center;
				justify-content: center;
				width: 100%;
				height: 100%;
				transition: all 0.3s ease;
			}
			.spg-logo-item:hover {
				background: #fbfdff;
				box-shadow: inset 0 0 20px rgba(0, 111, 227, 0.05);
			}
			.spg-logo-item:hover img {
				transform: scale(1.05);
			}
			.spg-logo-item img {
				max-width: 140px;
				max-height: 50px;
				width: auto;
				height: auto;
				object-fit: contain;
				transition: all 0.3s ease;
			}
			.spg-footer {
				text-align: center;
			}
			.spg-btn {
				background: #006FE3;
				color: #fff;
				padding: 14px 32px;
				border-radius: 8px;
				text-decoration: none;
				font-weight: 600;
				display: inline-block;
				transition: all 0.3s ease;
			}
			.spg-btn:hover {
				background: #0056bc;
				transform: translateY(-2px);
				box-shadow: 0 10px 20px rgba(0, 111, 227, 0.2);
			}
@media(max-width: 1024px){
	.spg-section{padding: 80px 30px;}
}
			@media (max-width: 768px) {
				.spg-grid { grid-template-columns: repeat(2, 1fr); margin-bottom: 40px;}
				.spg-logo-item { border-bottom: 1px solid #E3EEFA; }
				.spg-logo-item:nth-child(even) { border-right: none; }
				.spg-title { font-size: 32px; }
				.spg-section{padding: 60px 30px;}
			}
			@media(max-width: 991px){
				.spg-header{
					margin-bottom: 40px;
				}
			}
		</style>
	</div>
	<?php
}
