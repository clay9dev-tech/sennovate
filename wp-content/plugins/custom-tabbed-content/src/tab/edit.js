import { useBlockProps, InnerBlocks, InspectorControls } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';

export default function Edit({ attributes, setAttributes }) {
	const { metadata = {} } = attributes;

	return <>
		<div {...useBlockProps()}>
			<InnerBlocks template={[
				['core/paragraph']
			]} />
		</div>
		<InspectorControls>
			<PanelBody>
				<TextControl
					label={__('Tab Name')}
					value={metadata.name}
					onChange={(name) => setAttributes({
						metadata: {
							...metadata,
							name
						}
					})} />
			</PanelBody>
		</InspectorControls>
	</>
}
