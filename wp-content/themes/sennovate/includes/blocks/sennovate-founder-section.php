<?php
/**
 * Register a custom block for Sennovate Founder Section.
 */

add_action( 'acf/init', 'register_sennovate_founder_section_block', 20 );
function register_sennovate_founder_section_block() {
	if ( function_exists( 'acf_register_block_type' ) ) {
		acf_register_block_type( array(
			'name'            => 'sennovate-founder-section',
			'title'           => 'Sennovate Founder Section',
			'description'     => 'A section highlighting stats, founder image, and a quote.',
			'render_callback' => 'sennovate_render_founder_section_callback',
			'category'        => 'formatting',
			'icon'            => 'admin-users',
			'keywords'        => array( 'founder', 'stats', 'quote', 'sennovate' ),
			'supports'        => array(
				'align'  => true,
				'anchor' => true,
			),
		) );

		// Register Fields
		acf_add_local_field_group(array(
			'key' => 'group_sennovate_founder_section',
			'title' => 'Sennovate Founder Section Fields',
			'fields' => array(
				// Stats Column
				array(
					'key' => 'field_sfs_stat1_num',
					'label' => 'Stat 1 Number',
					'name' => 'sfs_stat1_num',
					'type' => 'text',
					'default_value' => '15+',
					'wrapper' => array('width' => '50%'),
				),
				array(
					'key' => 'field_sfs_stat1_label',
					'label' => 'Stat 1 Label',
					'name' => 'sfs_stat1_label',
					'type' => 'text',
					'default_value' => 'Years In Cybersecurity',
					'wrapper' => array('width' => '50%'),
				),
				array(
					'key' => 'field_sfs_stat1_bg',
					'label' => 'Stat 1 Background Color',
					'name' => 'sfs_stat1_bg',
					'type' => 'color_picker',
					'default_value' => '#EDF7F2',
				),
				array(
					'key' => 'field_sfs_stat2_num',
					'label' => 'Stat 2 Number',
					'name' => 'sfs_stat2_num',
					'type' => 'text',
					'default_value' => '200+',
					'wrapper' => array('width' => '50%'),
				),
				array(
					'key' => 'field_sfs_stat2_label',
					'label' => 'Stat 2 Label',
					'name' => 'sfs_stat2_label',
					'type' => 'text',
					'default_value' => 'Clients Served',
					'wrapper' => array('width' => '50%'),
				),
				array(
					'key' => 'field_sfs_stat2_bg',
					'label' => 'Stat 2 Background Color',
					'name' => 'sfs_stat2_bg',
					'type' => 'color_picker',
					'default_value' => '#F2F0FF',
				),
				// Founder Image
				array(
					'key' => 'field_sfs_founder_image',
					'label' => 'Founder Image',
					'name' => 'sfs_founder_image',
					'type' => 'image',
					'return_format' => 'array',
				),
				// Quote Column
				array(
					'key' => 'field_sfs_quote',
					'label' => 'Quote',
					'name' => 'sfs_quote',
					'type' => 'textarea',
					'default_value' => '“I built Sennovate after seeing mid-market companies overcharged, understaffed, and ignored by big firms”',
				),
				array(
					'key' => 'field_sfs_quote_desc',
					'label' => 'Quote Description',
					'name' => 'sfs_quote_desc',
					'type' => 'textarea',
					'default_value' => 'Every engagement still starts with a conversation with leadership. Decisions happen inhours, not weeks.',
				),
				array(
					'key' => 'field_sfs_founder_name',
					'label' => 'Founder Name',
					'name' => 'sfs_founder_name',
					'type' => 'text',
					'default_value' => 'Senthil Palaniappan',
				),
				array(
					'key' => 'field_sfs_founder_title',
					'label' => 'Founder Title',
					'name' => 'sfs_founder_title',
					'type' => 'text',
					'default_value' => 'Founder & CEO, Sennovate',
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'block',
						'operator' => '==',
						'value' => 'acf/sennovate-founder-section',
					),
				),
			),
		));
	}
}

/**
 * Render Callback.
 */
function sennovate_render_founder_section_callback( $block, $content = '', $is_preview = false, $post_id = 0 ) {
	$s1_num = get_field( 'sfs_stat1_num' ) ?: '15+';
	$s1_lbl = get_field( 'sfs_stat1_label' ) ?: 'Years In Cybersecurity';
	$s1_bg  = get_field( 'sfs_stat1_bg' ) ?: '#EDF7F2';

	$s2_num = get_field( 'sfs_stat2_num' ) ?: '200+';
	$s2_lbl = get_field( 'sfs_stat2_label' ) ?: 'Clients Served';
	$s2_bg  = get_field( 'sfs_stat2_bg' ) ?: '#F2F0FF';

	$img    = get_field( 'sfs_founder_image' );
	$quote  = get_field( 'sfs_quote' );
	$desc   = get_field( 'sfs_quote_desc' );
	$name   = get_field( 'sfs_founder_name' );
	$title  = get_field( 'sfs_founder_title' );

	$anchor     = ! empty( $block['anchor'] ) ? 'id="' . esc_attr( $block['anchor'] ) . '" ' : '';
	$class_name = 'sfs-section' . ( ! empty( $block['className'] ) ? ' ' . $block['className'] : '' );
	?>

	<div <?php echo $anchor; ?> class="<?php echo esc_attr( $class_name ); ?>">
		<div class="sfs-container">
			<div class="sfs-grid">
				
				<!-- Column 1: Stats -->
				<div class="sfs-col sfs-stats">
					<div class="sfs-stat-card" style="background-color: <?php echo esc_attr($s1_bg); ?>;">
						<div class="sfs-stat-num"><?php echo esc_html($s1_num); ?></div>
						<div class="sfs-stat-label"><?php echo esc_html($s1_lbl); ?></div>
					</div>
					<div class="sfs-stat-card" style="background-color: <?php echo esc_attr($s2_bg); ?>;">
						<div class="sfs-stat-num"><?php echo esc_html($s2_num); ?></div>
						<div class="sfs-stat-label"><?php echo esc_html($s2_lbl); ?></div>
					</div>
				</div>

				<!-- Column 2: Founder Image -->
				<div class="sfs-col sfs-image">
					<?php if ( $img ) : ?>
						<img src="<?php echo esc_url($img['url']); ?>" alt="<?php echo esc_attr($img['alt']); ?>">
					<?php endif; ?>
				</div>

				<!-- Column 3: Quote Card -->
				<div class="sfs-col sfs-quote">
					<div class="sfs-quote-card">
						<h3 class="sfs-quote-text"><?php echo esc_html($quote); ?></h3>
						<p class="sfs-quote-desc"><?php echo esc_html($desc); ?></p>
						<div class="sfs-quote-divider"></div>
						<div class="sfs-quote-footer">
							<div class="sfs-founder-name"><?php echo esc_html($name); ?></div>
							<div class="sfs-founder-title"><?php echo esc_html($title); ?></div>
						</div>
					</div>
				</div>

			</div>
		</div>

		<style>
			.sfs-section {
				padding: 80px 30px;
			}

			.sfs-grid {
				display: grid;
				grid-template-columns: 225px 1fr 1fr;
				gap: 24px;
				align-items: stretch;
			}
			.sfs-stats {
				display: flex;
				flex-direction: column;
				gap: 20px;
			}

			.sfs-col.sfs-image {
    max-height: 450px;
}
			.sfs-stat-card {
				padding: 40px 32px;
    border-radius: 12px;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    justify-content: flex-end;
			}
			.sfs-stat-num {
    font-family: var(--font-satoshi-bold);
    font-size: 48px;
    color: var(--sennovate-dark-blue);
    line-height: 1.4;
    margin-bottom: 4px;
    font-weight: 700;
}
			.sfs-stat-label {
    font-size: 16px;
    color: var(--sennovate-dark-blue);
    font-weight: 500;
    line-height: 1.4;
    font-family: var(--sennovate-medium-font);
}
			.sfs-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 8px;
}
			.sfs-quote-card {
				padding: 32px;
    height: 100%;
    display: flex;
    flex-direction: column;
    justify-content: flex-start;
    border-radius: 12px;
    border: 1px solid var(--Sennovate-stroke, #D7E2ED);
			}

			.sfs-quote-text {
    font-family: 'Satoshi-Medium', sans-serif !important;
    font-size: 28px !important;
    color: #051630;
    line-height: 1.25 !important;
    margin: 0;
    font-weight: 500 !important;
}
			.sfs-quote-desc {
    color: var(--sennovate-dark-blue);
    line-height: 1.4 !important;
    margin-bottom: 0;
    margin-top: 32px;
}
			.sfs-quote-divider {
				height: 1px;
				background: #D7E2ED;
				margin: 40px 0;
			}
			.sfs-founder-name {
    font-family: var(--sennovate-medium-font);
    font-size: 22px;
    color: var(--sennovate-dark-blue);
    margin-bottom: 5px;
    font-weight: 500;
}
			.sfs-founder-title {
    font-size: 16px;
    color: var(--sennovate-dark-blue);
    font-weight: 400;
    line-height: 1.4;
}

@media(max-width: 1024px){
	.sfs-section{
		padding: 80px 30px;
	}
}

			@media (max-width: 1100px) {
				.sfs-grid {
					grid-template-columns: 1fr 1fr;
				}
				.sfs-stats {
					flex-direction: row;
					grid-column: span 2;
				}
				.sfs-stat-card {
					width: 50%;
				}
			}

			@media (max-width: 991px) {
				.sfs-quote-divider{
					    margin: 28px 0;
				}
			}
			@media (max-width: 768px) {
				.sfs-grid {
					grid-template-columns: 1fr;
				}
				.sfs-stats {
					flex-direction: column;
					grid-column: span 1;
				}
				.sfs-stat-card {
					width: 100%;
				}
				.sfs-quote-card {
					padding: 30px;
				}
				.sfs-quote-text {
					font-size: 24px;
				}
			}
			@media(max-width: 767px){
	.sfs-section{
		padding: 60px 30px;
	}
	.sfs-stat-num{font-size: 32px;}
	.sfs-quote-text {
    font-size: 20px !important;
	}
}

		</style>
	</div>
	<?php
}
