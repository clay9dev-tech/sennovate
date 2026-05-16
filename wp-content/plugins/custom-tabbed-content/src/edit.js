import { __ } from '@wordpress/i18n';
import { useBlockProps, InnerBlocks } from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';
import { useEffect } from '@wordpress/element';
import { applyFilters } from '@wordpress/hooks';
import { Tabs } from './tabs';
import './editor.scss';

export default function Edit({ attributes, setAttributes, clientId }) {
	const blockProps = useBlockProps();
	const tabData = useSelect(select => select('core/block-editor').getBlocks(clientId));
	const { metadata = {} } = attributes;
	const { name = 'Some Tabs' } = metadata;

	setAttributes({ name });

	useEffect(() => {
		const tabs = tabData.map((T, n) => {
			const { metadata } = T.attributes;
			return (metadata && metadata.name) ? metadata.name : `Tab ${n + 1}`;
		});

		setAttributes({
			tabs: applyFilters('tabbedContent.tabs', tabs),
			tabId: clientId
		});
	}, [tabData]);
	const { tabs = [], tabId } = attributes;

	return (
		<div {...blockProps}>
			<Tabs tabId={tabId} tabs={tabs} name={name} />
			<div className="tabbed-content__content">
				<InnerBlocks template={[
					['mandy/tabbed-content--tab']
				]} />
			</div>
		</div>
	);
}
