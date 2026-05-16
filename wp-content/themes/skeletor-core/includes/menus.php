<?php
class Menus {
	const NAV_MENUS = [
		'main_nav'      => 'Main Navigation',
		'main_utility'  => 'Main Utility Navigation',
		'main_mob_nav'  => 'Mobile Sticky Navigation',
		'footer_nav'    => 'Footer Navigation',
		'footer_terms'  => 'Footer Terms and Conditions',
	];

	public static function setup() {
		register_nav_menus(self::NAV_MENUS);

		add_filter('nav_menu_link_attributes', [__CLASS__, 'nav_menu_link_attributes'], 10, 4);
		add_filter('walker_nav_menu_start_el', [__CLASS__, 'add_submenu_toggle_icons'], 10, 4);
		add_action('acf/init', [__CLASS__, 'acf_init']);
		add_filter('wp_nav_menu_items', [__CLASS__, 'main_menu_mobile_back_buttons'], 10, 2);
		add_filter('wp_nav_menu_objects', [__CLASS__, 'main_menu_meganav_class'], 10, 2);
	}

	private static function get_mobile_back_button($theme_location) {
		$link = sprintf('<button class="menu-item-link go-back">%s</button>', __('Back'));
		$link = apply_filters('skeletor_menu_back_button', $link, $theme_location);

		$menu_item_classes = ['hide-on-desktop', 'menu-item', 'menu-item-generated'];
		$menu_item_classes = apply_filters('skeletor_menu_back_button_menu_item_classes', $menu_item_classes, $theme_location);
		if (!is_array($menu_item_classes)) {
			$menu_item_classes = [$menu_item_classes];
		}
		$menu_item_classes = array_filter($menu_item_classes);

		return sprintf(
			'<li class="%s">%s</li>',
			implode(' ', $menu_item_classes),
			$link
		);
	}

	public static function main_menu_mobile_back_buttons($menu_items, $args) {
		if ($args->theme_location !== 'main_nav') {
			return $menu_items;
		}

		return str_replace(
			'<ul class="sub-menu">',
			sprintf('<ul class="sub-menu">%s', self::get_mobile_back_button($args->theme_location)),
			$menu_items
		);
	}

	public static function main_menu_meganav_class($menu_items, $args) {
		if ($args->theme_location !== 'main_nav') {
			return $menu_items;
		}

		foreach ($menu_items as &$item) {
			if ($item->menu_item_parent) {
				continue;
			}

			$subnav_style = get_post_meta($item->ID, 'subnav_style', true);
			if ($subnav_style !== 'meganav') {
				continue;
			}

			$item->classes[] = 'menu-item-has-meganav';
		}

		return $menu_items;
	}

	public static function acf_init() {
		$field_group_args = [
			'key'      => 'group_main_nav_item_fields',
			'title'    => 'Main Navigation Item Fields',
			'fields'   => [
				[
					'key'           => 'field_main_nav_item_subnav_style',
					'label'         => 'Style',
					'name'          => 'subnav_style',
					'type'          => 'select',
					'choices'       => [
						''        => 'Standard',
						'meganav' => 'Meganav',
					],
					'instructions'  => '
						<small>Note: “Meganav“ only applies to top-level
						items.</small>
					',
					'allow_null'    => 1,
					'return_format' => 'value',
				],
			],
			'location' => [
				[
					[
						'param'    => 'nav_menu_item',
						'operator' => '==',
						'value'    => 'location/main_nav',
					],
				],
			],
		];

		$field_group_args = apply_filters('skeletor_main_nav_subnav_style_args', $field_group_args);
		if (!$field_group_args) {
			return;
		}

		acf_add_local_field_group($field_group_args);
	}


	/**
	 * Add submenu toggle icon if menu item has children.
	 *
	 * @param  string $title The menu item's title.
	 * @param  object $item  The current menu item.
	 * @param  object  $args  An array of wp_nav_menu() arguments.
	 * @param  int    $depth Depth of menu item. Used for padding.
	 * @return string $title The menu item's title with dropdown icon.
	 */
	public static function add_submenu_toggle_icons($item_output, $item, $depth, $args) {
		$menus_with_submenu_toggles = apply_filters('skeletor_menu_locations_with_submenu_toggles', ['main_nav']);
		if (!in_array($args->theme_location, $menus_with_submenu_toggles, true)) {
			return $item_output;
		}

		if (in_array('menu-item-has-children', $item->classes, true)) {
			$item_output = str_replace(
				'</a>',
				sprintf('%s</a>', self::get_submenu_toggle_html($item)),
				$item_output
			);
		}

		return $item_output;
	}

	public static function get_submenu_toggle_html($item) {
		$button = sprintf(
			'<button class="sub-menu-toggle" aria-label="%s" aria-expanded="false"></button>',
			__('Toggle Sub-menu')
		);
		return apply_filters('skeletor_submenu_toggle_button', $button, $item);
	}

	public static function nav_menu_link_attributes($atts, $menu_item, $args, $depth) {
		$menu_link_class = [];
		if (isset($atts['class'])) {
			$menu_link_class = explode(' ', $atts['class']);
		}

		$menu_link_class[] = 'menu-item-link';
		$menu_link_class[] = sprintf('depth-%d', $depth);

		if (in_array('wp-block-button', $menu_item->classes, true)) {
			$menu_link_class[] = 'wp-block-button__link';
		}

		if (!empty($menu_link_class)) {
			$atts['class'] = implode(' ', $menu_link_class);
		}

		return $atts;
	}
}

add_action('after_setup_theme', ['Menus', 'setup']);
