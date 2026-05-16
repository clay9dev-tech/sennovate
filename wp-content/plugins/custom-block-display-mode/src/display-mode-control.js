const { __ } = wp.i18n;
const { PanelBody, SelectControl } = wp.components;
const { applyFilters } = wp.hooks;


export const DisplayModeControl = (props) => {
	const {
		displayModeStyle,
	} = props.attributes;

	const displayModeOptions = [
		{ label: __('Inherit'), value: ''},
		{ label: __('Light'), value: 'light'},
		{ label: __('Dark'), value: 'dark'},
	];

	return (
		<PanelBody className={'skeletor-inspector-control'} title={__('Display Mode')} icon="lightbulb" initialOpen={false}>

			<SelectControl
				label={__('Mode')}
				value={displayModeStyle}
				options={applyFilters(
					'mandyBlockDisplayMode.displayModeOptions',
					displayModeOptions
				)}
				onChange={(displayModeStyle) => {
					props.setAttributes({ displayModeStyle });
				}}
				help={__('NOTE: Inherit means this block will use the same display mode of it\'s ancestor(s).')}
			/>

		</PanelBody>
	);
};
