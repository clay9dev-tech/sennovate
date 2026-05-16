import classnames from 'classnames';

export const getButtonSettingsClassNames = ({ videoPopup }) => {
	return classnames({
		[`video-popup-modal`]: videoPopup
	});
}
