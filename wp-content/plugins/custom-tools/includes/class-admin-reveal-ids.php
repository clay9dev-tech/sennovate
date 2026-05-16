<?php
if (! defined('ABSPATH')) {
	exit;
}

/*
	Reveal IDs (Modified by Mandy)
	Based on version: 1.5.3

	https://wordpress.org/plugins/reveal-ids-for-wp-admin-25/

	Copyright 2008-2015 Oliver Schlöbe (email : scripts@schloebe.de)
	http://www.schloebe.de/wordpress/reveal-ids-for-wp-admin-25-plugin/

	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License as published by
	the Free Software Foundation; either version 2 of the License, or
	(at your option) any later version.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
class Mandy_Admin_Reveal_Ids {

	public function __construct() {
		add_action('admin_init', [$this, 'init']);
		add_action('admin_head', [$this, 'add_css']);
	}

	/**
	 * Initializes plugin
	 *
	 * @return void
	 */
	public function init() {
		global $wpversion, $pagenow;

		if (!function_exists('add_action')) {
			return;
		}

		if (!current_user_can('administrator')) {
			return;
		}

		add_filter('manage_media_columns', [$this, 'column_add']);
		add_action('manage_media_custom_column', [$this, 'column_value'], 10, 2);

		add_filter('manage_link-manager_columns', [$this, 'column_add']);
		add_action('manage_link_custom_column', [$this, 'column_value'], 10, 2);

		add_action('manage_edit-link-categories_columns', [$this, 'column_add']);
		add_filter('manage_link_categories_custom_column', [$this, 'column_return_value'], 100, 3);

		foreach (get_taxonomies() as $taxonomy) {
			if (isset($taxonomy)) {
				add_action("manage_edit-{$taxonomy}_columns", [$this, 'column_add']);
				add_filter("manage_{$taxonomy}_custom_column", [$this, 'column_return_value'], 100, 3);
				if (version_compare($GLOBALS['wp_version'], '3.0.999', '>')) {
					add_filter("manage_edit-{$taxonomy}_sortable_columns", [$this, 'column_add']);
				}
			}
		}

		foreach (get_post_types() as $ptype) {
			if (isset($ptype)) {
				add_action("manage_edit-{$ptype}_columns", [$this, 'column_add']);
				add_filter("manage_{$ptype}_posts_custom_column", [$this, 'column_value'], 100, 3);
				if (version_compare($GLOBALS['wp_version'], '3.0.999', '>')) {
					add_filter("manage_edit-{$ptype}_sortable_columns", [$this, 'column_add']);
				}
			}
		}

		add_action('manage_users_columns', [$this, 'column_add']);
		add_filter('manage_users_custom_column', [$this, 'column_return_value'], 100, 3);
		if (version_compare($GLOBALS['wp_version'], '3.0.999', '>')) {
			add_action('manage_users-network_columns', [$this, 'column_add']);
			add_filter('manage_users_sortable_columns', [$this, 'column_add']);
			add_filter('manage_users-network_sortable_columns', [$this, 'column_add']);
		}

		add_action('manage_edit-comments_columns', [$this, 'column_add']);
		add_action('manage_comments_custom_column', [$this, 'column_value'], 100, 2);
		if (version_compare($GLOBALS['wp_version'], '3.0.999', '>')) {
			add_filter('manage_edit-comments_sortable_columns', [$this, 'column_add']);
		}

		if (version_compare($GLOBALS['wp_version'], '3.0.999', '>')) {
			add_action('manage_sites-network_columns', [$this, 'column_add']);
			add_filter('manage_sites_custom_column', [$this, 'column_value'], 100, 3);
		}
	}

	/**
	 * Adds CSS
	 */
	public function add_css() {
		echo "\n" . '<style type="text/css">
	table.widefat th.column-ridwpaid {
		width: 70px;
	}

	table.widefat td.column-ridwpaid {
		word-wrap: normal;
	}
	</style>' . "\n";
	}

	/**
	 * Add the new 'ID' column
	 */
	public function column_add($cols) {
		$cols['ridwpaid'] = __('ID');
		return $cols;
	}


	/**
	 * Echoes the ID column content
	 */
	public function column_value($column_name, $id) {
		if ($column_name === 'ridwpaid') {
			echo $id;
		}
	}


	/**
	 * Returns the ID column content
	 */
	public function column_return_value($value, $column_name, $id) {
		if ($column_name === 'ridwpaid') {
			$value = $id;
		}
		return $value;
	}
}
