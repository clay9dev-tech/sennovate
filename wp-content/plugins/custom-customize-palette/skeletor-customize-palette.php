<?php
/*
Plugin Name: WordPress Custom Palette
Plugin URI:  https://wordpress.com
Description: Customizer interface for the Skeletor Abstract Palette
Version:     1.0.8
Author:      WordPress
Author URI:  https://wordpress.com
Text Domain: quickbuild
*/
namespace Skeletor;

defined('ABSPATH') || exit;

define('SKELETOR_CUSTOM_PALETTE_VERSION', '1.0.8');

require_once(__DIR__ . '/class--customize-variable-color-control.php');
require_once(__DIR__ . '/class--palette-customizer.php');


