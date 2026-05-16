import classnames from 'classnames';

export const getBoxShadowClassNames = ({ boxShadow }) => {
	return classnames({
		[`has-${boxShadow}-box-shadow`]: boxShadow,
	});
};
