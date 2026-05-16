import { BlockControls } from "@wordpress/block-editor";
import { ToolbarGroup, ToolbarButton, Dashicon } from "@wordpress/components";

const SliderBlockNavigationControls = ({ swiper, blockAppender, isPlaying, autoplay }) => {
	if (!swiper.current) {
		return false;
	}
	const { slides, activeIndex } = swiper.current;

	return (
		<BlockControls __experimentalShareWithChildBlocks={true}>
			<ToolbarGroup>
				<ToolbarButton
					label="Back"
					icon={<Dashicon icon="controls-back" />}
					onClick={() => swiper.current.slidePrev()}
				/>
				{autoplay && (
					<ToolbarButton
						label="Play/Pause"
						isPressed={!isPlaying}
						icon="controls-pause"
						onClick={() => {
							if (isPlaying) {
								swiper.current.autoplay?.stop();
							} else {
								swiper.current.autoplay?.start();
							}
						}}
					/>
				)}
				<p style={{ margin: 0, alignSelf: "center" }}>
					{activeIndex + 1} of {slides.length}
				</p>
				<ToolbarButton
					label="Forward"
					icon={<Dashicon icon="controls-forward" />}
					onClick={() => swiper.current?.slideNext()}
				/>
			</ToolbarGroup>

			<ToolbarGroup>{blockAppender}</ToolbarGroup>
		</BlockControls>
	);
};

export default SliderBlockNavigationControls;
