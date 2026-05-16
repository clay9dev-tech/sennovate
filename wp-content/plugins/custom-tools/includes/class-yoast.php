<?php
if (! defined('ABSPATH')) {
	exit;
}

/**
 * Yoast SEO customizations.
 *
 * Several nag/advertisement cleanup functions sourced from "Hide SEO Bloat" plugin.
 * https://wordpress.org/plugins/so-clean-up-wp-seo/
 *
 * @since 1.0.0
 */
class Mandy_Yoast {

	/**
	 * Sets up the class functionality.
	 *
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	public function __construct() {
		add_action('admin_bar_menu', [$this, 'remove_adminbar_settings'], 999);
		add_action('wp_dashboard_setup', [$this, 'remove_dashboard_widget']);
		add_action('admin_init', [$this, 'remove_class_hook']);
		add_action('admin_init', [$this, 'remove_seo_scores_dropdown_filters'], 20);
		add_action('admin_head', [$this, 'hide_visibility_css']);
	}

	/**
	 * Removes settings submenu in admin bar.
	 *
	 * @access public
	 * @since  2.0.2
	 * @return void
	 */
	public function remove_adminbar_settings() {
		global $wp_admin_bar;
		$nodes = array_keys($wp_admin_bar->get_nodes());
		foreach ($nodes as $node) {
			if (false !== strpos($node, 'wpseo')) {
				$wp_admin_bar->remove_node($node);
			}
		}
	}

	/**
	 * Removes dashboard widget.
	 *
	 * @access public
	 * @since  2.0.2
	 * @return void
	 */
	public function remove_dashboard_widget() {
		remove_meta_box('wpseo-dashboard-overview', 'dashboard', 'side');
	}

	/**
	 * Removes warning notice when changing permalinks.
	 *
	 * @access public
	 * @since  2.0.2
	 * @return void
	 */
	public function remove_class_hook() {
		remove_class_hook('admin_notices', 'WPSEO_Admin_Init', 'permalink_settings_notice');
	}

	/**
	 * Removes SEO/readability Scores dropdown filters on post list screens.
	 *
	 * @access public
	 * @since  2.0.2
	 * @return void
	 */
	public function remove_seo_scores_dropdown_filters() {
		global $wpseo_meta_columns;
		if ($wpseo_meta_columns) {
			remove_action('restrict_manage_posts', [$wpseo_meta_columns, 'posts_filter_dropdown']);
			remove_action('restrict_manage_posts', [$wpseo_meta_columns, 'posts_filter_dropdown_readability']);
		}
	}

	/**
	 * Adds CSS to hide nags and advertisements.
	 *
	 * @access public
	 * @since  2.0.2
	 * @return void
	 */
	public function hide_visibility_css() {

		echo "\n" . '<style media="screen" id="mandy-tools-yoast" type="text/css">' . "\n";

		// Sidebar ads.
		echo "#sidebar-container.wpseo_content_cell { display: none !important; }\n";

		// Tagline nag.
		echo "#wpseo-dismiss-tagline-notice { display: none; }\n";

		// Robots nag.
		echo "#wpseo-dismiss-blog-public-notice, #wpseo_advanced .error-message { display: none; }\n";

		// Upsell notice in Yoast SEO Dashboard.
		echo "#yoast-warnings #wpseo-upsell-notice, #yoast-additional-keyphrase-collapsible-metabox, .wpseo-keyword-synonyms, .wpseo-multiple-keywords { display: none !important; }\n";

		// Premium upsell admin block.
		echo ".yoast_premium_upsell, .yoast_premium_upsell_admin_block, #wpseo-local-seo-upsell { display: none }\n";

		// Entire "Premium" submenu.
		echo "li#toplevel_page_wpseo_dashboard > ul > li:nth-child(6) { display: none; }\n";

		// Post/Page/Taxonomy deletion premium ad.
		echo "body.edit-php .yoast-alert.notice.notice-warning, body.edit-tags-php .yoast-alert.notice.notice-warning { display: none; }\n";

		// Problems/Notification boxes.
		echo ".yoast-container.yoast-container__error, .yoast-container.yoast-container__warning { display: none; }\n";

		// Image warning nag.
		echo "#yst_opengraph_image_warning { display: none; } #postimagediv.postbox { border: 1px solid #e5e5e5 !important; }\n";

		// Issue counter.
		echo "#wpadminbar .yoast-issue-counter, #toplevel_page_wpseo_dashboard .wp-menu-name .update-plugins { display: none; }\n";

		// Configuration wizard.
		echo ".yoast-alerts .yoast-container__configuration-wizard { display: none; }\n";

		// Help center.
		echo ".wpseo-tab-video__panel.wpseo-tab-video__panel--text, #tab-link-dashboard_dashboard__contact-support, #tab-link-dashboard_general__contact-support, #tab-link-dashboard_features__contact-support, #tab-link-dashboard_knowledge-graph__contact-support, #tab-link-dashboard_webmaster-tools__contact-support, #tab-link-dashboard_security__contact-support, #tab-link-metabox_metabox__contact-support, .yoast-video-tutorial__description:first-child, .react-tabs__tab:last-child, #yoast-helpscout-beacon { display: none; }\n";
		echo ".yoast-help-center__button { display: none !important; }\n";

		// Premium ad after deleting post.
		echo "body.edit-php .yoast-notification.notice.notice-warning.is-dismissible { display: none; }\n";

		echo "</style>\n";
	}
}
