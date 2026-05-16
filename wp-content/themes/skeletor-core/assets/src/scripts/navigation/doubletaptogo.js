import { applyFilters } from '@wordpress/hooks';
import 'jquery-doubletaptogo';

const MENU_SELECTOR = '#main-menu';
const SELECTOR_CHAIN = 'li.menu-item-has-children:has(ul)';

function onDocumentReady() {
	const { jQuery } = window;
	if (!jQuery) {
		return;
	}

	const menuSelector = applyFilters('skeletor.doubleTapToGo.menuSelector', MENU_SELECTOR);
	const selectorChain = applyFilters('skeletor.doubleTapToGo.selectorChain', SELECTOR_CHAIN);

	jQuery(menuSelector).doubleTapToGo({ selectorChain });
}

document.addEventListener('DOMContentLoaded', onDocumentReady);

