import classnames from 'classnames';

export const getImageClassNames = ({ forceFullWidth }) => {
	return classnames({
		'force-full-width': forceFullWidth,
	});
};
