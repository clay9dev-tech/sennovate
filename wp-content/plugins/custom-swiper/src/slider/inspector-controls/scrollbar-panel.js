import {
	Panel,
	PanelBody,
	ToggleControl,
} from "@wordpress/components";

const ScrollbarPanel = ({ attributes, setAttributes }) => {
	const { hasScrollbar, scrollbarMobileOnly } = attributes;

	return (
		<Panel>
			<PanelBody title="Scrollbar Settings" initialOpen={false}>
				<ToggleControl
					label="Scrollbar"
					checked={hasScrollbar}
					onChange={(hasScrollbar) => setAttributes(hasScrollbar)}
				/>

				<ToggleControl
					label="Only on Mobile"
					checked={scrollbarMobileOnly}
					onChange={(scrollbarMobileOnly) => setAttributes({ scrollbarMobileOnly })}
					help={
						scrollbarMobileOnly
							? "Scrollbar will be added to the sides on mobile device only."
							: "The Scrollbar diplayed on bottom of the Slider."
					}
				/>
			</PanelBody>
		</Panel>
	);
};

export default ScrollbarPanel;
