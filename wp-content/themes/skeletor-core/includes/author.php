<?php
class Author_Customizations {
	public static function setup() {
		add_action('acf/init', [__CLASS__, 'add_custom_fields']);
	}

	public static function add_custom_fields() {
		acf_add_local_field_group([
			'key'      => 'group_user_fields',
			'title'    => 'User Fields',
			'fields'   => [
				[
					'key'   => 'field_user_title',
					'label' => 'Title',
					'name'  => 'title',
					'type'  => 'text',
				],
				[
					'key'   => 'field_user_company',
					'label' => 'Company',
					'name'  => 'company',
					'type'  => 'text',
				],
			],
			'location' => [
				[
					[
						'param'    => 'user_form',
						'operator' => '==',
						'value'    => 'edit',
					],
				],
			],
		]);
	}
}

add_action('after_setup_theme', ['Author_Customizations', 'setup']);
