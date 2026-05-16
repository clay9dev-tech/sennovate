/**
 * Scroll to the Element by passing ID
 * @param {String} elementId
 */

export function scrollToElementById(elementId) {
	if (!elementId) {
		return false;
	}

	if (elementId.substring(0, 1) === '#') {
		elementId = elementId.substring(1);
	}

	const scrollTo = document.getElementById(elementId);
	scrollToElement(scrollTo);
}

/**
 * Scroll to top
 */
export function scrollToTop() {
	scrollToElement(document.body);
}

/**
 * Scroll to Element
 * @param {Object} scrollTo
 */
function scrollToElement(scrollTo) {
	if (!scrollTo) {
		return false;
	}

	scrollTo.scrollIntoView({ behavior: 'smooth' });
}

/**
 * When Anchor tag is clicked
 * @param {Object} e
 */

function onLinkClicked(e) {
	const clicked = e.target;
	const target = clicked.hash;

	if (!target) {
		return false;
	}

	if ("ignoreAnchorScroll" in clicked.dataset) {
		return false;
	}

	if (clicked.hostname !== window.location.hostname) {
		return false;
	}

	if (clicked.pathname !== window.location.pathname) {
		return false;
	}

	if (target) {
		e.preventDefault();
		scrollToElementById(target);
	}
}

/**
 * Check for clicking on anchor tag
 * @param {Object} e
 */

function onDocumentClick(e) {
	if (e.target.tagName.toLowerCase() !== 'a') {
		return false;
	}

	onLinkClicked(e);
}

/**
 * When URL hash changes
 */

function onHashChange() {
	const target = window.location.hash;
	if (!target) {
		return false;
	}

	scrollToElementById(target);
}

document.addEventListener('click', onDocumentClick);
window.addEventListener('hashchange', onHashChange);
