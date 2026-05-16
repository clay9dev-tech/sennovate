// This file is part of the Acqueon theme for WordPress.
// It initializes a Swiper slider for career slides on the careers page.
import $ from 'jquery';

function onDocumentReady() {
	if ($('.career-grid-slider').length > 0) {
		var swiper = new Swiper(".career-grid-slider", {
			loop: true,
			slidesPerView: 1,
			spaceBetween: 30,
			autoHeight: true,
			pagination: {
				el: ".swiper-pagination",
				clickable: true,
			},
			autoplay: {
				delay: 5000,
				disableOnInteraction: false,
			}
		});

		// Pause autoplay on hover
		swiper.el.addEventListener('mouseenter', function () {
			swiper.autoplay.stop();  // Stop autoplay when mouse enters
		});

		// Resume autoplay when mouse leaves
		swiper.el.addEventListener('mouseleave', function () {
			swiper.autoplay.start();  // Resume autoplay when mouse leaves
		});

	}
}

document.addEventListener('DOMContentLoaded', onDocumentReady);
