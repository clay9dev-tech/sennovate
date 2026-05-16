<?php

class DebugMode {
	public static function setup() {
		add_filter('body_class', [__CLASS__, 'body_class']);
	}

	public static function body_class($class) {
		$class[] = 'debug';

		return $class;
	}
}

if (isset($_GET['debug'])) {
	add_action('after_setup_theme', ['DebugMode', 'setup']);
}
