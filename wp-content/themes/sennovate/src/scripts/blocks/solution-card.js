import { registerBlockType } from '@wordpress/blocks';
import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

// Allowed blocks inside the Solution card
const ALLOWED_BLOCKS = ['core/heading', 'core/paragraph', 'core/image'];

registerBlockType('custom/solution-build-card', {
	title: __('Solution Build Card'),
	icon: 'id-alt',
	category: 'widgets',
	attributes: {},
	// ✅ Enable background color support
	supports: {
		color: {
			background: true, // ✅ Enable background color/image
			text: true,
			gradients: true,
		},
		spacing: true,
	},
	attributes: {
		backgroundImage: {
			type: 'string',
			default: '',
		},
	},

	edit: (props) => {
		const blockProps = useBlockProps();

		return (
			<div {...blockProps} className="solution-build-card">
				<InnerBlocks
					template={[
						[
							'core/cover',
							{ className: 'solution-build-card-section' },
							[
								[
									'core/group',
									{ className: 'solution-images' },
									[['core/image'], ['core/image']],
								],
								[
									'core/group',
									{ className: 'solution-content' },
									[
										[
											'core/heading',
											{
												level: 3,
												className:
													'has-obsidian-color has-text-color has-text-align-left',
												placeholder: __('Enter Solution Name...'),
											},
										],
										[
											'core/paragraph',
											{
												className:
													'has-obsidian-color has-text-color has-text-align-left',
												placeholder: __('Enter Solution Content...'),
											},
										],
									],
								],
							],
						],
					]}
					allowedBlocks={['core/cover']}
				/>
			</div>
		);
	},

	save: () => {
		const blockProps = useBlockProps.save();
		return (
			<div {...blockProps} className="solution-build-card">
				<InnerBlocks.Content />
			</div>
		);
	},
});
