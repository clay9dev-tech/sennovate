const BLOCK_STYLES = {
	'core/button': [
		{
			label: 'Text',
			name: 'text',
		},
	],
	'core/heading': [
		{
			label: 'Display',
			name: 'display-heading',
		},
		{
			label: 'Eyebrow',
			name: 'eyebrow-heading',
		},
	],
	'core/group': [
		{
			label: 'Branded Container A',
			name: 'container-a',
		},
		{
			label: 'Branded Container B',
			name: 'container-b',
		},
		{
			label: 'Branded Container C',
			name: 'container-c',
		},
	],
};

const REMOVE_BLOCK_STYLES = {
	'core/separator': ['wide', 'dots'],
};

function onDocumentReady() {
	if (!wp.blocks) {
		return;
	}

	const { registerBlockStyle, unregisterBlockStyle } = wp.blocks;

	for (const blockId in REMOVE_BLOCK_STYLES) {
		const blockStyles = REMOVE_BLOCK_STYLES[blockId];
		blockStyles.forEach((style) => unregisterBlockStyle(blockId, style));
	}

	for (const blockId in BLOCK_STYLES) {
		const blockStyles = BLOCK_STYLES[blockId];
		blockStyles.forEach((style) => registerBlockStyle(blockId, style));
	}
}

document.addEventListener('DOMContentLoaded', onDocumentReady);
