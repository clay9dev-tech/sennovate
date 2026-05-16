import { registerBlockType } from '@wordpress/blocks';
import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

// Allowed blocks inside the menu card
const ALLOWED_BLOCKS = ['core/heading', 'core/paragraph', 'core/image', 'core/buttons'];

registerBlockType('custom/menu-post-card', {
	title: __('Menu Post Card'),
	icon: 'id-alt',
	category: 'widgets',
	attributes: {},

	edit: (props) => {
		const blockProps = useBlockProps();

		return (
			<div {...blockProps} className="menu-post-card">
				<InnerBlocks
					allowedBlocks={ALLOWED_BLOCKS}
					template={[
						['core/image', {}],
						['core/paragraph', { className: 'has-obsidian-color has-text-color has-text-align-left menu-post-type', placeholder: __('Enter Category Name...') }],
						['core/paragraph', { className: 'has-obsidian-color has-text-color has-text-align-left menu-post-title', placeholder: __('Enter Heading Name...') }],
						['core/buttons', { className: 'size-small' }, [
							['core/button', { className: 'is-style-text diretion-btn is-style-outline is-style-outline--1', placeholder: __('Add a Redirection...') }]
						]],
					]}
					templateLock={false}
				/>
			</div>
		);
	},

	save: () => {
		const blockProps = useBlockProps.save();

		return (
			<div {...blockProps} className="menu-post-card">
				<InnerBlocks.Content />
			</div>
		);
	},
});
