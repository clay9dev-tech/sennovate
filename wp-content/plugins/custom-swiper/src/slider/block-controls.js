import {
	BlockControls,
	BlockVerticalAlignmentToolbar,
} from "@wordpress/block-editor";
import { ToolbarGroup, ToolbarButton, Dashicon } from "@wordpress/components";

const SliderBlockControls = ({ attributes, setAttributes }) => {
	const { infinite, autoplay, hasNavigation, hasScrollbar, hasPagination, verticalAlign } =
		attributes;

	return (
		<BlockControls>
			<ToolbarGroup>
				<ToolbarButton
					label="Autoplay"
					icon="controls-play"
					isPressed={autoplay}
					onClick={() => setAttributes({ autoplay: !autoplay })}
				/>

				<ToolbarButton
					label="Loop"
					icon="controls-repeat"
					isPressed={infinite}
					onClick={() => setAttributes({ infinite: !infinite })}
				/>
			</ToolbarGroup>

			<ToolbarGroup>
				<ToolbarButton
					label="Navigation"
					icon="image-flip-horizontal"
					isPressed={hasNavigation}
					onClick={() => setAttributes({ hasNavigation: !hasNavigation })}
				/>
				<ToolbarButton
					label="Pagination"
					icon="ellipsis"
					isPressed={hasPagination}
					onClick={() => setAttributes({ hasPagination: !hasPagination })}
				/>
				<ToolbarButton
					label="Scrollbar"
					icon="minus"
					isPressed={hasScrollbar}
					onClick={() => setAttributes({ hasScrollbar: !hasScrollbar })}
				/>
			</ToolbarGroup>

			<BlockVerticalAlignmentToolbar
				value={verticalAlign}
				onChange={(verticalAlign) => {
					setAttributes({ verticalAlign });
				}}
			/>
		</BlockControls>
	);
};

export default SliderBlockControls;
