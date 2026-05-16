function onClickGoBack(e, goBack) {
	const activeSubMenu = goBack.closest('.sub-menu.active');
	if (!activeSubMenu) {
		return;
	}

	activeSubMenu.classList.remove('active');
	return false;
}

function onDocumentClick(e) {
	const goBack = e.target.closest('.go-back');
	if (!goBack) {
		return;
	}

	e.preventDefault();
	return onClickGoBack(e, goBack);
}

document.addEventListener('click', onDocumentClick);
