# Skeletor Custom Palette

This plugin will add a 'Skeletor Palette' section to the WordPress Customizer
interface which can be used to modify the colors assigned to the abstract
palette.

## How to Use

Just install and activate it! You’ll see the new Skeletor Palette section
appear in the sidebar of the Appearance Customizer. From here you’ll be able
to modify the values of everything defined in `settings.custom.colors` in the
theme.json file.

The customizations are saved in an option called `skeletor_theme_palette` and
added to the frontend via an inline stylesheet added to wp_head. If you want
to programmatically modify the palette, hook into
`option_skeletor_theme_palette`

(see https://developer.wordpress.org/reference/hooks/option_option/)

## Filters

**skeletor_customize_palette_capability**
By default the `edit_theme_options` capability is required to have access to
this part of the customizer. That can be changed by filtering into
`skeletor_customize_palette_capability` and returning the capability you want.

```php
add_filter('skeletor_customize_palette_capability', function() {
	return 'activate_plugins';
});
```

**skeletor_abstract_palette**
You can programmatically modify the abstract palette used to populate the
Customizer by filtering into `skeletor_abstract_palette`. It needs to be an
array of arrays, with each keys for `slug`, `label`, and `default`.

```php
add_filter('skeletor_abstract_palette', function($palette) {
	$palette[] = [
		'label'   => 'Hair Color',
		'slug'    => 'hair',
		'default' => 'var(--wp--preset--color--blonde)',
	];

	return $palette;
});
```

## Changelog

`1.0.8` Fix bug where Skeletor\Plugin_Updater was double-loading.

`1.0.7` Adds sentence casing for color labels and filter to override.

`1.0.6` Cleanup css/js enqueueing, expand readme

`1.0.5` Update data mapping to account for cleanup of package.json

`1.0.4` Housekeeping. Add phpdoc to class methods.

`1.0.3` Fixed bug where Bitbucket repos extracted to a misnamed folder

`1.0.2` Add Mandy Private Plugin Updater

`1.0.0` Initial Release