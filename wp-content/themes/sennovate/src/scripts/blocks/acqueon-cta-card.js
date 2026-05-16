import { registerBlockType } from '@wordpress/blocks';
import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

// Allowed blocks inside the Acqueon CTA card
const ALLOWED_BLOCKS = ['core/heading', 'core/paragraph', 'core/image', 'core/buttons'];

registerBlockType('custom/acqueon-cta-card', {
	title: __('Acqueon CTA Card'),
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
			<div {...blockProps} className="acqueon-cta-card">
				<InnerBlocks
					template={[
						[
							'core/cover',
							{ className: 'acqueon-cta-card-section' },
							[
								[
									'core/group',
									{ className: 'acqueon-cta-content' },
									[
										[
											'core/heading',
											{
												level: 2,
												className:
													'has-obsidian-color has-text-color has-text-align-left',
												placeholder: __('Enter CTA Heading...'),
											},
										],
										[
											'core/paragraph',
											{
												className:
													'has-obsidian-color has-text-color has-text-align-left',
												placeholder: __('Enter CTA Content...'),
											},
										],
										['core/buttons',
											{ className: 'size-small' },
											[
												[
													'core/button',
													{
														className: 'is-style-text cta-btn', placeholder: __('Add a Redirection...')
													}
												]
											]
										],
									],
								],
								[
									'core/group',
									{ className: 'acqueon-cta-images' },
									[['core/image']],
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
			<div {...blockProps} className="acqueon-cta-card">
				<InnerBlocks.Content />
			</div>
		);
	},
});
