import { applyHeadroom } from './headroom';
import { applyDarkBackground } from './dark-background';

const HEADER_ID = 'header';

function onDocumentReady() {
	const header = document.getElementById(HEADER_ID);
	if (!header) {
		return;
	}

	initialize(
		header,
		applyHeadroom,
		applyDarkBackground
	);
}

function initialize(header, ...modules) {
	modules.forEach((fn) => fn(header));
}

function mabyeHandleSearchToggle(e) {
	if (!e.target.classList.contains('search-toggle')) {
		return;
	}

	toggleSearchForm(e.target);
}

function toggleSearchForm(el){
	el.closest('.header-search').classList.toggle('activated');
}

document.addEventListener('DOMContentLoaded', onDocumentReady);
document.addEventListener('click', mabyeHandleSearchToggle);
