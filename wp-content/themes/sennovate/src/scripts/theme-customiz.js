function onDocumentReady() {

	const closeBtn = document.querySelector(".announcement-close");
	const announcementBar = document.querySelector(".header-announcement");
	const header = document.querySelector(".header");

	if (closeBtn && announcementBar) {
		closeBtn.addEventListener("click", function () {
			announcementBar.style.transition = "opacity 0.4s ease";
			announcementBar.style.opacity = "0";
			announcementBar.classList.toggle("hide-bar");
			header.classList.toggle("no-bar");
			setTimeout(() => {
				announcementBar.style.display = "none";
				header.style.top = "0px";
			}, 400);
		});
	}

	const swiper = new Swiper('.upcoming-events-slider', {
		slidesPerView: 1,
		spaceBetween: 30,
		effect: "fade",
		navigation: {
			nextEl: '.swiper-button-next',
			prevEl: '.swiper-button-prev',
		},
		loop: true,
		autoHeight: true,
	});

	const eventsSwiper = new Swiper('.events-slider', {
		slidesPerView: 3,
		spaceBetween: 32,
		navigation: {
			nextEl: '.swiper-button-next',
			prevEl: '.swiper-button-prev',
		},
		autoplay: {
			delay: 5000,
		},
		loop: true,
		autoHeight: true,

		breakpoints: {
			0: {
				slidesPerView: 1.2,
				spaceBetween: 16,
			},
			640: {
				slidesPerView: 2,
			},
			1024: {
				slidesPerView: 3,
			},
		},
	});

}

document.addEventListener('DOMContentLoaded', onDocumentReady);
