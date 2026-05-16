<?php
class Site_Theme_Options {
	const THEME_OPTIONS = [
		[
			'key'   => 'field_theme_opts_tab_resource',
			'label' => 'Resource',
			'type'  => 'tab',
		],
        [
            'key'           => 'field_select_resource_categories',
            'name'          => 'select_resource_categories',
            'label'         => 'Select Resource Categories',
            'type'          => 'taxonomy',
            'taxonomy'      => 'category',
            'field_type'    => 'multi_select',
            'return_format' => 'id',
            'instructions'  => 'Select one or more resource categories.',
        ],
        [
            'key'   => 'field_theme_opts_tab_footer',
            'label' => 'Footer',
            'type'  => 'tab',
        ],
        [
            'key'   => 'field_footer_logo',
            'label' => 'Footer Logo',
            'name'  => 'footer_logo',
            'type'  => 'image',
            'return_format' => 'url',
        ],
        [
            'key'   => 'field_footer_address',
            'label' => 'Address',
            'name'  => 'footer_address',
            'type'  => 'textarea',
            'rows'  => 3,
        ],
        [
            'key'   => 'field_footer_phone',
            'label' => 'Phone',
            'name'  => 'footer_phone',
            'type'  => 'text',
        ],
        [
            'key'   => 'field_footer_email',
            'label' => 'Email',
            'name'  => 'footer_email',
            'type'  => 'text',
        ],
        [
            'key'   => 'field_footer_socials',
            'label' => 'Social Links',
            'name'  => 'footer_socials',
            'type'  => 'repeater',
            'layout' => 'table',
            'sub_fields' => [
                [
                    'key'   => 'field_footer_social_platform',
                    'label' => 'Platform',
                    'name'  => 'platform',
                    'type'  => 'select',
                    'choices' => [
                        'linkedin' => 'LinkedIn',
                        'facebook' => 'Facebook',
                        'twitter'  => 'Twitter/X',
                        'youtube'  => 'YouTube',
                    ],
                ],
                [
                    'key'   => 'field_footer_social_url',
                    'label' => 'URL',
                    'name'  => 'url',
                    'type'  => 'url',
                ],
            ],
        ],
        [
            'key'   => 'field_footer_certs',
            'label' => 'Certifications',
            'name'  => 'footer_certs',
            'type'  => 'repeater',
            'layout' => 'table',
            'sub_fields' => [
                [
                    'key'   => 'field_footer_cert_logo',
                    'label' => 'Logo',
                    'name'  => 'logo',
                    'type'  => 'image',
                    'return_format' => 'url',
                ],
                [
                    'key'   => 'field_footer_cert_alt',
                    'label' => 'Alt Text',
                    'name'  => 'alt',
                    'type'  => 'text',
                ],
            ],
        ],
        [
            'key'   => 'field_footer_menu_columns',
            'label' => 'Menu Columns',
            'name'  => 'footer_menu_columns',
            'type'  => 'repeater',
            'layout' => 'row',
            'sub_fields' => [
                [
                    'key'   => 'field_footer_column_title',
                    'label' => 'Column Title',
                    'name'  => 'title',
                    'type'  => 'text',
                ],
                [
                    'key'   => 'field_footer_column_menu',
                    'label' => 'Select Menu',
                    'name'  => 'menu',
                    'type'  => 'select',
                    'choices' => [],
                    'allow_null' => 1,
                    'multiple' => 0,
                    'ui' => 1,
                    'ajax' => 0,
                    'return_format' => 'value',
                ],
            ],
        ],
        [
            'key'   => 'field_footer_copyright',
            'label' => 'Copyright Text',
            'name'  => 'footer_copyright',
            'type'  => 'text',
        ],
        [
            'key'   => 'field_footer_legal_menu',
            'label' => 'Legal Menu',
            'name'  => 'footer_legal_menu',
            'type'  => 'select',
            'choices' => [],
            'allow_null' => 1,
            'multiple' => 0,
            'ui' => 1,
            'ajax' => 0,
            'return_format' => 'value',
        ],
	];

	public static function setup() {
		add_filter('theme_options_fields', [__CLASS__, 'theme_options_fields']);
        add_action('acf/init', [__CLASS__, 'register_options_page']);
        add_action('acf/init', [__CLASS__, 'add_local_field_group']);

        // Populate menu choices
        add_filter('acf/load_field/name=menu', [__CLASS__, 'load_nav_menus_choices']);
        add_filter('acf/load_field/name=footer_legal_menu', [__CLASS__, 'load_nav_menus_choices']);
	}

	public static function theme_options_fields($fields) {
		return array_merge($fields, self::THEME_OPTIONS);
	}

    public static function load_nav_menus_choices($field) {
        $field['choices'] = [];
        $menus = wp_get_nav_menus();
        if ($menus) {
            foreach ($menus as $menu) {
                $field['choices'][$menu->term_id] = $menu->name;
            }
        }
        return $field;
    }

    public static function register_options_page() {
        if (function_exists('acf_add_options_page')) {
            acf_add_options_page([
                'page_title' => 'Theme Options',
                'menu_title' => 'Theme Options',
                'menu_slug'  => 'theme-options',
                'capability' => 'manage_options',
                'redirect'   => false
            ]);
        }
    }

    public static function add_local_field_group() {
        if (function_exists('acf_add_local_field_group')) {
            acf_add_local_field_group([
                'key' => 'group_theme_options',
                'title' => 'Theme Options',
                'fields' => self::THEME_OPTIONS,
                'location' => [
                    [
                        [
                            'param' => 'options_page',
                            'operator' => '==',
                            'value' => 'theme-options',
                        ],
                    ],
                ],
            ]);
        }
    }
}

add_action('after_setup_theme', ['Site_Theme_Options', 'setup']);
