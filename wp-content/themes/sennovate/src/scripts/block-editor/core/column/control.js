import { __ } from '@wordpress/i18n';
import { PanelBody, CheckboxControl } from '@wordpress/components';

export const ColumnSettingsControl = (props) => {
	const { ColumnLeftAlign } = props.attributes;

	return (
		<PanelBody
			className={'skeletor-inspector-control'}
			title={__('Addtional Column Settings')}
		>

			<CheckboxControl
				label={__("Left Align - Mobile")}
				help={__("Element will be left align when on mobile viewports")}
				checked={ColumnLeftAlign}
				onChange={(ColumnLeftAlign) =>
					props.setAttributes({ ColumnLeftAlign })
				}
			/>
		</PanelBody>
	);
};
