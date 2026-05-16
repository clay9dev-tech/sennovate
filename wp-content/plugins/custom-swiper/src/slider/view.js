import { addFilter, applyFilters } from "@wordpress/hooks";

const SELECTOR = ".wp-block-mandy-swiper";

function onDocumentReady() {
	const els = document.querySelectorAll(SELECTOR);

	if (!els) {
		return;
	}

	import("swiper/swiper-bundle.min.css");
	import("swiper").then((swiperModule) => {
		els.forEach((el) => initializeElement(el, swiperModule));
	});
}

export function initializeElement(element, swiperModule) {
	if (!element) {
		return null;
	}

	const swiperElement = element.querySelector(".swiper");
	if (!swiperElement) {
		return null;
	}

	const defaultConfig = {
		modules: [],
		speed: 300,
		slidesPerView: 1,
	};

	const swiperConfig = applyFilters(
		"mandySwiper.swiperConfig",
		defaultConfig,
		element,
		swiperModule
	);

	const { Swiper } = swiperModule;

	return new Swiper(swiperElement, swiperConfig);
}

document.addEventListener("DOMContentLoaded", onDocumentReady);

addFilter(
	"mandySwiper.swiperConfig",
	"mandySwiper.swiperConfig.speed",
	(swiperConfig, element) => {
		const { speed } = element.dataset;
		if (!speed) {
			return swiperConfig;
		}

		return Object.assign({}, swiperConfig, {
			speed: parseInt(speed),
		});
	}
);

addFilter(
	"mandySwiper.swiperConfig",
	"mandySwiper.swiperConfig.slidesPerView",
	(swiperConfig, element) => {
		if (!Object.keys(element.dataset).includes("slidesPerView")) {
			return swiperConfig;
		}

		const { slidesPerView } = element.dataset;
		const numSlidesPerView = parseInt(slidesPerView);

		if (!numSlidesPerView || Number.isNaN(numSlidesPerView)) {
			return Object.assign({}, swiperConfig, {
				slidesPerView: "auto",
			});
		}

		return Object.assign({}, swiperConfig, {
			slidesPerView: numSlidesPerView,
		});
	}
);

addFilter(
	"mandySwiper.swiperConfig",
	"mandySwiper.swiperConfig.spaceBetween",
	(swiperConfig, element) => {
		const { spaceBetween } = element.dataset;

		if (!spaceBetween) {
			return swiperConfig;
		}

		return Object.assign({}, swiperConfig, {
			spaceBetween: parseInt(spaceBetween),
		});
	}
);

addFilter(
	"mandySwiper.swiperConfig",
	"mandySwiper.swiperConfig.autoplay",
	(swiperConfig, element, swiperModule) => {
		if (!element.classList.contains("autoplay")) {
			return swiperConfig;
		}

		const { Autoplay } = swiperModule;
		const delay = parseInt(element.dataset.autoplayDelay);
		const pauseOnMouseEnter = element.dataset.pauseOnMouseEnter === "true";
		const disableOnInteraction = !pauseOnMouseEnter;

		return Object.assign({}, swiperConfig, {
			modules: [...swiperConfig.modules, Autoplay],
			autoplay: { delay, pauseOnMouseEnter, disableOnInteraction },
		});
	}
);

addFilter(
	"mandySwiper.swiperConfig",
	"mandySwiper.swiperConfig.loop",
	(swiperConfig, element) => {
		if (!element.classList.contains("infinite")) {
			return swiperConfig;
		}

		return Object.assign({}, swiperConfig, { loop: true });
	}
);

addFilter(
	"mandySwiper.swiperConfig",
	"mandySwiper.swiperConfig.autoHeight",
	(swiperConfig, element) => {
		if (!element.classList.contains("autoheight-slides")) {
			return swiperConfig;
		}

		return Object.assign({}, swiperConfig, { autoHeight: true });
	}
);

addFilter(
	"mandySwiper.swiperConfig",
	"mandySwiper.swiperConfig.centeredSlides",
	(swiperConfig, element) => {
		if (!element.classList.contains("centered-slides")) {
			return swiperConfig;
		}

		return Object.assign({}, swiperConfig, { centeredSlides: true });
	}
);

addFilter(
	"mandySwiper.swiperConfig",
	"mandySwiper.swiperConfig.navigation",
	(swiperConfig, element, swiperModule) => {
		if (!element.classList.contains("has-navigation")) {
			return swiperConfig;
		}

		const { Navigation } = swiperModule;
		return Object.assign({}, swiperConfig, {
			modules: [...swiperConfig.modules, Navigation],
			navigation: {
				nextEl: ".swiper-button-next",
				prevEl: ".swiper-button-prev",
			},
		});
	}
);

addFilter(
	"mandySwiper.swiperConfig",
	"mandySwiper.swiperConfig.pagination",
	(swiperConfig, element, swiperModule) => {
		if (!element.classList.contains("has-pagination")) {
			return swiperConfig;
		}

		const { Pagination } = swiperModule;
		return Object.assign({}, swiperConfig, {
			modules: [...swiperConfig.modules, Pagination],
			pagination: {
				el: ".swiper-pagination",
				clickable: true,
			},
		});
	}
);

addFilter(
	"mandySwiper.swiperConfig",
	"mandySwiper.swiperConfig.scrollbar",
	(swiperConfig, element, swiperModule) => {
		if (!element.classList.contains("has-scrollbar")) {
			return swiperConfig;
		}

		const { Scrollbar } = swiperModule;
		return Object.assign({}, swiperConfig, {
			modules: [...swiperConfig.modules, Scrollbar],
			scrollbar: {
				el: ".swiper-scrollbar",
				draggable: true,
			},
		});
	}
);

addFilter(
	"mandySwiper.swiperConfig",
	"mandySwiper.swiperConfig.fade",
	(swiperConfig, element, swiperModule) => {
		if (!element.classList.contains("has-effect-fade")) {
			return swiperConfig;
		}

		const { EffectFade } = swiperModule;

		return Object.assign({}, swiperConfig, {
			modules: [...swiperConfig.modules, EffectFade],
			effect: "fade",
		});
	}
);

addFilter(
	"mandySwiper.swiperConfig",
	"mandySwiper.swiperConfig.dispatchInit",
	(swiperConfig, element) => {
		return Object.assign({}, swiperConfig, {
			on: {
				...swiperConfig.on,
				afterInit: function () {
					element.dispatchEvent(
						new CustomEvent("mandy-swiper-initialized", {
							detail: { swiper: this },
						})
					);
				},
			},
		});
	}
);
