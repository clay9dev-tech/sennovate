=== Mandy Tools ===
Contributors: adamwalter,antishow,makkal
Requires at least: 5.3
Tested up to: 5.6
Stable tag: 2.1.0
Requires PHP: 7.2
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Mandy functions, helpers, and customizations plugin for WordPress.

== Description ==

Mandy functions, helpers, and customizations plugin for WordPress.

== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/mandy-tools` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress

== Changelog ==

= 2.1.0 =
* Adds Media upload restriction filters

= 2.0.9 =
* Adds oEmbed Provider logic

= 2.0.8 =
* Move the AllowDynamicProperties above the class

= 2.0.7 =
* PHP 8.2 Deprecated issues fixed.
* WP admin custome Nav - Search By post id, It’s help to add menu quick

= 2.0.6 =
* Adds Gravity Forms, Menu editing, and SearchWP settings capabilities to the Editor role.

= 2.0.5 =
* Add third $args argument to load_template_part.

= 2.0.4 =
* Add helper function load_template_part()

= 2.0.3 =
* Adds Mandy_Development class.
* Adds admin notice to deactivate plugins that shouldn't run on .local sites.

= 2.0.2 =
* Adds components from Hide SEO Bloat to get Yoast's ads under control.

= 2.0.1 =
* Adds Post Type Index Page meta box to Menus page.

= 2.0.0 =
* No longer a must-use plugin. Install in the main plugins directory.
* The plugin will only be compatible with new Skeletor 3.0 sites.
* The plugin is now upgradeable. All future development should keep this in mind and keep backwards compatibility for all sites running Skeletor 3.0 and above.
* Theme setup functions have been moved back into the theme (Skeletor 3.0).
* Classes and functions better organized.
* Plugin is set up to be translatable. Future development should keep this in mind.
* Removed a ton of out-of-date WordPress tweaks that are no longer necessary.
* Removed some cleanup functions that are just OCD and might cause issues in the future.
* Removed smart_excerpt in favor of filtering the default excerpt. Use the_excerpt and get_the_excerpt now. The filter excerpt_length is now in the theme (Skeletor 3.0) to customize the length.
* Removed media library upload restrictions in favor of the core large image handling added in WordPress 5.3.
* Removed Mandy_Permissions class which added full Gravity Forms access to Editors. It is more appropriate to use a dedicated plugin for this. Members or User Role Editor will be a standard plugin install (Skeletor 3.0).
* get_post_with_meta moved to this plugin and renamed get_post_and_fields to be more inline with other function names.
