import classnames from 'classnames';

export const getResponsiveVisibilityClassNames = ({ hideOnMobile, hideOnDesktop }) => {
	return classnames({
		'hide-on-mobile': hideOnMobile,
		'hide-on-desktop': hideOnDesktop,
	});
};
