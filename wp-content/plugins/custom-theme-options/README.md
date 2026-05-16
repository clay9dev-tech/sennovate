# Skeletor Theme Options

This plugin uses Advanced Custom Fields to create a custom global options
page called **Other Content**. The field group controlling that options page
is split into tabs and comes with a few starter options we always want like
`Site Logo` and `404 Page Content`.

## How to Use

**Filter theme_options_fields** — Before creating the options page during the
`acf/init` action, Skeletor Theme Options runs the field group array through
a filter called `theme_options_fields`. From your theme *(or another plugin)*
you can hook into theme_options_fields and append your own set of ACF fields!
Or prepend them! Get crazy!

**Filter theme_options_title** — Do you prefer to call the options something more 
_sassy_? Before creating the options page, the title is passed through a filter 
called `theme_options_title`. From your theme *(or another 
plugin)* you can hook into theme_options_fields and rename the title!

**Filter theme_options_icon** — Don't like the default icon? Before creating the 
options page, the icon is passed through a filter called `theme_options_icon`. 
From your theme *(or another plugin)* you can hook into theme_options_icon and
set whatever icon you prefer!

**Filter theme_options_menu_position** — Don't like where the option menu is 
located? Before creating the options page, the position is passed through a filter 
called `theme_options_menu_position`. From your theme *(or another plugin)* you can 
hook into theme_options_menu_position and set whatever position you prefer!

**Create Alternate Options Page** — If you’d rather create your own options
page instead of hooking into the global options,
SkeletorThemeOptions::add_options_page() can help save some work. Just pass a
title and field group and it’ll take care of the rest. You can also customize
the post_id and parent_slug if you need more advanced functionality. Check
the code for details!

## Remember
* **Don’t forget the tabs!** — The first field you append should almost
  always be a tab. Don't worry about the placement, there's code in place to
  force all of the top-level tabs to the left side.
* **The fields aren’t “Skeletor Block” fields** — The automatic key, name,
  type, etc. that the $field_group in a Skeletor_Block gets don’t happen here.

## Example
Add this code to your theme’s includes to add a **Google Tag Manager** tab in
your Theme Options, wired up to a code snippet in the header.

```php
<?php
class Google_Tag_Manager {
	const GTM_SNIPPET = /* ...snip... */;

	const THEME_OPTIONS = [
		[
			'key'   => 'field_theme_opts_tab_gtm',
			'label' => 'Google Tag Manager',
			'type'  => 'tab',
		],
		[
			'key'   => 'field_theme_opts_google_tag_manager_id',
			'type'  => 'text',
			'name'  => 'gtm_id',
			'label' => 'Google Tag Manager ID',
		],
		[
			'key'   => 'field_theme_opts_google_tag_manager_gaid',
			'type'  => 'text',
			'name'  => 'ga_id',
			'label' => 'Google Analytics ID',
		],
	];

	public static function setup() {
		add_filter('theme_options_fields', [__CLASS__, 'add_theme_options']);
		if (function_exists('get_field')) {
			add_action('wp_head', [__CLASS__, 'wp_head']);
		}
	}

	public static function add_theme_options($options) {
		return array_merge($options, self::THEME_OPTIONS);
	}

	public static function wp_head() {
		$ga_id = get_field('ga_id', 'options');
		$gtm_id = get_field('gtm_id', 'options');

		if ($ga_id && $gtm_id) {
			printf(self::GTM_SNIPPET, $ga_id, $gtm_id);
		}
	}
}

add_action('after_setup_theme', ['Google_Tag_Manager', 'setup']);
```