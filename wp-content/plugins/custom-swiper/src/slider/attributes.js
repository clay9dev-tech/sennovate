const attributes = {
	autoplay: { type: "bool" },
	autoplayDelay: { type: "int", default: 5000 },
	speed: { type: "int", default: 300 },
	infinite: { type: "bool" },
	hasNavigation: { type: "bool" },
	hasScrollbar: { type: 'bool' },
	scrollbarMobileOnly: { type: "bool" },
	verticalAlign: { type: "string" },
	stretchContentHeight: { type: "bool" },
	slidesPerView: { type: "int", default: 1 },
	spaceBetween: { type: "int", default: 0 },
	advancedSettings: { type: "string" },
	pauseOnMouseEnter: { type: "bool" },
	autoHeight: { type: "bool" },
	centeredSlides: { type: "bool" },
	slideWidth: { type: "string" },
	timingFunction: {
		enum: ["ease", "ease-in", "ease-out", "ease-in-out", "linear"],
		default: "ease",
	},
	hasOverflowVisible: { type: "bool", default: false },
	hasPagination: { type: "bool" },
	paginationPosition: {
		enum: ["absolute", "static"],
		default: "absolute",
	},
	paginationMargin: { type: "string" },
	navigationPadding: { type: "bool" },
	navigationInset: { type: "bool" },
	effect: {
		enum: ["none", "fade"],
		default: "none",
	},
	crossfade: {
		type: "bool",
	},
};

export default attributes;
