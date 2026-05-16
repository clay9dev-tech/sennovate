<?php
namespace Mandy\Skeletor\Blog;
use Related_Articles_Card;
use \WP_Query;
use \Post_Card;

class Template {
	const POST_PER_PAGE = 4;
	public static function setup() {
		add_action('acf/init', [__CLASS__, 'acf_init']);
		add_action('pre_get_posts', function( $query ) {
			if ( ! is_admin() && $query->is_main_query() && $query->is_search() ) {
				$query->set( 'post_type', [ 'post', 'news', 'event', 'acq_resource', 'page' ] );
			}
		});
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

	public static function register_archive_actions(WP_Query $query) {
		if (!is_admin() && $query->is_main_query()) {

			if (
				$query->is_home() ||
				$query->is_category() ||
				$query->is_search() ||
				(function_exists('is_page') && is_page('blog')) ||
				(function_exists('is_page') && is_page('resources')) ||
				$query->is_post_type_archive( 'resource_type' ) ||
				$query->is_tax('resource_type') ||
				( isset( $query->query_vars['post_type'] ) && $query->query_vars['post_type'] === 'acq_resource' ) || 
				( function_exists( 'is_post_type_archive' ) && is_post_type_archive( 'acq_resource' ) )
			) {
				add_action('before_archive_loop', [__CLASS__, 'inject_archive_header']);
				add_action('render_archive_grid_section_title', [__CLASS__, 'render_archive_grid_section_title']);
				add_action('the_archive_post', [__CLASS__, 'archive_item']);
				add_action('render_pagination', [__CLASS__, 'archive_pagination']);
				add_action('after_archive_loop', [__CLASS__, 'inject_archive_footer']);

				if (is_post_type_archive( 'acq_resource' ) || (function_exists('is_page') && is_page('blog')) ) {
					add_action('render_filter', [__CLASS__, 'render_filter']);
				}
			}
		}
	}

	public static function inject_latest_post(): void {
		global $wp_query;

		$queried_object = get_queried_object();
		$selected_current_term = null;

		if (is_object($queried_object) && isset($queried_object->term_id)) {
			$selected_current_term = (int) $queried_object->term_id;
		}

		if (!$selected_current_term) {
			return;
		}

		$archive_latest_title = get_field('archive_latest_title', 'resource_type_' . $selected_current_term);
		$archive_grid_title = get_field('archive_grid_title', 'resource_type_' . $selected_current_term);
		$title = $archive_latest_title;
		
		$args = [
			'post_type'      => get_post_type() ?: 'post',
			'posts_per_page' => 4,
			'orderby'        => 'date',
			'order'          => 'DESC',
			'tax_query'      => [
				[
					'taxonomy' => 'resource_type',
					'field'    => 'term_id', // or 'slug' if $selected_current_term is a slug
					'terms'    => [$selected_current_term],
				],
			],
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

	public static function render_archive_grid_section_title() {
		global $wp_query;

		$queried_object = get_queried_object();
		$selected_current_term = null;

		if (is_object($queried_object) && isset($queried_object->term_id)) {
			$selected_current_term = (int) $queried_object->term_id;
		}

		if (!$selected_current_term) {
			return;
		}

		$archive_latest_title = get_field('archive_latest_title', 'resource_type_' . $selected_current_term);
		$archive_grid_title = get_field('archive_grid_title', 'resource_type_' . $selected_current_term);
		if($archive_grid_title) {
			echo '<h3 class="wp-block-heading archive-latest-post-title">'.$archive_grid_title.'</h3>';
		}	
	}

	public static function inject_archive_header(): void {
		if (!is_category() && !is_search() ) {
			$selected_hero_banner = '';

			if(is_post_type_archive( 'acq_resource' )) {
				$selected_hero_banner = get_option('acq_resource-options_archive_header');
			}

			$queried_object = get_queried_object();
			$selected_current_term = null;

			$latest_posts = false;
			if (is_object($queried_object) && isset($queried_object->term_id)) {
				$selected_current_term = (int) $queried_object->term_id;
				$selected_hero_banner = get_field('archive_header', 'resource_type_' . $selected_current_term);
				$latest_posts = true;
			}

			if ($selected_hero_banner) {
				$post = get_post($selected_hero_banner);
				if ($post && $post->post_type === 'wp_block' ) {
					echo apply_filters('the_content', $post->post_content);
				}
			}

			if($latest_posts) {
				self::inject_latest_post();
			}
		} else {
			get_template_part('parts/blog', 'hero-banner');
		}
	}

	public static function archive_item(): void {
		global $post;
		echo Post_Card::render(['post' => $post]);
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

	public static function register_singular_actions(WP_Query $query): void {

		if (is_admin() || !$query->is_main_query() || !$query->is_singular) {
			return;
		}

		/* standard posts don't show a post type?!? */
		$post_type = $query->get('post_type');


		if ($post_type && $post_type == 'acq_resource') {
			add_action('before_post_content', [__CLASS__, 'inject_post_detail_contents']);
		}

		if (is_page() || $post_type && $post_type !== 'post') {
			return;
		}

		add_action('before_post_content', [__CLASS__, 'inject_post_hero']);
		add_action('after_post_content', [__CLASS__, 'inject_post_footer']);

	}

	public static function inject_post_hero(): void {
		get_template_part('parts/blog', 'detail-hero');
		$class = !has_post_thumbnail() ? ' has-no-thumbnail' : '';
		echo '<div class="post-content' . $class . '">';
		echo '<div class="post-content-container">';
		echo '<div class="blog-sidebar-widgets">';
			echo self::render_inner_nav();
			echo '<div class="share-container"><span class="share-text">Share</span>';
			cher_links();
			echo '</div>';
		echo '</div>';
		echo '<div class="blog-content-wrapper">';
			self::inject_post_detail_contents();
		echo '</div>';
	}

	public static function render_inner_nav() {
		global $post;
		$sidebar_links = [];

		// build our ToC
	
		$data = self::build_anchor_list($post);
		if ($data) {
			// remove any sub-links just want top-level
			$links = array_filter($data, function($item) {
				return !$item['is_sub_link'];
			});

			// re-key the array
			$links = array_values($links);

			/**
			 * massage the contents
			 * to match the desired structure
			 * of the page_sidebar 'stache
			 */
			$sidebar_links = array_map(function($item) {
				return [
					'sidebar_link' => $item,
				];
			}, $links);
		}

		// filter out any empty links
		if ($sidebar_links && is_array($sidebar_links)) {
			$sidebar_links = array_filter($sidebar_links, function($item) {
				// check to make sure there's no ACF weirdness
				// with empty values
				if (!$item || !isset($item['sidebar_link']) || !is_array($item['sidebar_link'])) {
					return false;
				}

				return true;
			});

			//now flatten our array
			$sidebar_links = array_map(function($item) {
				return $item['sidebar_link'];
			}, $sidebar_links);
		}

		error_log(json_encode($sidebar_links));

		// bail if we have nothing
		if (!$sidebar_links) {
			return;
		}
		?>
		<section class="<?php printf('%s__post-inner-nav', Skeletor_Blog_Posts::SLUG); ?> post-inner-nav">
			<nav class="post-inner-nav-menu">
				<p class="h6 post-inner-nav-heading"><?php echo __('In This Article'); ?></p>
				<?php //<div class="post-inner-nav-menu-active-section"></div> ?>
				<span class="post-inner-nav-toggle" aria-role="presentation"></span>
				<ol class="post-inner-nav-menu-items">
					<?php
					foreach ($sidebar_links as $sidebar_link) {
						if (isset($sidebar_link['target']) && !empty($sidebar_link['target'])) {
							$target_attr = ' target="_blank"';
						} else {
							$target_attr = '';
						}
						?>
						<li class="post-inner-nav-menu-item"><a href="<?php echo $sidebar_link['url']; ?>"<?php echo $target_attr; ?> class="post-inner-nav-menu-link"><?php echo $sidebar_link['title']; ?></a></li>
						<?php
					}
					?>
				</ol>
			</nav>
		</section>
	<?php
	}


	public static function render_filter($cpt = 'post') { 
		$selected_current_term = [];

		$queried_object = get_queried_object();
		if (is_object($queried_object) && isset($queried_object->term_id)) {
			$selected_current_term[] = (int) $queried_object->term_id;
		}

		?>
		<div class="post-filter">
			<!-- 🔍 Search Form -->
			<form id="ajax-search-form" class="archive-search-form" method="get">
				<div class="search-wrapper">
					<input
						type="search"
						name="sq"
						placeholder="Search"
						aria-label="Search posts"
						value="<?php echo esc_attr(get_search_query()); ?>"
					/>
					<button type="submit" aria-label="Submit search">
						<span class="search-icon"><img src="<?php echo get_stylesheet_directory_uri() . '/images/search.svg'?>" /></span>
					</button>
				</div>
			</form>

			<!-- ⬇️ Category Filters -->
			<div class="category-filter">
				<button type="button" class="filter-toggle" aria-expanded="false">
				Categories
				<span class="arrow"><img src="<?php echo get_stylesheet_directory_uri() . '/images/chevron-down.svg'?>" /></span>
				</button>

				<ul class="custom-post-categories">
				<?php
				global $resource_ids;

				$args = [
					'taxonomy' => get_post_type() == 'acq_resource' ? 'resource_type' : 'category',
					'exclude' => 1,
					'hide_empty' => true,
				];

				if ( !empty($resource_ids) ) {
					//$args['include'] = $resource_ids;
				}

				$terms = get_terms($args);

				if (!empty($terms) && !is_wp_error($terms)) :
					foreach ($terms as $term) : 
					$is_checked = in_array($term->term_id, $selected_current_term) ? 'checked' : '';
					?>
					<li>
						<label>
						<input class="category-checkbox" 
							type="checkbox" name="category[]" 
							value="<?php echo esc_attr($term->term_id); ?>"
							<?php echo $is_checked; ?>
						>
						<span><?php echo esc_html($term->name); ?></span>
						</label>
					</li>
				<?php endforeach; endif; ?>
				</ul>
			</div>
		</div>
	<?php
	}

	public static function inject_post_detail_contents() : void {
		global $post;

		if (!$post) {
			return; // Exit if no post is available
		}

		?>
		<article <?php post_class('wp-block-group alignwide has-background is-layout-constrained wp-block-group-is-layout-constrained'); ?> style="padding-inline:0;">
            <div class="wp-block-group alignwide entry-content" >
                <?php
				$block_content = apply_filters('the_content', $post->post_content);
				echo $block_content;
				?>
            </div>
        </article>
		<?php
	}

	public static function get_reading_time() : int {
		$content = get_the_content();
		$word_count = str_word_count(strip_tags($content));
		$reading_time = ceil($word_count / 200);
		return $reading_time;
	}

	public static function inject_archive_footer(): void {

		//cta
		global $post;
		$selected_post_id = get_option('custom_blog_archive_end_options_field');
		if ($selected_post_id) {
			$post = get_post($selected_post_id);
			if ($post && $post->post_type === 'wp_block') {
				echo apply_filters('the_content', $post->post_content);
			}
		}
		echo '</div>';
	}

	public static function inject_post_footer(): void {
		echo '</div>';
		echo '<h2 class="wp-block-heading has-text-align-left m-text-align-left has-black-color has-text-color has-link-color related-post-heading">Related Articles</h2>';
		echo Related_Articles_Card::render([]);
		//get_template_part('parts/blog', 'related-blogs');

		//cta
		global $post;
		$selected_post_id = get_option('custom_blog_single_end_options_field');
		if ($selected_post_id) {
			$post = get_post($selected_post_id);
			if ($post && $post->post_type === 'wp_block') {
				echo apply_filters('the_content', $post->post_content);
			}
		}
		echo '</div>';
	}

	public static function get_post_tags(): void {
		$post_tags = get_the_tags();
		if ($post_tags) {
			echo '<div class="post-tags"><h2>Tags:</h2><ul>';
			foreach ($post_tags as $tag) {
				echo '<li>' . esc_html($tag->name) . '</li>';
			}
			echo '</ul></div>';
		}
	}

	public static function get_prev_next_post(): void {
		get_template_part('parts/blog', 'post-navigation');
	}

	public static function get_post_featured_image() {
		$post_id = get_the_ID();
		$featured_img = get_the_post_thumbnail( $post_id, 'large' );
		printf(
			'<div class="banner-image-wrapper has-text-align-center"><div class="image-wrapper">%s</div></div>',
			$featured_img,
		);
	}

	public static function pagination_posts_ajax_handler() {
		$paged   = isset($_POST['page']) ? intval($_POST['page']) : 1;
		$cat_id  = isset($_POST['cat']) ? $_POST['cat'] : 0;
		$search  = isset($_POST['sq']) ? sanitize_text_field($_POST['sq']) : '';
		$post_type  = isset($_POST['post_type']) ? sanitize_text_field($_POST['post_type']) : 'post';
		if($post_type == 'page') {
			$post_type = 'post';
		}
		$args = [
			'post_type' =>$post_type,
			'paged'     => $paged,
		];

		if (!empty($cat_id)) {
			if ($args['post_type'] === 'acq_resource') {
				$args['tax_query'] = [
					[
						'taxonomy' => 'resource_type',
						'field'    => 'term_id',
						'terms'    => (array) $cat_id,
					]
				];
			} else {
				$args['category__in'] = (array) $cat_id;
			}
		}

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
		echo self::render_selected_filter($search, $cat_id);
		echo '<section class="archive-posts">';
		echo $content;
		echo '</section>';
		echo self::archive_pagination($query);
		wp_die(); // Important to terminate
	}

	public static function render_selected_filter($search, $term_ids) {
		$term_names = [];

		// Check both category and resource_type taxonomies
		$taxonomies = ['category', 'resource_type'];

		foreach ((array)$term_ids as $term_id) {
			foreach ($taxonomies as $taxonomy) {
				$term = get_term($term_id, $taxonomy);
				if (!is_wp_error($term) && $term && $term->taxonomy === $taxonomy) {
					$term_names[] = $term->name;
				}
			}
		}

		// Exit if no filters applied
		if (empty($term_names) && empty($search)) return;
		?>

		<div class="selected-filter">
			<ul>
				<?php if (!empty($search)) : ?>
					<li class="filter-chip">Search: <strong><?php echo esc_html($search); ?></strong></li>
				<?php endif; ?>

				<?php foreach ($term_names as $name) : ?>
					<li class="filter-chip"><?php echo esc_html($name); ?></li>
				<?php endforeach; ?>

				<li class="clear-filter" role="button" tabindex="0">
					Clear 
					<img src="<?php echo esc_url(get_stylesheet_directory_uri() . '/images/search-close.svg'); ?>" alt="Clear" />
				</li>
			</ul>
		</div>

		<?php
	}

	public static function build_anchor_list($post = null) {
		$leftside_anchor_nav = '';
		$get_title = self::vtlblog_get_sidebar_title();
		if (isset($get_title[0])) {
			$leftside_anchor_nav = self::make_left_sidebar_nav($get_title[0]);
		}
	}

	public static function vtlblog_get_sidebar_title() {
		$content = get_the_content();
		preg_match_all( '@<h2.*?>(.*?)<\/h2>@', $content, $title );
		return $title;
	}

	public static function make_left_sidebar_nav($titles) {
		$html = '';
		if ($titles) {
			$html = '<span class="anchor-nav-heading">Table of contents</span>';	
			foreach ($titles as $title) {
				$title = strip_tags($title);
				$title = trim($title);
				
				if (empty($title)) {
					continue;
				}

				$slug = sanitize_title($title);
				$slug = self::slugify($slug);

				$html .= '<div class="sidebar-menu-item"><a href="#' . $slug . '" class="anchor-menu-link">' . $title . '</a></div>';
			}
		}
		echo $html;
	}

	public static function slugify($text){
		$text = preg_replace('~[^\pL\d]+~u', '-', $text);
		$text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
		$text = preg_replace('~[^-\w]+~', '', $text);
		$text = trim($text, '-');
		$text = preg_replace('~-+~', '-', $text);
		$text = strtolower($text);

		if (empty($text)) {
		return 'n-a';
		}
		return $text;
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

add_action('after_setup_theme', ['\Mandy\Skeletor\Blog\Template', 'setup']);

