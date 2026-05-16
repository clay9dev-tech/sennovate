import { Panel, PanelBody, ToggleControl } from "@wordpress/components";

const NavigationPanel = ({ attributes, setAttributes }) => {
	const { hasNavigation, navigationPadding, navigationInset } = attributes;

	return (
		<Panel>
			<PanelBody title="Navigation Settings" initialOpen={false}>
				<ToggleControl
					label="Navigation"
					checked={hasNavigation}
					onChange={(hasNavigation) => setAttributes({ hasNavigation })}
				/>

				<ToggleControl
					label="Navigation Padding"
					checked={navigationPadding}
					onChange={(navigationPadding) => setAttributes({ navigationPadding })}
					help={
						navigationPadding
							? "Padding will be added to the sides of this block so that the navigation does not overlap the slide wrapper."
							: "The slide wrapper will extend from the left edge to the right edge, behind the navigation."
					}
				/>

				<ToggleControl
					label="Navigation Inset"
					checked={navigationInset}
					onChange={(navigationInset) => setAttributes({ navigationInset })}
					help={
						navigationInset
							? "The navigation buttons will be inset from the sides of the block using the standard column gutter."
							: "The navigation buttons will be flush with the sides of the block."
					}
				/>
			</PanelBody>
		</Panel>
	);
};

export default NavigationPanel;
