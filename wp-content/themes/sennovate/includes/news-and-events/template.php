<?php
namespace Mandy\Skeletor\News;
use Related_Articles_Card;
use \WP_Query;
use \Post_Card;

class Template {
	const POST_PER_PAGE = 4;
	public static function setup() {
		add_action('acf/init', [__CLASS__, 'acf_init']);
		add_action('pre_get_posts', [__CLASS__, 'register_singular_actions']);
		add_action('pre_get_posts', [__CLASS__, 'register_archive_actions']);
		add_action('wp_enqueue_scripts', [ __CLASS__, 'enqueue_event_load_more_assets']);
		add_action('wp_ajax_pagination_posts', [__CLASS__, 'pagination_posts_ajax_handler']);
		add_action('wp_ajax_nopriv_pagination_posts', [__CLASS__, 'pagination_posts_ajax_handler']);
	}

	public static function enqueue_event_load_more_assets() {
		$filepath = get_stylesheet_directory() . '/src/scripts/pagination-post.js';
		wp_enqueue_script(
			'pagination-post-param',
			get_stylesheet_directory_uri() . '/src/scripts/pagination-post.js',
			array(),
			filemtime($filepath)
		);
	
		global $wp_query;
		$queried_object = get_queried_object();
		$cat_id = is_category() ? $queried_object->term_id : '';

		if ( ! empty( get_post_type()) ) {
			$post_type = get_post_type();
		} else {
			$post_type = 'post'; // fallback
		}
	
		wp_localize_script('pagination-post-param', 'pagination_post_param', [
			'ajax_url'        => admin_url('admin-ajax.php'),
			'post_type'       => $post_type,
			'posts'           => json_encode($wp_query->query),
			'posts_per_page'  => self::POST_PER_PAGE,
			'cat_id'          => $cat_id,
			'nonce'           => wp_create_nonce('pagination_post_none'),
			'max_pages'       => $wp_query->max_num_pages,
		]);
	}

	public static function register_singular_actions(WP_Query $query): void {

		if (is_admin() || !$query->is_main_query() || !$query->is_singular) {
			return;
		}

		/* standard posts don't show a post type?!? */
		$post_type = $query->get('post_type');
		if ($post_type == 'news') {
			add_action('before_post_content', [__CLASS__, 'inject_post_hero']);
			add_action('before_post_content', [__CLASS__, 'inject_news_detail_contents']);
			add_action('after_post_content', [__CLASS__, 'inject_post_footer']);
		}

		if ($post_type == 'event') {
			add_action('before_post_content', [__CLASS__, 'inject_post_hero']);
			add_action('before_post_content', [__CLASS__, 'inject_event_detail_contents']);
		}
	}

	public static function inject_post_hero(): void {
		?>
		<?php
		global $post, $wp_query;
		$class = !has_post_thumbnail() ? ' has-no-thumbnail' : '';
		$post_id = get_the_ID();
		$author_id = get_the_author_meta('ID');
		$image = $featured_image
            ? SkeletorFeaturedImageFocalPoint::featured_image_block($post, 'medium')
            : '<figure class="size-medium"><img class="size-medium no-thumbnail" src="' . esc_url(get_stylesheet_directory_uri() . '/images/no-thumbnail.png') . '" alt="' . esc_attr(get_the_title($post)) . '" /></figure>';

					
		$cpt = $wp_query->query_vars['post_type']
		?>

		<section class="wp-block-group alignfull detail-banner">
			<div class="wp-block-group alignwide detail-banner-wrap">
				<div class="wp-block-columns are-vertically-aligned-center reverse-stacking-order is-content-justification-space-between detail-banner-columns ">
					<div class="wp-block-column detail-banner-left">
						<div class="blog-hero-heading">
							<?php if ($cpt == 'news'){ ?>
								<div class="post-date"><?php echo get_the_date('j M, Y'); ?></div>
							<?php } ?>
							<h1 class="wp-block-heading blog-title"><?php the_title(); ?></h1>
							<?php if ($cpt == 'event'){ ?>
								<div class="post-date"><?php 
									$start_date = get_the_date('j M, Y');
									if($cpt == 'event' && $get_start = get_field('start_date')){
										$start_date = $get_start;
									}
									echo $start_date;
								?>
								<?php
									if ($cpt == 'event' && $location = get_field('location')){
										echo '<span class="location">'.$location.'</span>';
									}

									if ($cpt == 'event' && $event_link = get_field('event_link')){
										echo '<div class="wp-block-buttons is-layout-flex wp-block-buttons-is-layout-flex">
										<div class="wp-block-button"><a href="'.$event_link.'" class="wp-block-button__link wp-element-button">Register Now</a></div>
										</div>';
									}
								?>
								</div>
							<?php } ?>
						</div>
					</div>
					<div class="wp-block-column detail-banner-right">
						<?php echo $image; ?>
					</div>
				</div>
			</div>
		</section>
		<?php
	}

	public static function inject_news_detail_contents() : void {
		global $post;

		if (!$post) {
			return; // Exit if no post is available
		}

		?>
		<article <?php post_class('wp-block-group alignwide has-background is-layout-constrained wp-block-group-is-layout-constrained'); ?> style="padding-inline:0;">
            <div class="wp-block-group alignwide entry-content">
                <?php
				$block_content = apply_filters('the_content', $post->post_content);
				echo $block_content;
				?>
            </div>
        </article>
		<?php
	}

	public static function inject_event_detail_contents() : void {
		global $post;

		if (!$post) {
			return; // Exit if no post is available
		}

		$block_content = apply_filters('the_content', $post->post_content);
		echo $block_content;

	}

	public static function inject_post_footer(): void {
		echo '</div>';
		echo '<div class="wp-block-group alignwide has-background is-layout-constrained wp-block-group-is-layout-constrained" style="margin-top:var(--wp--preset--spacing--60);margin-bottom:var(--wp--preset--spacing--60);padding:0;">';
		echo '<h2 class="wp-block-heading alignwide has-text-align-left m-text-align-left has-black-color has-text-color has-link-color related-post-heading" style="margin:0;margin-bottom:var(--wp--preset--spacing--60);">Related Articles</h2>';
		echo Related_Articles_Card::render([]);
		echo '</div>';
		//get_template_part('parts/blog', 'related-blogs');

		//cta
		global $post, $wp_query;
		$cpt = $wp_query->query_vars['post_type'] . '-options';
		$selected_post_id = get_option($cpt.'_single_end_cta');
		if ($selected_post_id) {
			$post = get_post($selected_post_id);
			if ($post && $post->post_type === 'wp_block') {
				echo apply_filters('the_content', $post->post_content);
			}
		}
		echo '</div>';
	}

	public static function register_archive_actions(WP_Query $query) {
		
		if (!is_admin() && $query->is_main_query()) {

			if (
				$query->is_home() ||
				$query->is_category() ||
				$query->is_search() ||
				( isset( $query->query_vars['post_type'] ) && $query->query_vars['post_type'] === 'news' ) || 
				( function_exists( 'is_post_type_archive' ) && is_post_type_archive( 'news' ) ) ||
				( isset( $query->query_vars['post_type'] ) && $query->query_vars['post_type'] === 'event' ) || 
				( function_exists( 'is_post_type_archive' ) && is_post_type_archive( 'event' ) )
			) {
				add_action('before_archive_loop', [__CLASS__, 'inject_archive_header']);
				
				if (!is_category() && !is_search()) {
					add_action('before_archive_loop', [__CLASS__, 'inject_latest_post']);
					add_action('before_archive_loop', [__CLASS__, 'inject_grid_title']);
				}
				add_action('the_archive_post', [__CLASS__, 'archive_item']);
				add_action('render_pagination', [__CLASS__, 'archive_pagination']);
			}
		}
	}

	public static function inject_archive_header(): void {
		global $wp_query;
		$cpt = $wp_query->query_vars['post_type'] . '-options';
	
		$selected_hero_banner = get_option($cpt.'_archive_header');

		if (!is_category() && !is_search()) {
			if ($selected_hero_banner) {
				$post = get_post($selected_hero_banner);
				if ($post && $post->post_type === 'wp_block' && get_post_type() != 'acq_resource') {
					echo apply_filters('the_content', $post->post_content);
				}
			}
		}
	}

	public static function inject_grid_title() {
		global $wp_query;

		$cpt = $wp_query->query_vars['post_type'] . '-options';
		$title = get_option( $cpt . '_archive_grid_title' );

		echo '<div class="wp-block-group alignwide " style="padding:0;">
		<h3 class="wp-block-heading archive-latest-post-title">'.$title.'</h3></div>';
	}

	public static function inject_latest_post(): void {
		global $wp_query;
		$cpt = $wp_query->query_vars['post_type'] . '-options';
		$title = get_option($cpt.'_archive_latest_title');
		$args = [
			'post_type'      => get_query_var('post_type') ?: 'post',
			'posts_per_page' => 4,
			'orderby'        => 'date',
			'order'          => 'DESC'
		];

		$latest_posts = new WP_Query($args);

		if ($latest_posts->have_posts()) :
			echo '<div class="wp-block-group alignwide latest-post-event-news-wrap" style="padding:0;">';
			echo '<h3 class="wp-block-heading archive-latest-post-title">'.$title.'</h3>';
			echo '<div class="latest-post-event-news">';
			while ($latest_posts->have_posts()) : $latest_posts->the_post();
				global $post;
				echo Post_Card::render(['post' => $post, 'date_enable' => true]);
			endwhile;
			echo '</div>';
			echo '</div>';
			wp_reset_postdata();
		endif;

	}
	
	public static function archive_item(): void {
		global $post;
		global $wp_query;
		$cpt = $wp_query->query_vars['post_type'];

		if ($cpt == 'news') {
			echo Post_Card::render(['post' => $post, 'type' => 'two-column', 'date_enable' => true]);
		} else {
			echo Post_Card::render(['post' => $post, 'date_enable' => true]);
		}
	}

	public static function archive_pagination(WP_Query $query = null) {
		if (!$query instanceof WP_Query) {
			global $wp_query;
			$query = $wp_query;
		}

		$total_pages  = $query->max_num_pages;
		$current_page = max(1, $query->get('paged'));

		if ($current_page === 0 && isset($_POST['page'])) {
			$current_page = (int) $_POST['page'];
		}

		if ($total_pages <= 1) return;

		// If AJAX request with category
		if (defined('DOING_AJAX') && DOING_AJAX && isset($_POST['cat'])) {
			$cat_id = (int) $_POST['cat'];
			$base = trailingslashit(get_category_link($cat_id)) . 'page/%#%/';
		} else {
			// Normal frontend rendering
			$base = str_replace(999999999, '%#%', esc_url(get_pagenum_link(999999999)));
		}

		$arrow_img_tag = '
		<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
		<path d="M19 12H5" stroke="#FF5811" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
		<path d="M12 19L5 12L12 5" stroke="#FF5811" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
		</svg>';

		$links = paginate_links([
			'base'      => $base,
			'format'    => '', // Required for pretty permalinks
			'current'   => $current_page,
			'total'     => $total_pages,
			'mid_size'  => 2,
			'end_size'  => 1,
			'prev_text' => '<span class="icon">' . $arrow_img_tag . '</span> Previous',
			'next_text' => 'Next <span class="icon">' . $arrow_img_tag . '</span>',
			'type'      => 'array',
		]);

		if (!empty($links)) {
			echo '<div class="wp-block-group alignwide pagination-wrapper-group"><div class="pagination-wrapper">';
			foreach ($links as $link) {
				$class = 'number';
				if (strpos($link, 'prev') !== false) {
					$class = 'prev';
				} elseif (strpos($link, 'next') !== false) {
					$class = 'next';
				}
				echo '<div class="pagination-item ' . esc_attr($class) . '">' . $link . '</div>';
			}
			echo '</div></div>';
		}
	}

	public static function get_prev_next_post(): void {
		get_template_part('parts/blog', 'post-navigation');
	}

	public static function pagination_posts_ajax_handler() {
		$paged   = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$cat_id  = isset($_POST['cat']) ? $_POST['cat'] : 0;
		$search  = isset($_POST['sq']) ? sanitize_text_field($_POST['sq']) : '';
		$post_type  = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : 'post';

		$args = [
			'post_type' =>$post_type,
			'paged'     => $paged,
		];

		if (!empty($search)) {
			$args['s'] = $search;
		}

		$query = new \WP_Query($args);
	
		ob_start(); // Start output buffering
	
		if ($query->have_posts()) {
			while ($query->have_posts()) {
				$query->the_post();
				global $post;
				echo Post_Card::render(['post' => $post]);
			}
			wp_reset_postdata();
		}
	
		$content = ob_get_clean();
		echo '<section class="archive-posts">';
		echo $content;
		echo '</section>';
		echo self::archive_pagination($query);
		wp_die(); // Important to terminate
	}

	public static function acf_init() {
		acf_add_local_field_group([
			'key'    => 'group_blog_custom_field_category_group',
			'title'  => 'Category Settings',
			'fields' => [
				[
					'key'   => 'field_category_thumbnail',
					'name'  => 'category_thumbnail',
					'label' => 'Thumbnail',
					'type'  => 'image',
					'return_format' => 'url', // or 'array' or 'id'
					'preview_size'  => 'thumbnail',
				],
			],
			'location' => [
				[
					[
						'param'    => 'taxonomy',
						'operator' => '==',
						'value'    => 'category',
					],
				],
			],
			'position' => 'side',
		]);
	}
	
}

add_action('after_setup_theme', ['\Mandy\Skeletor\News\Template', 'setup']);
