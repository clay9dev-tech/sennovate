import { addFilter } from "@wordpress/hooks";
const TARGET_CLASSNAME = "wp-block-mandy-swiper";

/**
 * check if the initialized swiper parent
 * is a wp-block-mandy-swiper and if so
 * checks ITS parentNode for negating classnames: alignwide, alignleft, or alignright
 * if any of those DO NOT exist that parentNode is tagged with
 * the className has-wp-block-mandy-swiper
 *
 * @param {*} event
 * @returns
 */

function onAdvancedSetting(swiperConfig, element) {
    const { advancedSettings } = element.dataset;

    if (!advancedSettings) {
        return swiperConfig;
    }

    swiperConfig = Object.assign({}, swiperConfig, {
        breakpoints: JSON.parse(advancedSettings),
    });

    return swiperConfig;
}

function onSwiperInitialized(event) {
	if(!event.detail || !event.detail.swiper) {
		return;
	}

	const { swiper } = event.detail;
	const wrapper = swiper.el.parentElement;

	if (!wrapper.classList.contains(TARGET_CLASSNAME)) {
		return;
	}

	/**
	 * if the wrapper has any of the following
	 * negatingClassnames, then we bail
	 */
	const negatingClassnames = ['alignleft', 'alignright'];
	if ([...wrapper.classList].some(className => negatingClassnames.indexOf(className) !== -1)) {
		return;
	}

	wrapper.parentElement.classList.add(`has-${TARGET_CLASSNAME}`);
}

function onDocumentReady() {
	const els = document.querySelectorAll(`.${TARGET_CLASSNAME}`);

	if (!els) {
		return;
	}

	els.forEach((el) => {
        addFilter(
            "mandySwiper.swiperConfig",
            "mandySwiper.swiperConfig.advancedSettings",
            onAdvancedSetting,
        );
		addFilter(
			"mandySwiper.swiperConfig",
			"mandySwiper.swiperConfig.ally",
			(swiperConfig, element, swiperModule) => {
		
				const { A11y } = swiperModule;
		
				return Object.assign({}, swiperConfig, {
					modules: [...swiperConfig.modules, A11y],
					a11y: true,
				});
			}
		);
        el.addEventListener('mandy-swiper-initialized', onSwiperInitialized);
    });
}

document.addEventListener('DOMContentLoaded', onDocumentReady);
