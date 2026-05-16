import {
	Panel,
	PanelBody,
	ToggleControl,
	__experimentalNumberControl as NumberControl,
} from "@wordpress/components";

const AutoplayPanel = ({ attributes, setAttributes }) => {
	const { autoplay, autoplayDelay, pauseOnMouseEnter } = attributes;

	return (
		<Panel>
			<PanelBody title="Autoplay Settings" initialOpen={false}>
				<ToggleControl
					label="Autoplay"
					checked={autoplay}
					onChange={(autoplay) => setAttributes(autoplay)}
				/>

				{autoplay && (
					<>
						<NumberControl
							label="Autoplay Delay"
							help="Time (in milliseconds) to wait before advancing to the next slide"
							value={autoplayDelay}
							onChange={(autoplayDelay) => setAttributes({ autoplayDelay })}
						/>

						<ToggleControl
							label="Pause on Mouse Enter"
							help="If enabled, the slider will pause when the mouse is over it"
							checked={pauseOnMouseEnter}
							onChange={(pauseOnMouseEnter) => setAttributes({ pauseOnMouseEnter })}
						/>
					</>
				)}
			</PanelBody>
		</Panel>
	);
};

export default AutoplayPanel;
