import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';
import { Tabs } from './tabs';

export default function save({ attributes }) {
	const { tabs, name } = attributes;

	return (
		<div {...useBlockProps.save()}>
			<Tabs {...{ tabs, name }} />
			<div className="tabbed-content__content">
				<InnerBlocks.Content />
			</div>
		</div>
	);
}
