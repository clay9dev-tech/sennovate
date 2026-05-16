import classnames from 'classnames';

export const getColumnsSettingsClassNames = ({
	noGutterSpace
}) => {
	return classnames({
		'columns-has-no-gutter-gap': noGutterSpace
	});
};
