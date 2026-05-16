<?php
/**
 * Register a custom block for Sennovate FAQ Section.
 */

add_action( 'acf/init', 'register_sennovate_faq_section_block', 20 );
function register_sennovate_faq_section_block() {
	if ( function_exists( 'acf_register_block_type' ) ) {
		acf_register_block_type( array(
			'name'            => 'sennovate-faq-section',
			'title'           => 'Sennovate FAQ Section',
			'description'     => 'A section displaying a list of frequently asked questions with a smooth accordion layout.',
			'render_callback' => 'sennovate_render_faq_section_callback',
			'category'        => 'formatting',
			'icon'            => 'editor-help',
			'keywords'        => array( 'faq', 'questions', 'accordion', 'sennovate' ),
			'supports'        => array(
				'align'  => true,
				'anchor' => true,
			),
		) );

		// Register Fields
		acf_add_local_field_group(array(
			'key' => 'group_sennovate_faq_section',
			'title' => 'Sennovate FAQ Section Fields',
			'fields' => array(
				array(
					'key' => 'field_sfq_header_title',
					'label' => 'Section Header Title',
					'name' => 'sfq_header_title',
					'type' => 'text',
					'default_value' => 'Got Questions? We\'ve Got Answers.',
				),
				array(
					'key' => 'field_sfq_bg_color',
					'label' => 'Section Background Color',
					'name' => 'sfq_bg_color',
					'type' => 'color_picker',
					'default_value' => '#FAFCFF',
				),
				array(
					'key' => 'field_sfq_faqs',
					'label' => 'FAQs',
					'name' => 'sfq_faqs',
					'type' => 'repeater',
					'layout' => 'block',
					'button_label' => 'Add Question',
					'sub_fields' => array(
						array(
							'key' => 'field_sfq_question',
							'label' => 'Question',
							'name' => 'question',
							'type' => 'text',
						),
						array(
							'key' => 'field_sfq_answer',
							'label' => 'Answer',
							'name' => 'answer',
							'type' => 'textarea',
							'rows' => 4,
						),
					),
				),
			),
			'location' => array(
				array(
					array(
						'param' => 'block',
						'operator' => '==',
						'value' => 'acf/sennovate-faq-section',
					),
				),
			),
		));
	}
}

/**
 * Render Callback.
 */
function sennovate_render_faq_section_callback( $block, $content = '', $is_preview = false, $post_id = 0 ) {
	$h_title  = get_field( 'sfq_header_title' );
	$bg_color = get_field( 'sfq_bg_color' ) ?: '#FAFCFF';
	$faqs     = get_field( 'sfq_faqs' );

	$block_id   = ! empty( $block['anchor'] ) ? $block['anchor'] : 'sfq-' . uniqid();
	$class_name = 'sfq-section' . ( ! empty( $block['className'] ) ? ' ' . $block['className'] : '' );
	?>

	<div id="<?php echo esc_attr( $block_id ); ?>" class="<?php echo esc_attr( $class_name ); ?> alignfull" style="background-color: <?php echo esc_attr( $bg_color ); ?>;">
		<div class="sfq-container">
			
			<?php if ( $h_title ) : ?>
				<div class="sfq-header">
					<h2 class="sfq-header-title"><?php echo esc_html( $h_title ); ?></h2>
				</div>
			<?php endif; ?>

			<div class="sfq-list">
				<?php if ( $faqs ) : foreach ( $faqs as $index => $faq ) : ?>
					<div class="sfq-item <?php echo $index === 0 ? 'active' : ''; ?>">
						<div class="sfq-question">
							<h3 class="sfq-q-text"><?php echo esc_html( $faq['question'] ); ?></h3>
							<span class="sfq-icon"></span>
						</div>
						<div class="sfq-answer" style="<?php echo $index === 0 ? 'display: block;' : 'display: none;'; ?>">
							<div class="sfq-answer-inner">
								<p><?php echo wp_kses_post( nl2br( $faq['answer'] ) ); ?></p>
							</div>
						</div>
					</div>
				<?php endforeach; endif; ?>
			</div>

		</div>

		<style>
			.sfq-section {
				padding: 80px 30px;
				font-family: 'Inter', sans-serif;
			}
			.sfq-container {
				max-width: 900px;
				margin: 0 auto;
			}
			.sfq-header {
				text-align: center;
				margin-bottom: 50px;
			}
			.sfq-header-title {
				font-family: var(--font-satoshi-bold) !important;
				font-size: clamp(32px, 4vw, 44px) !important;
				color: var(--Sennovate-Dark-blue, #051630) !important;
				margin: 0 !important;
				line-height: 1.3 !important;
				font-weight: 500 !important;
			}

			.sfq-list {
				display: flex;
				flex-direction: column;
				gap: 16px;
			}

			.sfq-item {
				background: #ffffff;
				border: 1px solid var(--Sennovate-stroke, #E3EEFA);
				border-radius: 12px;
				overflow: hidden;
				transition: all 0.3s ease;
			}
			.sfq-item.active {
				box-shadow: 0 10px 30px rgba(0, 111, 227, 0.05);
				border-color: #D7E2ED;
			}

			.sfq-question {
				display: flex;
				justify-content: space-between;
				align-items: center;
				padding: 24px 30px;
				cursor: pointer;
				user-select: none;
			}
			.sfq-q-text {
				font-family: var(--sennovate-medium-font) !important;
				font-size: 20px !important;
				color: var(--Sennovate-Dark-blue, #051630) !important;
				margin: 0 !important;
				font-weight: 500 !important;
				padding-right: 20px;
			}

			/* Icon styling (Plus/Minus) */
			.sfq-icon {
				position: relative;
				width: 32px;
				height: 32px;
				border: 1px solid #E3EEFA;
				border-radius: 50%;
				flex-shrink: 0;
				display: flex;
				justify-content: center;
				align-items: center;
				transition: all 0.3s ease;
			}
			.sfq-icon::before,
			.sfq-icon::after {
				content: '';
				position: absolute;
				background-color: var(--Sennovate-Dark-blue, #051630);
				transition: all 0.3s ease;
			}
			/* Horizontal line */
			.sfq-icon::before {
				width: 14px;
				height: 2px;
			}
			/* Vertical line */
			.sfq-icon::after {
				width: 2px;
				height: 14px;
			}

			/* Active state icon (Minus) */
			.sfq-item.active .sfq-icon::after {
				transform: rotate(90deg);
				opacity: 0;
			}
			.sfq-item.active .sfq-icon {
				border-color: #D7E2ED;
			}

			.sfq-answer {
				/* display is controlled via inline styles/jQuery */
			}
			.sfq-answer-inner {
				padding: 0 30px 30px 30px;
			}
			.sfq-answer-inner p {
				font-size: 16px;
				line-height: 1.6;
				color: var(--Sennovate-Dark-blue, #051630);
				opacity: 0.8;
				margin: 0;
			}

			/* Responsive */
			@media (max-width: 768px) {
				.sfq-section { padding: 60px 20px; }
				.sfq-question { padding: 20px; }
				.sfq-answer-inner { padding: 0 20px 20px 20px; }
				.sfq-q-text { font-size: 18px !important; }
			}
		</style>

		<script>
			if (typeof jQuery !== 'undefined') {
				jQuery(document).ready(function($) {
					const $section = $('#<?php echo esc_js($block_id); ?>');
					if ($section.length === 0) return;

					$section.find('.sfq-question').off('click').on('click', function() {
						const $item = $(this).closest('.sfq-item');
						const $answer = $(this).siblings('.sfq-answer');

						if ($item.hasClass('active')) {
							$item.removeClass('active');
							$answer.slideUp(300);
						} else {
							// Close others
							$section.find('.sfq-item').removeClass('active');
							$section.find('.sfq-answer').slideUp(300);

							// Open current
							$item.addClass('active');
							$answer.slideDown(300);
						}
					});
				});
			} else {
				// Fallback vanilla JS if jQuery is somehow unavailable
				document.addEventListener('DOMContentLoaded', function() {
					const section = document.getElementById('<?php echo esc_js($block_id); ?>');
					if (!section) return;

					const items = section.querySelectorAll('.sfq-item');
					items.forEach(item => {
						const question = item.querySelector('.sfq-question');
						question.addEventListener('click', () => {
							const isActive = item.classList.contains('active');
							const answer = item.querySelector('.sfq-answer');
							
							items.forEach(otherItem => {
								otherItem.classList.remove('active');
								otherItem.querySelector('.sfq-answer').style.display = 'none';
							});

							if (!isActive) {
								item.classList.add('active');
								answer.style.display = 'block';
							}
						});
					});
				});
			}
		</script>
	</div>
	<?php
}
