<?php
/**
 * Plugin Name:       WordPress Swiper
 * Plugin URI:        https://wordpress.com
 * Description:       Powered by Swiper JS and love.
 * Requires at least: 6.1
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            WordPress
 * Author URI:        https://wordpress.com
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 *
 */

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function create_block_slider_block_init() {
	register_block_type(__DIR__ . '/build/slide');
	register_block_type(__DIR__ . '/build/slider');
}
add_action( 'init', 'create_block_slider_block_init' );
