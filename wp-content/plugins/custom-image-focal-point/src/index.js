import { focalPointAttributes } from './attributes';
import { FocalPointControl } from './control';
import { recursiveMap } from './helpers';

const { addFilter, applyFilters } = wp.hooks;
const { createHigherOrderComponent } = wp.compose;
const { cloneElement, Children } = wp.element;
const { InspectorControls } = wp.blockEditor;

const BLOCKS = ['core/image'];

const isBlockWithFocalPoint = (name) => {
	return applyFilters(
		'hasFocalPointControl',
		BLOCKS.includes(name),
		name
	);
};

addFilter('blocks.registerBlockType', 'focalPoint', (settings, name) => {
	if (!isBlockWithFocalPoint(name)) {
		return settings;
	}

	return {
		...settings,
		attributes: {
			...settings.attributes,
			...focalPointAttributes,
		},
	};
});

addFilter(
	'blocks.getSaveElement',
	'focalPoint',
	(element, block, attributes) => {
		if (!isBlockWithFocalPoint(block.name)) {
			return element;
		}

		const { focalPoint } = attributes;

		if (focalPoint) {
			const cloneChildren = recursiveMap(element.props.children, child => {
				if (child?.type === 'img') {
					return cloneElement(child, {
						style: {
							objectPosition: `${focalPoint.x * 100}% ${focalPoint.y * 100}%`,
						},
					});
				}

				return child;
			});

			const ret = cloneElement(element, {}, cloneChildren);

			return ret;
		}

		return element;
	}
);

addFilter(
	'editor.BlockEdit',
	'focalPoint',
	createHigherOrderComponent((BlockEdit) => (props) => {
		if (!isBlockWithFocalPoint(props.name)) {
			return <BlockEdit {...props} />;
		}

		return (
			<>
				<BlockEdit {...props} />
				<InspectorControls>
					<FocalPointControl {...props} />
				</InspectorControls>
			</>
		);
	})
);
