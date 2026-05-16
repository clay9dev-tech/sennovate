import { __ } from '@wordpress/i18n';
import { PanelBody, ToggleControl } from '@wordpress/components';

export const ButtonSettingsControl = (props) => {
	const { videoPopup } = props.attributes;

	return (
		<PanelBody
			className={'skeletor-inspector-control'}
			title={__('Button Settings')}
		>
			<ToggleControl
				label="Enble Video Popup"
				checked={videoPopup}
				help={__('The sample video URLs are from Vimeo and YouTube. \r\n https://player.vimeo.com/video/109626219 \r\n https://www.youtube.com/embed/cBJyo0tgLnw')}
				onChange={(videoPopup) =>
					props.setAttributes({ videoPopup })
				}
			/>
		</PanelBody>
	);
};
