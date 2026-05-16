import { __ } from '@wordpress/i18n';
import { PanelBody, CheckboxControl } from '@wordpress/components';

export const ColumnSettingsControl = (props) => {
	const { stickyContent } = props.attributes;

	return (
		<PanelBody
			className={'skeletor-inspector-control'}
			title={__('Extra Column Settings')}
		>
			<CheckboxControl
				label="Sticky Content"
				checked={stickyContent}
				onChange={(stickyContent) =>
					props.setAttributes({ stickyContent })
				}
			/>
		</PanelBody>
	);
};
