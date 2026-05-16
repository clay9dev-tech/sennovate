import {
	BlockControls,
	BlockVerticalAlignmentToolbar,
} from "@wordpress/block-editor";

const SlideBlockControls = ({ attributes, setAttributes }) => {
	const { verticalAlign } = attributes;

	return (
		<BlockControls>
			<BlockVerticalAlignmentToolbar
				value={verticalAlign}
				onChange={(verticalAlign) => {
					setAttributes({ verticalAlign });
				}}
			/>
		</BlockControls>
	);
};

export default SlideBlockControls;
