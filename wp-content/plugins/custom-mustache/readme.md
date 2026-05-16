# Mandy Mustache

Adds support for Mustache templates to Wordpress

## How to Use

Call `MandyMustache::render($template, $data)` to pass an associative array
into a template and echo the result!
```php
$template = '<h1 class="page-title">{{{ title }}}</h1>';
$data = ['title' => 'Hello, World!'];

MandyMustache::render($template, $data);
//<h1 class="page-title">Hello, World!</h1>
```

You can also pass the result into a string instead of output by adding a
third argument with a true value.

```php
printf(
	'<div class="wrapper">%s</div>',
	MandyMustache::render($template, $data, true)
);
//<div class="wrapper"><h1 class="page-title">Hello, World!</h1></div>
```

## Using Template Files Instead of Strings

MandyMustache also registers a folder, `themeDir/views`, as a location for
template files. Instead of passing the template code directly into the render
function, you can provide the template filename and MandyMustache will load
it automatically.

`themeDir/views/heading.mustache`
```mustache
<h1 class="page-title">Hello, {{{ name }}}!</h1>
```

Now we don’t need to have the html in our php code.

```php
MandyMustache::render('heading', [ 'name' => 'World' ]);
//<h1 class="page-title">Hello, World!</h1>
```

## Built-In Partials

MandyMustache comes pre-installed with partial views for Links and Image that
are customized to match the schema for the ACF Link and Image fields. You can
display an ACF image in a MandyMustache template like so:

```mustache
An image here: {{#imageFieldName}}{{> image}}{{/imageFieldName}}
My cool link: {{#linkFieldName}}{{> link}}{{/linkFieldName}}
```

## Filtering Data with before_render

Before rendering, the data that feeds the template is passed through a filter
named `before_render`. By hooking into this filter, you can modify the data
before output using a callback function.

```php
function decorate_name($data) {
	if (isset($data['name'])) {
		$data['name'] = sprintf(
			'%s!!!',
			strtoupper($data['name'])
		);
	} else {
		$data['name'] = 'unknown';
	}

	return $data;
}

add_filter('before_render', 'decorate_name');

MandyMustache::render('heading', [ 'name' => 'world' ]);
//<h1 class="page-title">Hello, WORLD!!!!</h1>

MandyMustache::render('heading');
//<h1 class="page-title">Hello, unknown!</h1>
```

## Targeted Filters

Of course, actaully *using* the previous example would be a bad idea because
it affects **all** renders!

```php
$data = [ 'name' => 'Matt' ];
$quiet = '<p class="quiet">please be quiet, {{{ name }}}.</p>';

add_filter('before_render', 'decorate_name');
MandyMustache::render($quiet, $data);
//<p class="quiet">please be quiet, MATT!!!.</p>
```

Instead of using `before_render`, you probably want `before_render_{$name}`.
That way you only affect the data for the template with the specified name.

```php
//oops, I only wanted the title to shout! Let's fix that...
remove_filter('before_render', 'decorate_name');
//and only add this filter for the heading template
add_filter('before_render_heading', 'decorate_name');

MandyMustache::render($quiet, $data);
//<p class="quiet">please be quiet, Matt.</p>

MandyMustache::render('heading', $data);
//<h1 class="page-title">Hello, MATT!!!!</h1>
```

The string output from the render function is also passed through another
filter called `after_render_{$name}` just before being returned. This is a
good way to use a callback for a last-minute search-and-replace or wrapper.
You can also add the template data as a second argument on this filter.
Unlike before_render, there is no generic after_render filter for ALL
templates.

```php
add_filter('after_render_heading', function ($html, $data) {
	$json = json_encode($data);
	return sprintf(
		'<div class="render-wrapper" data-template-data="%s">%s</div>',
		esc_attr($json)
		$html
	);
}, 10, 2);
```

## Using MandyMustache With Plugins

You can register a custom `views` folder by hooking into the
`mandy_mustache_view_path` filter. This works like a system path, so you can
strategically append or prepend folders here, depending on whether or not you
want the Theme views folder to take precedence.

## See Also

* https://github.com/bobthecow/mustache.php
* http://mustache.github.io/mustache.5.html
