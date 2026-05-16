<?php
if (! defined('ABSPATH')) {
	exit;
}

/**
 * Search By post id
 * This function is intended to be used as a post-search by id.
 */
if (!class_exists('VTL_Custom_Nav')) {
	class VTL_Custom_Nav {

		/**
		 * Ajax call Custom Nav Search By ID
		 */
		public function __construct() {
			add_action( 'wp_ajax_vtl_search_by_id', [ $this, 'vtl_search_by_ids' ] );
			add_action( 'wp_ajax_nopriv_vtl_search_by_id', [ $this, 'vtl_search_by_ids' ] );
		}

		/**
		 * Return the <li> value searched for by id; otherwise, No results found will be displayed.
		 */
		public function vtl_search_by_ids() {
			global $wpdb;
			$request = $_POST['searchNav'];
			$post_info  = get_post( $request );
			$html = '<li>
				<label class="menu-item-title">
					<input type="checkbox" class="menu-item-checkbox" name="menu-item[-' . $post_info->ID . '][menu-item-object-id]" value="' . $post_info->ID . '">' . $post_info->post_title . '
				</label>
				<input type="hidden" class="menu-item-type" name="menu-item[-' . $post_info->ID . '][menu-item-type]" value="post_type">
				<input type="hidden" class="menu-item-object" name="menu-item[-' . $post_info->ID . '][menu-item-object]" value="' . $post_info->post_type . '">
				<input type="hidden" class="menu-item-title" name="menu-item[-' . $post_info->ID . '][menu-item-title]" value="' . $post_info->post_title . '">
				<input type="hidden" class="menu-item-url" name="menu-item[-' . $post_info->ID . '][menu-item-url]" value="' . get_the_permalink( $post_info->ID ) . '">
			</li>';
			if (is_null($post_info)) {
				echo '<li>No results found.</li>';
			} else {
				echo $html;
			}
			die();
		}

		/**
		 * Inserting a Nav Menu metabox in the top sidebar
		 */
		public function add_nav_menu_meta_boxes() {
			add_meta_box(
				'add-search_by_id_nav_link',
				__('Search By ID'),
				array( $this, 'nav_menu_link'),
				'nav-menus',
				'side',
				'high'
			);
		}

		/**
		 * Section Nav Menu link generation
		 */
		public function nav_menu_link() {
			?>
			<div id="posttype-wl-login" class="posttypediv">
				<div id="tabs-panel-custom-search-login" class="tabs-panel tabs-panel-active">
					<p class="quick-search-wrap">
						<label for="vtl-quick-search-by-id" class="screen-reader-text"><?php _e( 'Search' ); ?></label>
						<input type="search" id="vtl-quick-search-by-id" class="vtl-quick-search" name="vtl-quick-search-by-id" />
						<span class="spinner"></span>
						<input type="hidden" name="action" value="vtl_search_by_id">
						<?php submit_button( __( 'Search' ), 'small vtl-quick-search-submit hide-if-js', 'submit', false, array( 'id' => 'submit-vtl-quick-search-by-id') ); ?>
					</p>
					<ul id ="custom-search-login-checklist" class="categorychecklist form-no-clear"></ul>
				</div>
				<p class="button-controls">
					<span class="add-to-menu">
						<input type="submit" class="button-secondary submit-add-to-menu right" value="Add to Menu" name="add-post-type-menu-item" id="submit-posttype-wl-login">
						<span class="spinner"></span>
					</span>
				</p>
			</div>
			<?php
		}

	}
}
$custom_nav = new VTL_Custom_Nav;
add_action('admin_init', [$custom_nav, 'add_nav_menu_meta_boxes']);
