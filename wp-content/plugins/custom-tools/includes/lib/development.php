<?php
if (!defined('ABSPATH')) {
	exit;
}

if (!function_exists('write_log')) {
	/**
	 * Logs passed data to the WordPress debug.log.
	 * Outputs to the WP-CLI log if used by WP-CLI and if WP_CLI constant is set.
	 *
	 * @since  1.0.0
	 * @param  mixed $log Data to log
	 * @return mixed
	 */
	function write_log(...$log) {
		$out = '';

		if (count($log) == 1) {
			$log = $log[0];

			if (is_scalar($log)) {
				$out = $log;
			} else {
				$out = print_r($log, true);
			}
		} elseif (count($log) > 1) {
			$out = call_user_func_array('sprintf', $log);
		}

		if (defined('WP_CLI') && WP_CLI) {
			WP_CLI::log($out);
		}

		error_log($out);
	}
}
