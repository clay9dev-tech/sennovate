import classnames from "classnames";

import { InnerBlocks, useBlockProps } from "@wordpress/block-editor";
import { getAttributeClasses } from "./helpers";

const { blockEditor } = wp;

export default function save({ attributes }) {
	const { hasPagination, hasNavigation, hasScrollbar } = attributes;
	const blockProps = useBlockProps.save();
	blockProps.className = classnames(
		blockProps.className,
		getAttributeClasses(attributes)
	);

	if (attributes.slidesPerView >= 0) {
		blockProps["data-slides-per-view"] = attributes.slidesPerView;
	}

	if (attributes.spaceBetween) {
		blockProps["data-space-between"] = attributes.spaceBetween;
	}

	if (attributes.speed) {
		blockProps["data-speed"] = attributes.speed;
	}

	if (attributes.autoplay) {
		blockProps["data-autoplay-delay"] = attributes.autoplayDelay;
	}

	if (attributes.pauseOnMouseEnter) {
		blockProps["data-pause-on-mouse-enter"] = attributes.pauseOnMouseEnter;
	}

	const blockStyle = {};

	if (parseInt(attributes.slidesPerView) === 0 && attributes.slideWidth) {
		Object.assign(blockStyle, { ["--slide-width"]: attributes.slideWidth });
	}

	if (attributes.paginationMargin) {
		Object.assign(blockStyle, {
			["--pagination-margin"]: `var(--wp--preset--spacing--${attributes.paginationMargin})`,
		});
	}

	if (attributes.spaceBetween) {
		Object.assign(blockStyle, {
			["--space-between"]: `${attributes.spaceBetween}px`,
		});
	}

	blockProps.style = blockStyle;

	if (attributes.advancedSettings) {
		let advancedSettings = "";
		try {
			const parsedSettings = JSON.parse(attributes.advancedSettings);
			advancedSettings = JSON.stringify(parsedSettings);
		} catch (e) {
			/* just ignore it */
		}

		blockProps["data-advanced-settings"] = advancedSettings;
	}

	return (
		<div {...blockProps}>
			<div className="swiper">
				<div className="swiper-wrapper">
					<InnerBlocks.Content />
				</div>

				{hasPagination && <div className="swiper-pagination" />}

				{hasScrollbar && <div className="swiper-scrollbar" />}

				{hasNavigation && (
					<>
						<div className="swiper-button-prev" />
						<div className="swiper-button-next" />
					</>
				)}
			</div>
		</div>
	);
}
