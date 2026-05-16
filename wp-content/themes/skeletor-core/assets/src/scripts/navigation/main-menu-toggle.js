import { debounce } from '../helpers';

function onClickMainMenuToggle() {
	document.body.classList.toggle('nav-open');

	if (!document.body.classList.contains('nav-open')) {
		const activeSubMenus = document.querySelectorAll('.sub-menu.active');
		activeSubMenus.forEach((s) => s.classList.remove('active'));
	}

	return false;
}

function onDocumentClick(e) {
	const mainMenuToggle = e.target.closest('.main-menu-toggle');
	if (!mainMenuToggle) {
		return;
	}

	e.preventDefault();
	return onClickMainMenuToggle(e, mainMenuToggle);
}

function onWindowResize() {
	document.body.classList.remove('nav-open');
}

document.addEventListener('click', onDocumentClick);
window.addEventListener('resize', debounce(onWindowResize, 100));
