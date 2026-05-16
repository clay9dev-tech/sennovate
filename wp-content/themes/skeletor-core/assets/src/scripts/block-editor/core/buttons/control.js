import { __ } from '@wordpress/i18n';
import { PanelBody, SelectControl } from '@wordpress/components';

export const ButtonSettingsControl = (props) => {
	const { size } = props.attributes;

	return (
		<PanelBody
			className={'skeletor-inspector-control'}
			title={__('Button Settings')}
		>
			<SelectControl
				label="Size"
				value={size || ''}
				options={[
					{ label: 'Small', value: 'small' },
					{ label: 'Default', value: '' },
					{ label: 'Large', value: 'large' },
				]}
				onChange={(size) => props.setAttributes({ size })}
			/>
		</PanelBody>
	);
};
