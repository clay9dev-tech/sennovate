<?php
if (! defined('ABSPATH')) {
	exit;
}

/**
 * Development helpers and functions.
 *
 * @since  2.0.3
 */
class Mandy_Development {

	/**
	 * Sets up the class functionality.
	 *
	 * @access public
	 * @since  2.0.3
	 * @return void
	 */
	public function __construct() {
		add_action('admin_notices', [$this, 'plugin_deactivation_notice']);
	}

	/**
	 * Adds admin notice
	 *
	 * @access public
	 * @since  2.0.3
	 * @return string HTML markup of notice.
	 */
	public function plugin_deactivation_notice() {
		$home_url = parse_url(get_home_url());

		if ($home_url && (isset($home_url['host']) && substr($home_url['host'], -6) === '.local')) {

			$plugins = [];

			switch (true) {
				case is_plugin_active('ithemes-security/ithemes-security.php'):
					$plugins[] = 'iThemes Security';
					break;

				case is_plugin_active('ithemes-security-pro/ithemes-security-pro.php'):
					$plugins[] = 'iThemes Security Pro';
					break;

				case is_plugin_active('backupbuddy/backupbuddy.php'):
					$plugins[] = 'BackupBuddy';
					break;

				default:
					break;
			}

			if (!empty($plugins)) {
				$class = 'notice notice-warning';
				$list = '';

				foreach ($plugins as $plugin) {
					$list .= sprintf(
						'<li>%s</li>',
						esc_html($plugin)
					);
				}

				printf(
					'<div class="%s"><p>%s</p><ul style="list-style: disc; padding-left: 2.5rem;">%s</ul></div>',
					$class,
					__('This site is running on a <code>.local</code> hostname. Please deactivate the following production plugins:', 'mandy-tools'),
					$list
				);
			}
		}
	}
}
