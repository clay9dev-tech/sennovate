import classnames from 'classnames';

export const getHeadingSettingsClassNames = ({ displayAs }) => {
	return classnames({
		[displayAs]: displayAs
	});
}
