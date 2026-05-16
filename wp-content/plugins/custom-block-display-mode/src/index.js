import classnames from 'classnames/dedupe';

import { DisplayModeControl } from './display-mode-control';
import { displayModeAttributes } from './attributes';
import { getDisplayModeClassName } from './helpers';

const { addFilter, applyFilters } = wp.hooks;
const { createHigherOrderComponent } = wp.compose;
const { Fragment } = wp.element;
const { InspectorControls } = wp.blockEditor;

const BLOCKS = applyFilters('mandyBlockDisplayMode.defaultBlocksEffected', [
	'core/group'
]);

/**
 * applicable options = settings (or default) or styles
 */
const INSPECTOR_CONTROL_GROUP = 'settings'; // set to styles to show on the ... styles group

const isBlockWithDisplayMode = (name) => {
	return applyFilters('mandyBlockDisplayMode.hasDisplayMode', BLOCKS.includes(name), name);
};

addFilter(
	'blocks.registerBlockType',
	'mandyBlockDisplayMode',
	(settings, name) => {
		if (!isBlockWithDisplayMode(name)) {
			return settings;
		}

		return {
			...settings,
			attributes: {
				...settings.attributes,
				...displayModeAttributes,
			},
		};
	}
);

addFilter(
	'blocks.getSaveContent.extraProps',
	'mandyBlockDisplayMode',
	(props, blockType, attributes) => {
		if (!isBlockWithDisplayMode(blockType.name)) {
			return props;
		}

		const ret = {
			...props,
			className: classnames(
				props.className,
				getDisplayModeClassName(attributes)
			),
		};

		return ret;
	}
);

addFilter(
	'editor.BlockEdit',
	'mandyBlockDisplayMode',
	createHigherOrderComponent((BlockEdit) => (props) => {
		if (!isBlockWithDisplayMode(props.name)) {
			return <BlockEdit {...props} />;
		}

		const blockEditProps = {
			...props,
			className: classnames(
				props.className,
				getDisplayModeClassName(props.attributes)
			),
		};

		return (
			<Fragment>
				<BlockEdit {...blockEditProps} />
				<InspectorControls group={INSPECTOR_CONTROL_GROUP}>
					<DisplayModeControl {...props} />
				</InspectorControls>
			</Fragment>
		);
	})
);

addFilter(
	'editor.BlockListBlock',
	'mandyBlockDisplayMode',
	createHigherOrderComponent((BlockListBlock) => (props) => {
		const { name } = props;

		if (!isBlockWithDisplayMode(name)) {
			return <BlockListBlock {...props} />;
		}

		const blockListProps = {
			...props,
			className: classnames(
				props.className,
				getDisplayModeClassName(props.attributes)
			),
		};

		return <BlockListBlock {...blockListProps} />;
	})
);
