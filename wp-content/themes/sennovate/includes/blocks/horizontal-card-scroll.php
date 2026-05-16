<?php
/**
 * Register a custom block for Horizontal Card Scroll.
 *
 * @package Sennovate
 */

add_action( 'acf/init', 'register_horizontal_scroll_block', 20 );
function register_horizontal_scroll_block() {
	if ( function_exists( 'acf_register_block_type' ) ) {
		acf_register_block_type( array(
			'name'            => 'horizontal-card-scroll',
			'title'           => 'Horizontal Card Scroll',
			'description'     => 'A pinned horizontal scroll section for cards.',
			'render_callback' => 'sennovate_render_horizontal_card_scroll_callback',
			'category'        => 'formatting',
			'icon'            => 'slides',
			'keywords'        => array( 'cards', 'horizontal', 'scroll', 'sticky' ),
			'supports'        => array(
				'align'  => true,
				'anchor' => true,
			),
		) );
	}
}

/**
 * Render Callback.
 */
function sennovate_render_horizontal_card_scroll_callback( $block, $content = '', $is_preview = false, $post_id = 0 ) {

	$title    = get_field( 'scroll_section_title' )    ?: "What You're Up Against";
	$subtitle = get_field( 'scroll_section_subtitle' ) ?: 'From detection to response, we secure your entire digital ecosystem with precision and speed.';
	$cards    = get_field( 'scroll_cards' );
	$cta      = get_field( 'scroll_cta_button' );

	$anchor     = ! empty( $block['anchor'] ) ? 'id="' . esc_attr( $block['anchor'] ) . '" ' : '';
	$class_name = 'hss' . ( ! empty( $block['className'] ) ? ' ' . $block['className'] : '' );
	?>

	<section <?php echo $anchor; ?>class="<?php echo esc_attr( $class_name ); ?>">

		<?php /* .hss__panel is what GSAP pins on desktop */ ?>
		<div class="hss__panel">

			<div class="hss__header">
				<div class="container">
					<?php if ( $title ) : ?>
						<h2 class="hss__title"><?php echo esc_html( $title ); ?></h2>
					<?php endif; ?>
					<?php if ( $subtitle ) : ?>
						<p class="hss__subtitle"><?php echo esc_html( $subtitle ); ?></p>
					<?php endif; ?>
				</div>
			</div>

			<div class="hss__track-wrap">
				<?php if ( $cards ) : ?>
					<div class="hss__track">
						<?php foreach ( $cards as $card ) :
							$icon       = $card['card_icon'];
							$card_title = $card['card_title'];
							$card_desc  = $card['card_description'];
						?>
							<div class="hss__card">
								<div class="hss__card-inner">
									<?php if ( $icon ) : ?>
										<div class="hss__card-icon">
											<img src="<?php echo esc_url( $icon['url'] ); ?>"
											     alt="<?php echo esc_attr( $icon['alt'] ); ?>"
											     loading="lazy">
										</div>
									<?php endif; ?>
									<?php if ( $card_title ) : ?>
										<h3 class="hss__card-title"><?php echo esc_html( $card_title ); ?></h3>
									<?php endif; ?>
									<?php if ( $card_desc ) : ?>
										<p class="hss__card-desc"><?php echo esc_html( $card_desc ); ?></p>
									<?php endif; ?>
								</div>
							</div>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>

			<?php if ( $cta ) : ?>
				<div class="hss__footer">
					<div class="container">
						<a href="<?php echo esc_url( $cta['url'] ); ?>"
						   class="wp-block-button__link hss__cta"
						   target="<?php echo esc_attr( $cta['target'] ); ?>">
							<?php echo esc_html( $cta['title'] ); ?>
						</a>
					</div>
				</div>
			<?php endif; ?>

		</div><!-- /.hss__panel -->
	</section><!-- /.hss -->

	<style>
	/* ─────────────────────────────────────────────────────────────
	   Horizontal Scroll Section  (GSAP ScrollTrigger)
	   ───────────────────────────────────────────────────────────── */

	:root {
		--hss-nav  : 80px;   /* fixed header height                   */
		--hss-gap  : 24px;   /* card gap — desktop                    */
		--hss-off  : max(24px, calc((100vw - 1180px) / 2));
		/*
		 * --hss-off mirrors contentSize (1180px) from theme.json.
		 * At 1440px viewport → (1440−1180)/2 = 130px left offset.
		 */
	}

	/* Section wrapper — let GSAP control its own height */
	.hss {
		 position: relative;
	width: 100vw !important;
	max-width: 100vw !important;
	margin-left: 0 !important;
	margin-right: 0 !important;
	left: calc(-1 * (100vw - 100%) / 2) !important;
	box-sizing: border-box;
	}

	/* ── Pinned panel ────────────────────────────────────────────── */
	.hss__panel {
  display: flex;
  flex-direction: column;
  justify-content: flex-start;
  min-height: 0;
  padding: 40px 30px 80px;
  box-sizing: border-box;
  width: 100%;        /* ← changed from 100vw */
  position: relative;
  left: auto;         /* ← removed left: 50% */
  margin-left: 0; 
	}

	/* ── Header ────────────────────────────────────────────────── */
	.hss__header {
		text-align    : center;
		margin-bottom : 32px; /* Reduced from 48px */
		flex-shrink   : 0;
	}
.hss__title {
    color: var(--sennovate-dark-blue);
    line-height: 1.3 !important;
    margin: 0 0 12px;
    font-family: var(--Satoshi-Medium) !important;
    font-weight: 500 !important;
}

	.hss__subtitle {
    font-size: 18px !important;
    color: var(--sennovate-dark-blue);
    margin: 0 auto;
    line-height: 1.4 !important;
}

	/* ── Track wrapper ───────────────────────────────────────────── */

	.hss__track-wrap {
		display      : flex;
		align-items  : flex-start;
		overflow     : hidden;
		margin-top   : 20px;
		margin-bottom: 0;
	}

	/* ── Scrolling row ─────────────────────────────────────────── */
	.hss__track {
		display      : flex;
		align-items  : stretch;
		gap          : var(--hss-gap);
		width        : max-content;
		padding      : 10px 0 10px var(--hss-off); /* Indent first card to match container, 0 right padding */
		flex-shrink  : 0;
	}

	.hss__card {
		flex-shrink : 0;
		width       : 351px; /* Restored to design size */
		min-width   : 320px;
		height      : auto;
		min-height  : 280px;
	}
.hss__card-inner {
    background: #fff;
    border: 1px solid #d7e2ed;
    border-radius: 12px;
    padding: 24px;
    height: 100%;
    display: flex;
    flex-direction: column;
    transition: all .3s ease;
}
	.hss__card-inner:hover {
		transform  : translateY(-6px);
		box-shadow : 0 12px 32px rgba(0,0,0,.10);
		border-color: #006ceb;
	}
.hss__card-icon {
    width: 40px;
    height: 40px;
    margin-bottom: 20px;
    flex-shrink: 0;
    border: 1px solid #E3EEFA;
    border-radius: 6px;
    display: flex;
    justify-content: center;
    align-items: center;
}
	.hss__card-icon img { width:24px; height:24px; object-fit:contain; display:block; }
.hss__card-title {
    font-size: 24px !important;
    color: var(--sennovate-dark-blue);
    line-height: 1.4 !important;
    margin: 0 0 14px;
    font-family: var(--font-satoshi-bold) !important;
    font-weight: 700 !important;
}
.hss__card-desc {
    font-size: 1rem;
    color: #505c6e;
    line-height: 1.4;
    margin: 0;
}

	/* ── Footer CTA ────────────────────────────────────────────── */
	.hss__footer {
		text-align : center;
		margin-top : 60px; /* Further reduced from 32px */
		flex-shrink: 0;
		position   : relative;
		z-index    : 20; /* Ensure it stays above the track and pin-spacer */
	}
	.hss__cta {
		background     : #006ceb;
		color          : #fff !important;
		padding        : 14px 32px;
		border-radius  : 8px;
		font-weight    : 600;
		text-decoration: none;
		display        : inline-block;
		transition     : all .25s ease;
		position       : relative;
		pointer-events : auto !important; /* Force clickability */
	}
	.hss__cta:hover { 
		background: #0056bc; 
		transform: translateY(-2px);
	}


	/* ── Mobile & iPad ≤ 1024 px ────────────────────────────── */
	@media (max-width: 1024px) {
		:root { --hss-nav: 0; }

		.hss {
			height: auto !important;
			padding: 0 !important;
			margin: 0 !important;
		}

		.hss__panel {
			height     : auto !important;
			min-height : 0 !important;
			display    : block !important; /* Stop flex centering on mobile */
		}

		.hss__track-wrap {
			display    : block !important;
			overflow-x : auto !important;
			overflow-y : hidden !important;
			padding    : 0 20px !important;
			margin     : 20px 0 !important;
			width      : 100% !important;
		}

		.hss__track {
			display      : flex !important;
			gap          : 16px !important;
			width        : max-content !important;
			padding      : 10px 20px 10px 0 !important;
		}

		.hss__card {
			width      : 300px !important;
			min-height : 260px !important;
			height     : auto !important;
		}

		.hss__header { margin-bottom: 24px !important; }
		.hss__footer { margin-top: 24px !important; }

		/* Responsive Typography */
		.hss__title {
			font-size: 32px !important;
			line-height: 1.2 !important;
		}
		.hss__subtitle {
			font-size: 16px !important;
		}
		.hss__card-title {
			font-size: 20px !important;
		}
		.hss__card-desc {
			font-size: 14px !important;
		}

		/* Even smaller for phones */
		@media (max-width: 480px) {
			.hss__title { font-size: 26px !important; }
			.hss__subtitle { font-size: 14px !important; }
		}
	}
	@media(max-width: 767px){
			.hss__panel{padding:20px 30px 60px !important}
	}
	</style>

	<script>
	(function () {
		var CDN = 'https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.5/';

		function loadScript(src, cb) {
			var existing = document.querySelector('script[src="' + src + '"]');
			if (existing) {
				cb();
				return;
			}
			var s = document.createElement('script');
			s.src = src;
			s.async = false;
			s.onload = cb;
			document.head.appendChild(s);
		}

		function initSection(section) {
			if (section.dataset.hssInit) return;
			
			var track = section.querySelector('.hss__track');
			var panel = section.querySelector('.hss__panel');
			if (!track || !panel) return;

			section.dataset.hssInit = 'true';

			function getScrollAmount() {
				// We measure the full internal width of the track
				var trackWidth = track.scrollWidth;
				var wrapperWidth = track.parentElement.offsetWidth;
				
				// Total distance needed to move the last card fully into view.
				var amount = -(trackWidth - wrapperWidth);
				return amount;
			}

			// Ensure starting position is clean
			gsap.set(track, { x: 0 });

			// Only initialize if the track is actually wider than the screen
			var amount = getScrollAmount();
			if (amount >= 0) {
				// Center the cards if they all fit on the screen
				section.querySelector('.hss__track-wrap').style.justifyContent = 'center';
				console.log('HSS: Content fits, centering instead of scrolling.');
				return;
			}

			section.dataset.hssInit = 'true';

			gsap.to(track, {
				x: getScrollAmount,
				ease: 'none',
				scrollTrigger: {
					trigger: section,
					start: 'top 80px',
					end: function() { 
						return '+=' + Math.abs(getScrollAmount()); 
					},
					pin: panel,
					scrub: 1,
					invalidateOnRefresh: true,
					anticipatePin: 1
				}
			});
		}

		function boot() {
			loadScript(CDN + 'gsap.min.js', function () {
				loadScript(CDN + 'ScrollTrigger.min.js', function() {
					if (!window.gsap || !window.ScrollTrigger) return;
					
					gsap.registerPlugin(ScrollTrigger);
					
					var mm = gsap.matchMedia();
					var sections = document.querySelectorAll('.hss');

					mm.add("(min-width: 1025px)", function() {
						sections.forEach(initSection);
						return function() {
							// Cleanup when leaving desktop
							ScrollTrigger.getAll().forEach(st => st.kill());
						};
					});

					mm.add("(max-width: 1024px)", function() {
						// On mobile/iPad, ensure no GSAP styles remain
						gsap.set(".hss__track", { clearProps: "all" });
					});
				});
			});
		}

		if (document.readyState === 'complete') {
			boot();
		} else {
			window.addEventListener('load', boot);
		}
		
		if (window.acf) {
			window.acf.addAction('render_block_preview/type=horizontal-card-scroll', boot);
		}
	})();
	</script>

	<?php
}
