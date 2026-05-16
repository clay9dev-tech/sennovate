const { Children, cloneElement } = wp.element;

export const recursiveMap = (children, fn) => Children.map(children, child => {
	if (child?.props.children) {
		child = cloneElement(child, {
			children: recursiveMap(child.props.children, fn)
		});
	}

	return fn(child);
});
