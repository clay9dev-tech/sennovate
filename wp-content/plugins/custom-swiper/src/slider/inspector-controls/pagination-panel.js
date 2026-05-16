import {
	Panel,
	PanelBody,
	ToggleControl,
	SelectControl,
} from "@wordpress/components";

const { blockEditor } = wp;

const PaginationPanel = ({ attributes, setAttributes }) => {
	const { hasPagination, paginationPosition, paginationMargin } = attributes;
	const spacingSizes = Array.from(
		blockEditor.useSetting("spacing.spacingSizes")
	);

	const marginOptions = [
		{ label: "None", value: false },
		...spacingSizes.map((size) => ({ label: size.name, value: size.slug })),
	];

	return (
		<Panel>
			<PanelBody title="Pagination Settings" initialOpen={false}>
				<ToggleControl
					label="Pagination"
					checked={hasPagination}
					onChange={(hasPagination) => setAttributes(hasPagination)}
				/>

				<SelectControl
					label="Position"
					value={paginationPosition}
					options={[
						{ value: "", label: "Choose a Position", disabled: true },
						{ value: "absolute", label: "Absolute" },
						{ value: "static", label: "Static" },
					]}
					onChange={(paginationPosition) =>
						setAttributes({ paginationPosition })
					}
					help={
						paginationPosition === "static"
							? 'Pagination will be under the slides and will "take up space", adding to the total height of the Slider block.'
							: "Pagination will float on top of the bottom side of the slide wrapper. This is the default setting."
					}
				/>

				{paginationPosition === "static" && (
					<SelectControl
						label="Margin"
						value={paginationMargin}
						options={marginOptions}
						onChange={(paginationMargin) => setAttributes({ paginationMargin })}
						help="Controls the amount of space between the bottom of the slide wrapper and the top of the pagination when set to static mode."
					/>
				)}
			</PanelBody>
		</Panel>
	);
};

export default PaginationPanel;
