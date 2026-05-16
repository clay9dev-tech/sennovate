import { __ } from '@wordpress/i18n';
import { PanelBody, ToggleControl } from '@wordpress/components';

export const ImageSettingsControl = (props) => {
	const { forceFullWidth } = props.attributes;

	return (
		<PanelBody
			className={'skeletor-inspector-control'}
			title={__('Image Settings')}
		>
			<ToggleControl
				label="Force Full Width"
				checked={forceFullWidth}
				onChange={(forceFullWidth) =>
					props.setAttributes({ forceFullWidth })
				}
			/>
		</PanelBody>
	);
};
