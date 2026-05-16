import { debounce } from '../helpers';

function onClickSubMenuToggle(e, subMenuToggle) {
	const menuItem = subMenuToggle.closest('.menu-item');
	if (!menuItem) {
		return;
	}

	const subMenu = menuItem.querySelector('.sub-menu');
	if (!subMenu) {
		return;
	}

	let aria = subMenuToggle.getAttribute('aria-expanded');
	if (!aria || aria === 'false') {
		aria = 'true';
	} else {
		aria = 'false';
	}
	subMenuToggle.setAttribute('aria-expanded', aria);
	subMenu.classList.toggle('active');
	return false;
}

function onDocumentClick(e) {
	const subMenuToggle = e.target.closest('.sub-menu-toggle');
	if (!subMenuToggle) {
		return;
	}

	e.preventDefault();
	return onClickSubMenuToggle(e, subMenuToggle);
}

function onWindowResize() {
	const activeSubMenus = document.querySelectorAll('.sub-menu.active');
	activeSubMenus.forEach((s) => {
		s.classList.remove('active');
	});
}

document.addEventListener('click', onDocumentClick);
window.addEventListener('resize', debounce(onWindowResize, 100));
