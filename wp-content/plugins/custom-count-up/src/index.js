/* globals CustomEvent */
const { IntersectionObserver } = window;

const SELECTOR = '.count-up';

function onDocumentReady() {
	if (!IntersectionObserver) {
		return;
	}

	const els = document.querySelectorAll(SELECTOR);
	if (els.length === 0) {
		return;
	}

	const observer = new IntersectionObserver(
		(entries) => onObserveIntersection(entries, observer),
		{
			threshold: 0.1,
		}
	);

	import('animejs/lib/anime.es.js').then((module) => {
		const anime = module.default;

		[...els].forEach((el) => initializeElement(el, anime, observer));
	});
}

function initializeElement(el, anime, observer) {
	const text = el.textContent;
	const countTo = parseFloat(text.replace(/[^0-9.]/, ''));

	if (!(text && countTo)) {
		return;
	}

	const countUp = { value: 0 };

	const animeConfig = {
		targets: countUp,
		value: countTo,
		round: getSignificantDigits(countTo),
		easing: 'easeInQuad',
		begin: () => {
			el.parentNode.dataset.countTo = countTo;
			el.classList.add('entered');
		},
		update: () => {
			el.textContent = countUp.value;
		},
	};

	if (text.match(',') || el.classList.contains('has-commas')) {
		Object.assign(animeConfig, {
			update: () => {
				el.textContent = formatCount(countUp.value);
			},
		});
	}

	el.anime = anime(animeConfig);
	observer.observe(el);
}

function onObserveIntersection(entries, observer) {
	entries.forEach((entry) => {
		if (entry.isIntersecting) {
			observer.unobserve(entry.target);
			onElementEnter(entry.target);
		}
	});
}

function formatCount(n) {
	return n
		.toString()
		.replace(/\B(?=(\d{3})+(?!\d))/g, ',')
		.trim();
}

function getSignificantDigits(n) {
	const splitNumber = n.toString().split('.');
	if (splitNumber.length === 2) {
		return Math.pow(10, splitNumber[1].length);
	}

	return 1;
}

function onElementEnter(el) {
	el.anime?.play();
}

document.addEventListener('DOMContentLoaded', onDocumentReady);
