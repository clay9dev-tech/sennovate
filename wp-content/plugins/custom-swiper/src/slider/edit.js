import classnames from "classnames";
import { __ } from "@wordpress/i18n";

import {
	useBlockProps,
	useInnerBlocksProps,
	InnerBlocks,
} from "@wordpress/block-editor";
import { useState, useEffect, useRef } from "@wordpress/element";
import { useSelect } from "@wordpress/data";
import { addFilter } from "@wordpress/hooks";

import "./editor.scss";
import "swiper/swiper-bundle.min.css";

import * as SwiperModule from "swiper";
import SliderBlockControls from "./block-controls";
import SliderBlockNavigationControls from "./block-navigation-controls";
import SliderInspectorControls from "./inspector-controls";
import { getAttributeClasses } from "./helpers";

import { initializeElement } from "./view";

export default function Edit(props) {
	const { attributes, clientId } = props;
	const {
		autoplay,
		autoplayDelay,
		speed,
		slidesPerView,
		spaceBetween,
		hasNavigation,
		hasPagination,
		hasScrollbar,
		advancedSettings,
		pauseOnMouseEnter,
		autoHeight,
		centeredSlides,
		slideWidth,
		paginationMargin,
	} = attributes;

	const innerBlockProps = useInnerBlocksProps(
		{},
		{
			allowedBlocks: ["mandy/slider--slide"],
			orientation: "horizontal",
			renderAppender: () => false,
		}
	);

	const blockEditor = useSelect(s => s('core/block-editor'));

	const innerBlocks = blockEditor.getBlock(clientId).innerBlocks;
	const slideCount = innerBlocks?.length;
	const selectedBlock = blockEditor.getSelectedBlock();

	const swiperContainer = useRef(null);
	const swiperInstance = useRef(null);

	const [activeSlideIndex, setActiveSlideIndex] = useState(0);
	const [isPlaying, setIsPlaying] = useState(autoplay);

	const blockProps = useBlockProps({
		ref: swiperContainer,
		["data-slides-per-view"]: slidesPerView,
		["data-space-between"]: spaceBetween,
		["data-speed"]: speed,
		["data-advanced-settings"]: advancedSettings,
		["data-autoplay-delay"]: autoplayDelay,
		["data-pause-on-mouse-enter"]: pauseOnMouseEnter,
		style: {
			["--space-between"]: `${spaceBetween}px`,
			["--slide-width"]: slideWidth,
			["--pagination-margin"]: `var(--wp--preset--spacing--${paginationMargin})`,
		},
	});

	if (!slideCount) {
		blockProps.className = `${blockProps.className} no-slides`;
	}

	blockProps.className = classnames(
		blockProps.className,
		getAttributeClasses(attributes)
	);

	const regenerateSwiper = () => {
		swiperInstance.current?.destroy();
		swiperInstance.current = initializeElement(
			swiperContainer.current,
			SwiperModule
		);

		swiperInstance.current.on("slideChange", (s) => {
			setActiveSlideIndex(s.activeIndex);
		});

		swiperInstance.current.on("autoplayStop", (s) => {
			setIsPlaying(false);
		});

		swiperInstance.current.on("autoplayStart", (s) => {
			setIsPlaying(true);
		});
	};

	useEffect(() => {
		const slideIndex = Array.from(innerBlocks).findIndex((b) =>
			b?.clientId === selectedBlock?.clientId
		);

		if (slideIndex >= 0) {
			swiperInstance.current?.slideTo(slideIndex);
		}
	}, [selectedBlock]);

	useEffect(() => {
		regenerateSwiper();
	}, [
		slidesPerView,
		spaceBetween,
		hasNavigation,
		hasPagination,
		hasScrollbar,
		autoplay,
		autoplayDelay,
		speed,
		autoHeight,
		centeredSlides,
		slideWidth,
		slideCount,
	]);

	return (
		<div {...blockProps}>
			<SliderBlockControls {...props} />
			<SliderBlockNavigationControls
				{...props}
				autoplay={autoplay}
				isPlaying={isPlaying}
				swiper={swiperInstance}
				blockAppender={<InnerBlocks.ButtonBlockAppender />}
			/>
			<SliderInspectorControls {...props} />

			<div {...innerBlockProps}>
				<div className="swiper">
					<div className="swiper-wrapper">{innerBlockProps.children}</div>

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
		</div>
	);
}

addFilter(
	"mandySwiper.swiperConfig",
	"mandySwiper.swiperConfig.editorOverrides",
	(swiperConfig) => {
		return Object.assign({}, swiperConfig, {
			allowTouchMove: false,
			loop: false,
		});
	}
);