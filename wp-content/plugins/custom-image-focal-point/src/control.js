const { __ } = wp.i18n;
const { PanelBody, FocalPointPicker } = wp.components;

export const FocalPointControl = (props) => {
	const { focalPoint, url } = props.attributes;

	return (
		<PanelBody title={__('Image Focal Point')} initialOpen={false}>
			<FocalPointPicker
				   label="Focal Point"
				     url={url}
				   value={focalPoint}
				onChange={(focalPoint) => {
					props.setAttributes({ focalPoint });
				}}
			/>
		</PanelBody>
	);
};
