import classnames from 'classnames';

export const getColumnsSettingsClassNames = ({
	dividers,
	responsiveColumns,
	reverseStackingOrder,
	justification,
	equalHeight,
	removeSideMargins,
	align
}) => {
	return classnames({
		'reverse-stacking-order': reverseStackingOrder,
		dividers,
		'has-responsive-columns': responsiveColumns,
		[`is-content-justification-${justification}`]: justification,
		'has-equal-height-columns': equalHeight,
		'remove-side-margins': removeSideMargins && align === 'full'
	});
};
