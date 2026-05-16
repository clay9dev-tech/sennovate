import { __ } from '@wordpress/i18n';
import { PanelBody, SelectControl } from '@wordpress/components';

export const BoxShadowControl = (props) => {
	const { boxShadow } = props.attributes;

	return (
		<PanelBody
			className={'skeletor-inspector-control'}
			title={__('Box Shadow')}
			initialOpen={false}
		>
			<SelectControl
				value={boxShadow}
				options={[
					{ label: 'None', value: '' },
					{ label: 'Soft', value: 'soft' },
					{ label: 'Hard', value: 'hard' },
				]}
				onChange={(boxShadow) => props.setAttributes({ boxShadow })}
			/>
		</PanelBody>
	);
};
