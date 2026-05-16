import { isDarkColor } from './helpers';

export function applyDarkBackground(header) {
	const firstBlock = document.getElementById('main')?.children[0];
	if (!firstBlock) {
		return;
	}

	const backgroundColor = window.getComputedStyle(firstBlock).backgroundColor;
	if (isDarkColor(backgroundColor)) {
		header.classList.add('dark-background');
	}
}
