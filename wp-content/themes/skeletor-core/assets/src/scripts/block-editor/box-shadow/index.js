import classnames from 'classnames';

import { addFilter, applyFilters } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';
import { InspectorControls } from '@wordpress/block-editor';

import { boxShadowAttributes } from './attributes';
import { BoxShadowControl } from './control';
import { getBoxShadowClassNames } from './helpers';

const BLOCK_NAME = ['core/group', 'core/image'];

/**
 * This function is used to determine if the block should use this Control or not. By
 * filtering hasBoxShadowControl from within the theme js, you can add this to your
 * block without having to touch the plugin script.
 * @param {string} name
 */
const isBlockWithBoxShadow = (name) => {
	return applyFilters('hasBoxShadowControl', BLOCK_NAME.includes(name), name);
};
addFilter(
	'blocks.registerBlockType',
	'boxShadow.attributes',
	(settings, name) => {
		if (!isBlockWithBoxShadow(name)) {
			return settings;
		}

		return {
			...settings,
			attributes: {
				...settings.attributes,
				...boxShadowAttributes,
			},
		};
	}
);

addFilter(
	'editor.BlockEdit',
	'boxShadow.control',
	createHigherOrderComponent((BlockEdit) => (props) => {
		if (!isBlockWithBoxShadow(props.name)) {
			return <BlockEdit {...props} />;
		}

		return (
			<>
				<BlockEdit {...props} />
				<InspectorControls>
					<BoxShadowControl {...props} />
				</InspectorControls>
			</>
		);
	})
);

addFilter(
	'editor.BlockListBlock',
	'boxShadow.editorBlock',
	createHigherOrderComponent((BlockListBlock) => (props) => {
		if (!isBlockWithBoxShadow(props.name)) {
			return <BlockListBlock {...props} />;
		}

		return (
			<BlockListBlock
				{...props}
				className={classnames(props.className, getBoxShadowClassNames(props.attributes))}
			/>
		);
	})
);

addFilter(
	'blocks.getSaveContent.extraProps',
	'boxShadow.className',
	(props, blockType, attributes) => {
		if (!isBlockWithBoxShadow(blockType.name)) {
			return props;
		}

		return {
			...props,
			className: classnames(
				props.className,
				getBoxShadowClassNames(attributes)
			),
		};
	}
);
