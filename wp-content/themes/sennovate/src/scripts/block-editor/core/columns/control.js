import { __ } from '@wordpress/i18n';
import { PanelBody, ToggleControl } from '@wordpress/components';

export const ColumnsSettingsControl = (props) => {
	const {
		noGutterSpace
	} = props.attributes;

	return (
		<PanelBody
			className={'skeletor-inspector-control'}
			title={__('Addtional Columns Settings')}
		>

			<ToggleControl
				label={__("No Gutter")}
				help={__("Make all columns have the no gutter space")}
				checked={noGutterSpace}
				onChange={(noGutterSpace) =>
					props.setAttributes({ noGutterSpace })
				}
			/>

		</PanelBody>
	);
};
