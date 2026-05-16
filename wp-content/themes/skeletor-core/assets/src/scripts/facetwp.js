/**
 * prevent the default action
 * on link clicks for facetwp-page <a> tags
 *
 * by default facetwp does not include hrefs
 * and handles the navigation via javascript,
 * but since we're adding hrefs for SEO/indexing
 * we need to prevent the default click handling
 *
 * @param {*} e
 * @returns
 */
function onDocumentClick(e) {
	const { FWP } = window;

	if (typeof FWP === 'undefined' || !FWP) {
		return;
	}
	if (e.target.tagName.toLowerCase() !== 'a') {
		return;
	}

	if (!e.target.classList.contains('facetwp-page')) {
		return;
	}

	if (!e.target.href || e.target.href == '#') {
		return;
	}

	e.preventDefault();
}

document.addEventListener('click', onDocumentClick);
