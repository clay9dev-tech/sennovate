import { InspectorAdvancedControls } from "@wordpress/block-editor";
import { Notice, TextareaControl, SelectControl } from "@wordpress/components";

const AdvancedControls = ({ attributes, setAttributes }) => {
	const { advancedSettings, timingFunction } = attributes;

	const advancedSettingsObject = {};
	let invalidJSONWarning = null;

	try {
		const parsedAdvancedSettings = JSON.parse(advancedSettings);
		Object.assign(advancedSettingsObject, parsedAdvancedSettings);
	} catch (e) {
		invalidJSONWarning = (
			<>
				<Notice status="warning" isDismissible={false}>
					WARNING! This is not valid JSON and will be ignored.
				</Notice>
				<hr />
			</>
		);
	}

	return (
		<InspectorAdvancedControls>
			<TextareaControl
				label="Manual JSON"
				help="Add JSON here to force settings into the Swiper configuration object. Mostly intended for responsive stuff, but anything is allowed. Keep in mind that Swiper Breakpoints are mobile-first and move up, so your block settings should define the mobile version, and this field should have the tablet/desktop overrides."
				value={advancedSettings}
				onChange={(advancedSettings) => setAttributes({ advancedSettings })}
			/>
			{invalidJSONWarning}

			<SelectControl
				label="Timing Function"
				value={timingFunction}
				options={[
					{ value: "", label: "Choose a Timing Function", disabled: true },
					{ value: "ease", label: "Ease" },
					{ value: "ease-in", label: "Ease In" },
					{ value: "ease-out", label: "Ease Out" },
					{ value: "ease-in-out", label: "Ease In/Out" },
					{ value: "linear", label: "Linear" },
				]}
				onChange={(timingFunction) => setAttributes({ timingFunction })}
			/>
		</InspectorAdvancedControls>
	);
};

export default AdvancedControls;
