# Mandy Block Display Mode
Add a Display Mode control to InspectorControls for any Block!

## How to Use
In your theme’s **admin** js file, use the [WordPress Hooks api](https://developer.wordpress.org/block-editor/packages/packages-hooks/) addFilter method to filter on **mandyBlockDisplayMode.hasDisplayMode**. Your filter callback will receive two arguments, the current result and a blockName. Return whether or not you want this block to have Spacing controls.

**Example: Adding Display Mode controls to “My Block”**
```js
const { addFilter } = wp.hooks;

addFilter('mandyBlockDisplayMode.hasDisplayMode', 'namespace.hasDisplayMode', (result, blockName) => {
	const blocksWithDisplayMode = [
		'namespace/my-block',
	];

	return result || blocksWithDisplayMode.includes(blockName);
});
```
**Example: Adding Spacing controls to ALL Blocks**
```js
const { addFilter } = wp.hooks;

addFilter('mandyBlockDisplayMode.hasDisplayMode', 'namespace.hasDisplayMode', (result, blockName) => true);
```
