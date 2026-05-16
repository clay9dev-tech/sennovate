<?php
namespace Mandy\Posttypes;

class Resource extends \Mandy\Custom_Post_Type {
	/** @var string */
	static $name = 'acq_resource';

	/** @var string */
	static $placeholder_text = 'Enter Resource Name';

	/** @var array */
	static $labels = [
		'menu_name' => 'Resources',
		'singular'  => 'Resource',
		'plural'    => 'Resources',
		'all_items' => 'All Resources',
	];

	/** @var array */
	static $options = [
		'has_archive'        => 'resources',
		'public'             => true,
		'publicly_queryable' => true,
		'show_ui'            => true,
		'show_in_rest'       => true,
		'menu_position'      => 20,
		'menu_icon'          => 'dashicons-media-document',
		'rewrite'           => [
			'slug'       => 'resource/%resource_type%',
			'with_front' => false,
		],
		'supports'           => [
			'title',
			'custom-fields',
			'editor',
			'thumbnail',
			'excerpt',
		],
	];

	/** @var array */
	static $taxonomies = [
		'resource_type'     => [
			'public'            => true,
			'show_in_nav_menus' => false,
			'hierarchical'      => false,
			'labels'            => [
				'name'                       => 'Types',
				'singular_name'              => 'Type',
				'menu_name'                  => 'Types',
				'all_items'                  => 'All Types',
				'parent_item'                => 'Parent Type',
				'parent_item_colon'          => 'Parent Type:',
				'new_item_name'              => 'New Type Name',
				'add_new_item'               => 'Add New Type',
				'edit_item'                  => 'Edit Type',
				'update_item'                => 'Update Type',
				'view_item'                  => 'View Type',
				'separate_items_with_commas' => 'Separate types with commas',
				'add_or_remove_items'        => 'Add or remove types',
				'choose_from_most_used'      => 'Choose from the most used',
				'popular_items'              => 'Popular Types',
				'search_items'               => 'Search Types',
				'not_found'                  => 'Not Found',
				'items_list'                 => 'Types list',
				'items_list_navigation'      => 'Types list navigation',
			],
			'rewrite'           => [
				'slug'         => 'resource',
				'with_front'   => false,
				'hierarchical' => false,
			],
		],
	];

	/**
	 * key/value pairs should be slug => label. If the slug matches a
	 * taxonomy then the column should automatically populate with terms from
	 * that taxonomy. If not, implement an admin_column_{slug}($column, $post)
	 * function in this class that echoes out what the column should contain.
	 *
	 * @var array
	 **/
	static $admin_columns = [
		'resource_type'     => 'Type',
	];

	/** @var array */
	static $options_field_group = [
		[
			'key'   => 'field_resource_opts_lptab',
			'label' => 'Landing Page',
			'type'  => 'tab',
		],
		[
			'key'          => 'field_resource_opts_archive_header',
			'type'         => 'post_object',
			'name'         => 'archive_header',
			'label'        => 'Resource header banner',
			'post_type'    => 'wp_block',
		],
	];

	/**
	 * Adds 'thankyou' rewrite rule.
	 *
	 * Any time a user completes a gravity form, they are sent to ./thank-you.
	 * This action sets up a rewrite rule so that a Thank You page from a
	 * Resource page (presumably a Gated Resource) forwards to the Resource, but
	 * with a 'thankyou' query var set to true so the template knows to display
	 * The Resource Download page instead of the Resource Gate page.
	 */
	public static function thank_you_rewrite() {
		global $wp;

		$wp->add_query_var('thankyou');
		add_rewrite_rule(
			sprintf('^%s?/(.+)/thank-you', static::$name),
			sprintf('index.php?post_type=%s&name=$matches[1]&thankyou=1', static::$name),
			'top'
		);
	}


	static function initialize() {
		parent::initialize();

		add_action('init', [__CLASS__, 'thank_you_rewrite']);
	}
}

add_action('after_setup_theme', ['\\Mandy\\Posttypes\\Resource', 'initialize']);

add_filter('post_type_link', function ($post_link, $post) {
    if ($post->post_type === 'acq_resource') {
        $terms = wp_get_object_terms($post->ID, 'resource_type');
        if (!empty($terms) && !is_wp_error($terms)) {
            return str_replace('%resource_type%', $terms[0]->slug, $post_link);
        } else {
            // Fallback if no term is set
            return str_replace('%resource_type%', 'no-type', $post_link);
        }
    }
    return $post_link;
}, 10, 2);

add_action( 'acf/include_fields', function() {
	if ( ! function_exists( 'acf_add_local_field_group' ) ) {
		return;
	}

acf_add_local_field_group([
    'key'                   => 'group_resource_type_archive_fields',
    'title'                 => 'Resource Fields',
    'fields'                => [
		[
			'key'           => 'ffield_resource_type_archive_title',
			'label'         => 'Latest title',
			'name'          => 'archive_latest_title',
			'type'          => 'text',
		],
		[
			'key'          => 'field_resource_type_archive_header',
			'type'         => 'post_object',
			'name'         => 'archive_header',
			'label'        => 'Header banner',
			'post_type'    => 'wp_block',
		],
		[
			'key'           => 'field_resource_type_archive_grid_title',
			'label'         => 'Grid title',
			'name'          => 'archive_grid_title',
			'type'          => 'text',
		],
    ],
    'location'              => [
        [
            [
                'param'    => 'taxonomy',
                'operator' => '==',
                'value'    => 'resource_type',
            ],
        ],
    ],
    'menu_order'            => 0,
    'position'              => 'acf_after_title',
    'style'                 => 'seamless',
    'label_placement'       => 'top',
    'instruction_placement' => 'label',
    'active'                => true,
    'description'           => '',
    'show_in_rest'          => 0,
]);

} );
