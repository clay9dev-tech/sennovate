import classnames from 'classnames';

import { addFilter } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';
import { InspectorControls } from '@wordpress/block-editor';

import { imageAttributes } from './attributes';
import { ImageSettingsControl } from './control';
import { getImageClassNames } from './helpers';

const BLOCK_NAME = 'core/image';

addFilter(
	'blocks.registerBlockType',
	'imageSettings.attributes',
	(settings, name) => {
		if (name !== BLOCK_NAME) {
			return settings;
		}

		return {
			...settings,
			attributes: {
				...settings.attributes,
				...imageAttributes,
			},
		};
	}
);

addFilter(
	'editor.BlockEdit',
	'imageSettings.control',
	createHigherOrderComponent((BlockEdit) => (props) => {
		if (props.name !== BLOCK_NAME) {
			return <BlockEdit {...props} />;
		}

		return (
			<>
				<BlockEdit {...props} />
				<InspectorControls>
					<ImageSettingsControl {...props} />
				</InspectorControls>
			</>
		);
	})
);

addFilter(
	'editor.BlockListBlock',
	'imageSettings.editorBlock',
	createHigherOrderComponent((BlockListBlock) => (props) => {
		if (props.name !== BLOCK_NAME) {
			return <BlockListBlock {...props} />;
		}

		return (
			<BlockListBlock
				{...props}
				className={classnames(
					props.className,
					getImageClassNames(props.attributes)
				)}
			/>
		);
	})
);

addFilter(
	'blocks.getSaveContent.extraProps',
	'imageSettings.className',
	(props, blockType, attributes) => {
		if (blockType.name !== BLOCK_NAME) {
			return props;
		}

		return {
			...props,
			className: classnames(
				props.className,
				getImageClassNames(attributes)
			),
		};
	}
);
