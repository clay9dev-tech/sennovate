import classnames from 'classnames';

export const getButtonSettingsClassNames = ({ size }) => {
	return classnames({
		[`size-${size}`]: size
	});
}
