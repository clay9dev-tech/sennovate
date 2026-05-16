import classnames from 'classnames';

import { addFilter } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';
import { InspectorControls } from '@wordpress/block-editor';

import { buttonSetAttributes } from './attributes';
import { ButtonSettingsControl } from './control';
import { getButtonSettingsClassNames } from './helpers';

const BLOCK_NAME = 'core/buttons';

addFilter(
	'blocks.registerBlockType',
	'buttonSettings.attributes',
	(settings, name) => {
		if (name !== BLOCK_NAME) {
			return settings;
		}

		return {
			...settings,
			attributes: {
				...settings.attributes,
				...buttonSetAttributes,
			},
		};
	}
);

addFilter(
	'editor.BlockEdit',
	'buttonSettings.control',
	createHigherOrderComponent((BlockEdit) => (props) => {
		if (props.name !== BLOCK_NAME) {
			return <BlockEdit {...props} />;
		}

		return (
			<>
				<BlockEdit {...props} />
				<InspectorControls>
					<ButtonSettingsControl {...props} />
				</InspectorControls>
			</>
		);
	})
);

addFilter(
	'editor.BlockListBlock',
	'buttonSettings.editorBlock',
	createHigherOrderComponent((BlockListBlock) => (props) => {
		if (props.name !== BLOCK_NAME) {
			return <BlockListBlock {...props} />;
		}

		return (
			<BlockListBlock
				{...props}
				className={getButtonSettingsClassNames(props.attributes)}
			/>
		);
	})
);

addFilter(
	'blocks.getSaveContent.extraProps',
	'buttonSettings.className',
	(props, blockType, attributes) => {
		if (blockType.name !== BLOCK_NAME) {
			return props;
		}

		return {
			...props,
			className: classnames(
				props.className,
				getButtonSettingsClassNames(attributes)
			),
		};
	}
);
