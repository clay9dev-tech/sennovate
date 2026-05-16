<?php
class Mandy_CLI {
	function rocket_flush() {
		if (function_exists('flush_rocket_htaccess')) {
			flush_rocket_htaccess();
		}

		if (function_exists('rocket_generate_config_file')) {
			rocket_generate_config_file();
		}
	}
}

if (defined('WP_CLI') && class_exists('WP_CLI')) {
	WP_CLI::add_command('vtl', 'Mandy_CLI');
}
