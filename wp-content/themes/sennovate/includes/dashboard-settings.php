<?php
// Register the settings page
function other_content_settings_page() {
	add_menu_page(
		'Other Content',
		'Other Content',
		'manage_options',
		'other-content-settings',
		'other_content_settings_page_html',
		'dashicons-admin-generic',
		100
	);
}
add_action('admin_menu', 'other_content_settings_page');

// Display the settings page content
function other_content_settings_page_html() {
	if (!current_user_can('manage_options')) {
		return;
	}

	settings_errors('my_custom_messages');
	?>
	<div class="wrap">
		<h1><?php echo esc_html(get_admin_page_title()); ?></h1>
		<form action="options.php" method="post">
			<?php
			settings_fields('other_content_custom_settings');
			do_settings_sections('other_content_custom_settings');
			submit_button('Save Settings');
			?>
		</form>
	</div>
	<?php
}

// Register the settings
function other_content_custom_settings_init() {
	register_setting('other_content_custom_settings', 'custom_footer_options');
	register_setting('other_content_custom_settings', 'custom_header_options');
	register_setting('other_content_custom_settings', 'custom_blog_single_end_options_field');
	register_setting('other_content_custom_settings', 'custom_blog_archive_end_options_field');
	register_setting('other_content_custom_settings', 'custom_signup_options_field');

	add_settings_section(
		'other_content_custom_settings_section',
		'Common Settings for the Respected pages',
		'other_content_custom_settings_section_callback',
		'other_content_custom_settings'
	);

	add_settings_field(
		'custom_footer_options_field',
		'Default Footer',
		'custom_footer_options_field_callback',
		'other_content_custom_settings',
		'other_content_custom_settings_section'
	);

	add_settings_field(
		'custom_header_options_field',
		'Blog Archive Banner',
		'custom_header_options_field_callback',
		'other_content_custom_settings',
		'other_content_custom_settings_section'
	);

	add_settings_field(
		'custom_blog_single_end_options_field',
		'Blog end CTA',
		'custom_blog_single_end_options_field_callback',
		'other_content_custom_settings',
		'other_content_custom_settings_section'
	);

	add_settings_field(
		'custom_blog_archive_end_options_field',
		'Blog & Resource Archive end CTA',
		'custom_blog_archive_end_options_field_callback',
		'other_content_custom_settings',
		'other_content_custom_settings_section'
	);

	add_settings_field(
		'custom_signup_options_field',
		'Signup',
		'custom_signup_options_field_callback',
		'other_content_custom_settings',
		'other_content_custom_settings_section'
	);
}
add_action('admin_init', 'other_content_custom_settings_init');

function other_content_custom_settings_section_callback() {
	echo '<p>Select a footer and a blog header from the dropdowns below.</p>'; 
}

function custom_footer_options_field_callback() {
	$selected_post_id = get_option('custom_footer_options');

	$custom_posts = get_posts(array(
		'post_type' => 'wp_block',
		'posts_per_page' => -1,
	));

	if (!empty($custom_posts)) {
		echo '<select name="custom_footer_options">';
		foreach ($custom_posts as $post) {
			echo '<option value="' . esc_attr($post->ID) . '" ' . selected($selected_post_id, $post->ID, false) . '>' . esc_html($post->post_title) . '</option>';
		}
		echo '</select>';
	} else {
		echo '<p>No posts found in the custom post type.</p>';
	}
}

function custom_header_options_field_callback() {
	$selected_post_id = get_option('custom_header_options');

	$custom_posts = get_posts(array(
		'post_type' => 'wp_block',
		'posts_per_page' => -1,
	));

	if (!empty($custom_posts)) {
		echo '<select name="custom_header_options">';
		foreach ($custom_posts as $post) {
			echo '<option value="' . esc_attr($post->ID) . '" ' . selected($selected_post_id, $post->ID, false) . '>' . esc_html($post->post_title) . '</option>';
		}
		echo '</select>';
	} else {
		echo '<p>No posts found in the custom post type.</p>';
	}
}

function custom_blog_single_end_options_field_callback() {
	$selected_post_id = get_option('custom_blog_single_end_options_field');

	$custom_posts = get_posts(array(
		'post_type' => 'wp_block',
		'posts_per_page' => -1,
	));

	if (!empty($custom_posts)) {
		echo '<select name="custom_blog_single_end_options_field">';
		foreach ($custom_posts as $post) {
			echo '<option value="' . esc_attr($post->ID) . '" ' . selected($selected_post_id, $post->ID, false) . '>' . esc_html($post->post_title) . '</option>';
		}
		echo '</select>';
	} else {
		echo '<p>No posts found in the custom post type.</p>';
	}
}

function custom_blog_archive_end_options_field_callback() {
	$selected_post_id = get_option('custom_blog_archive_end_options_field');

	$custom_posts = get_posts(array(
		'post_type' => 'wp_block',
		'posts_per_page' => -1,
	));

	if (!empty($custom_posts)) {
		echo '<select name="custom_blog_archive_end_options_field">';
		foreach ($custom_posts as $post) {
			echo '<option value="' . esc_attr($post->ID) . '" ' . selected($selected_post_id, $post->ID, false) . '>' . esc_html($post->post_title) . '</option>';
		}
		echo '</select>';
	} else {
		echo '<p>No posts found in the custom post type.</p>';
	}
}

function custom_signup_options_field_callback() {
	$selected_post_id = get_option('custom_signup_options_field');

	$custom_posts = get_posts(array(
		'post_type' => 'wp_block',
		'posts_per_page' => -1,
	));

	if (!empty($custom_posts)) {
		echo '<select name="custom_signup_options_field">';
		foreach ($custom_posts as $post) {
			echo '<option value="' . esc_attr($post->ID) . '" ' . selected($selected_post_id, $post->ID, false) . '>' . esc_html($post->post_title) . '</option>';
		}
		echo '</select>';
	} else {
		echo '<p>No posts found</p>';
	}
}

