<?php

/**
 * Plugin Name:       WordPress Tabbed Content
 * Plugin URI:        https://wordpress.com
 * Description:       Why show many section when one do trick?
 * Requires at least: 6.6
 * Requires PHP:      7.0
 * Version:           1.0.2
 * Author:            WordPress
 * Author URI: 		  https://wordpress.com/
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       mandy
 *
 * @package CreateBlock
 */

if (! defined('ABSPATH')) {
	exit; // Exit if accessed directly.
}

function mandy_tabbed_content_block_init() {
	register_block_type(__DIR__ . '/build');
	register_block_type(__DIR__ . '/build/tab');
}
add_action('init', 'mandy_tabbed_content_block_init');

define('MANDY_TABBED_CONTENT_VERSION', '`1.0.2');
