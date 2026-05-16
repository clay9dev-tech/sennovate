Skeletor Blocks
===============
This plugin adds an abstract class called `Skeletor_Block` to your theme. By extending this class, you can quickly create your own custom Blocks, with lots of easy-to-use hooks for advanced customization.

This plugin users MandyMustache to render, so make sure you have that installed too!

[Mandy Mustache](https://bitbucket.org/madebymandy/mandy-mustache/src/master/)

Working With Skeletor Blocks
----------------------------
We have a LOT of documentation for working with Skeletor. Check out our snippets collection on Bitbucket for the full list, but here are a few highlights for getting started:

[Getting Started With Skeletor 3](https://bitbucket.org/madebymandy/workspace/snippets/6XzEbk/getting-started-with-skeletor-3)  
[Layout Composition in Skeletor 3](https://bitbucket.org/madebymandy/workspace/snippets/5XE966/layout-composition-in-skeletor-3)  
[Creating a Skeletor Block](https://bitbucket.org/madebymandy/workspace/snippets/kxMajB/creating-a-skeletor-block)


Skeletor Cheat Sheet
--------------------
A Skeletor Block is going to have two basic parts:  
- A PHP Class that extends `Skeletor_Block` in theme/includes/blocks  
- A Mustache view in theme/views - the filename should be the the static $name from the php class  

And optionally if you need custom css/js for the block  
- An scss file in theme/assets/src/styles  
- A js file in theme/assets/src/js

Skeletor Block Snippet
----------------------
Add this to your Visual Studio Code php.json snippets file for a quick start.

```json
"Block Class": {
	"prefix": "block",
	"body": [
		"use \\Mandy\\Skeletor_Block;",
		"",
		"if (!class_exists('\\Mandy\\Skeletor_Block')) {",
		"	return;",
		"}",
		"",
		"class ${1:VTL_Class_Name} extends Skeletor_Block {",
		"	public static \\$title = '${2:block_title}';",
		"	public static \\$name = '${3:block_name}';",
		"",
		"	public static \\$block_settings = [];",
		"	public static \\$field_group = [];",
		"	public static \\$inner_blocks = [];",
		"}",
		"",
		"add_action('after_setup_theme', ['${1:VTL_Class_Name}', 'init']);",
		""
	],
	"description": "Stubbed out class for a Skeletor Block"
}
```

Basic Skeletor Mustache View
----------------------------

```html
<div {{{ _block_attributes }}}>
	{{{innerBlocks}}}
</div>
```