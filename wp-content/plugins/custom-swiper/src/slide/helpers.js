import classnames from "classnames";

export const getAttributeClasses = ({
	verticalAlign,
}) => {
	return classnames({
		[`vertical-align-${verticalAlign}`]: verticalAlign,
	});
};
