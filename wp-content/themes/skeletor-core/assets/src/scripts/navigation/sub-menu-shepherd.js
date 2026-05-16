import { debounce } from '../helpers';

function onDocumentReady() {
	const subMenus = document.querySelectorAll('.sub-menu');
	if (!subMenus) {
		return;
	}

	constrainSubMenus(subMenus);

	window.addEventListener(
		'resize',
		debounce(() => {
			constrainSubMenus(subMenus);
		}, 100)
	);
}

function constrainSubMenus(subMenus) {
	const main = document.getElementById('main');
	const gutterWidth = getComputedStyle(main).paddingLeft;
	const leftEdge = parseInt(gutterWidth);
	const rightEdge = window.innerWidth - leftEdge;
	const contentWidth = window.innerWidth - 2 * leftEdge;

	subMenus.forEach((submenu) => {
		const bounds = submenu.getBoundingClientRect();
		const offset =
			parseInt(submenu.style.getPropertyValue('--offsetX')) || 0;

		if (bounds.width > contentWidth) {
			return;
		}

		const rightEdgeOverlap = Math.max(0, bounds.right - offset - rightEdge);
		submenu.style.setProperty('--offsetX', `${-1 * rightEdgeOverlap}px`);
	});
}

document.addEventListener('DOMContentLoaded', onDocumentReady);
