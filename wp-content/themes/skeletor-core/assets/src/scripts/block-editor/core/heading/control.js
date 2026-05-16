const { __ } = wp.i18n;

const { PanelBody, SelectControl } = wp.components;

export const HeadingSettingsControl = (props) => {
	const { displayAs } = props.attributes;

	return (
		<PanelBody
			className={'skeletor-inspector-control'}
			title={__('Extra Heading Settings')}
		>
			<SelectControl
				label={__("Display Style")}
				value={displayAs || ""}
				options={[
					{ label: 'Default', value: '' },
					{ label: 'H1', value: 'h1' },
					{ label: 'H2', value: 'h2' },
					{ label: 'H3', value: 'h3' },
					{ label: 'H4', value: 'h4' },
					{ label: 'H5', value: 'h5' },
					{ label: 'H6', value: 'h6' },
				]}
				onChange={(displayAs) =>
					props.setAttributes({ displayAs })
				}
				help={__("To maintain content heirarchy, we may need to display a heading as if it were at a higher level than it's position in the DOM. Select which heading level this item should be displayed as.")}
			/>
		</PanelBody>
	);
};


/**
 * TODO: add control to add 'count-up' to text similar to the strike-through, superscript, subscript
 * add for paragraph as well
 */
