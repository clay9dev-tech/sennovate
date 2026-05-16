import { registerBlockType } from '@wordpress/blocks';
import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

// Allowed blocks inside the Partner card
const ALLOWED_BLOCKS = ['core/heading', 'core/paragraph', 'core/image'];

registerBlockType('custom/partner-card', {
	title: __('Partner Card'),
	icon: 'id-alt',
	category: 'widgets',
	attributes: {},
	// ✅ Enable background color support
	supports: {
		color: {
			background: true,
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
			<div {...blockProps} className="partner-card">
				<InnerBlocks
					template={[
						[
							'core/cover',
							{ className: 'partner-card-section' },
							[
								[
									'core/group',
									{ className: 'partner-images' },
									[
										[
											'core/image',
											{ className: 'partner-logo top-left' }
										],
										[
											'core/image',
											{ className: 'partner-logo top-center' }
										],
										[
											'core/image',
											{ className: 'partner-logo top-right' }
										],
										[
											'core/image',
											{ className: 'partner-logo mid-left' }
										],
										[
											'core/image',
											{ className: 'partner-logo mid-right' }
										],
										[
											'core/image',
											{ className: 'partner-logo bottom-left' }
										],
										[
											'core/image',
											{ className: 'partner-logo bottom-center' }
										],
										[
											'core/image',
											{ className: 'partner-logo bottom-right' }
										],
									],
								],
								[
									'core/group',
									{ className: 'partner-content' },
									[
										[
											'core/heading',
											{
												level: 2,
												className:
													'has-obsidian-color has-text-color has-text-align-center',
												placeholder: __('Enter Partner Name...'),
											},
										],
										[
											'core/paragraph',
											{
												className:
													'has-obsidian-color has-text-color has-text-align-center',
												placeholder: __('Enter Partner Content...'),
											},
										],
										[
											'core/buttons',
											{ className: 'size-small' },
											[
												[
													'core/button',
													{
														className: 'is-style-text partner-cta-btn', placeholder: __('Add a Redirection...')
													}
												]
											]
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
			<div {...blockProps} className="partner-card">
				<InnerBlocks.Content />
			</div>
		);
	},
});
