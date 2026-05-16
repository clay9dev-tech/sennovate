import classnames from 'classnames';

import { addFilter, applyFilters } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';
import { InspectorControls } from '@wordpress/block-editor';

import { responsiveVisibilityAttributes } from './attributes';
import { ResponsiveVisiblityControl } from './control';
import { getResponsiveVisibilityClassNames } from './helpers';

const BLOCK_NAME = [
	'core/group',
	'core/image',
	'core/spacer',
];

/**
 * This function is used to determine if the block should use this Control or not. By
 * filtering hasResponsiveVisiblityControl from within the theme js, you can add this to your
 * block without having to touch the plugin script.
 * @param {string} name
 */
const isBlockWithResponsiveVisibility = (name) => {
	return applyFilters('hasResponsiveVisiblityControl', BLOCK_NAME.includes(name), name);
};
addFilter(
	'blocks.registerBlockType',
	'boxShadow.attributes',
	(settings, name) => {
		if (!isBlockWithResponsiveVisibility(name)) {
			return settings;
		}

		return {
			...settings,
			attributes: {
				...settings.attributes,
				...responsiveVisibilityAttributes,
			},
		};
	}
);

addFilter(
	'editor.BlockEdit',
	'boxShadow.control',
	createHigherOrderComponent((BlockEdit) => (props) => {
		if (!isBlockWithResponsiveVisibility(props.name)) {
			return <BlockEdit {...props} />;
		}

		return (
			<>
				<BlockEdit {...props} />
				<InspectorControls>
					<ResponsiveVisiblityControl {...props} />
				</InspectorControls>
			</>
		);
	})
);

addFilter(
	'editor.BlockListBlock',
	'boxShadow.editorBlock',
	createHigherOrderComponent((BlockListBlock) => (props) => {
		if (!isBlockWithResponsiveVisibility(props.name)) {
			return <BlockListBlock {...props} />;
		}

		return (
			<BlockListBlock
				{...props}
				className={classnames(props.className, getResponsiveVisibilityClassNames(props.attributes))}
			/>
		);
	})
);

addFilter(
	'blocks.getSaveContent.extraProps',
	'boxShadow.className',
	(props, blockType, attributes) => {
		if (!isBlockWithResponsiveVisibility(blockType.name)) {
			return props;
		}

		return {
			...props,
			className: classnames(
				props.className,
				getResponsiveVisibilityClassNames(attributes)
			),
		};
	}
);
