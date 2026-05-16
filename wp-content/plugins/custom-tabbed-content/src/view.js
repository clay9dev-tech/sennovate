import { doAction } from '@wordpress/hooks';

const SELECTOR = '.wp-block-mandy-tabbed-content';

function onDocumentReady() {
	const els = document.querySelectorAll(SELECTOR);

	if (!els) {
		return;
	}

	els.forEach(initializeElement);
}

const getPanelForTab = (tab) => {
	const el = tab.closest(SELECTOR);
	const tabIndex = el.tabs.indexOf(tab);
	return el.panels[tabIndex];
}

function initializeElement(element, n) {
	element.addEventListener('click', (e) => {
		const tab = e.target.closest(`${SELECTOR} .tabbed-content__tab`);
		if (!tab) {
			return;
		}

		setActiveTab(element, tab);
	});

	const id = element.id || `tabbed-content-${n}`;

	const tabs = [...element.querySelectorAll('.tabbed-content__tab')];
	tabs.forEach((tab, t) => {
		tab.id = `${id}-tab-${t}`;
		tab.setAttribute('aria-controls', `${id}-panel-${t}`);
	});

	const panels = [...element.querySelectorAll('.tabbed-content__content [role="tabpanel"]')];
	panels.forEach((panel, p) => {
		panel.id = `${id}-panel-${p}`;
		panel.setAttribute('aria-labelledby', `${id}-tab-${p}`);
	})
	Object.assign(element, { tabs, panels });

	const firstTab = tabs[0];
	setActiveTab(element, firstTab);
	element.classList.add('initialized');
}

function setActiveTab(tabbedContent, tab) {
	if (tabbedContent.activeTab && tabbedContent.activeTab !== tab) {
		deactivateTabbedContentTab(tabbedContent, tabbedContent.activeTab);
	}

	activateTabbedContentTab(tabbedContent, tab);
}

function deactivateTabbedContentTab(tabbedContent, tab) {
	doAction('tabbedContent.beforeDeactivateTab', tab, tabbedContent);

	tab.classList.remove('active');
	tab.tabIndex = -1;
	tab.setAttribute('aria-selected', false);

	getPanelForTab(tab).classList.remove('active');

	tabbedContent.activeTab = null;
	tabbedContent.activeContent = null;

	doAction('tabbedContent.afterDeactivateTab', tab, tabbedContent);
}

function activateTabbedContentTab(tabbedContent, tab) {
	const content = getPanelForTab(tab);

	doAction('tabbedContent.beforeActivateTab', tab, tabbedContent);

	tab.classList.add('active');
	tab.tabIndex = 0;
	tab.setAttribute('aria-selected', true);
	content.classList.add('active');

	tabbedContent.activeTab = tab;
	tabbedContent.activeContent = content;

	doAction('tabbedContent.afterActivateTab', tab, tabbedContent);
}

document.addEventListener('DOMContentLoaded', onDocumentReady);
