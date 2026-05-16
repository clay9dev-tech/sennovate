import classnames from 'classnames';

export const getDisplayModeClassName = (attributes) => {
	const { displayModeStyle } = attributes;

	return classnames({
		[`is-style-${displayModeStyle}`]: displayModeStyle,
	});
};
