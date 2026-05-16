import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';

export default function save() {
	return (
		<div
			role="tabpanel"
			tabindex="-1"
			{...useBlockProps.save()}>
			<InnerBlocks.Content />
		</div>
	);
}
