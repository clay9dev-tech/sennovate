import classnames from 'classnames';

import { addFilter } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';
import { InspectorControls } from '@wordpress/block-editor';

import { columnsAttributes } from './attributes';
import { ColumnsSettingsControl } from './control';
import { getColumnsSettingsClassNames } from './helpers';

const BLOCK_NAME = 'core/columns';

addFilter(
	'blocks.registerBlockType',
	'columnsSettings.attributes',
	(settings, name) => {
		if (name !== BLOCK_NAME) {
			return settings;
		}

		return {
			...settings,
			attributes: {
				...settings.attributes,
				...columnsAttributes,
			},
		};
	}
);

addFilter(
	'editor.BlockEdit',
	'columnsSettings.control',
	createHigherOrderComponent(
		(BlockEdit) => (props) => {
			if (props.name !== BLOCK_NAME) {
				return <BlockEdit {...props} />;
			}

			return (
				<>
					<BlockEdit {...props} />
					<InspectorControls>
						<ColumnsSettingsControl {...props} />
					</InspectorControls>
				</>
			);
		},
		'withColumnsSettingsControl'
	)
);

addFilter(
	'editor.BlockListBlock',
	'columnsSettings.editorBlock',
	createHigherOrderComponent((BlockListBlock) => (props) => {
		if (props.name !== BLOCK_NAME) {
			return <BlockListBlock {...props} />;
		}

		return (
			<BlockListBlock
				{...props}
				className={getColumnsSettingsClassNames(props.attributes)}
			/>
		);
	})
);

addFilter(
	'blocks.getSaveContent.extraProps',
	'columnsSettings.getSaveContent.extraProps',
	(props, blockType, attributes) => {
		if (blockType.name !== BLOCK_NAME) {
			return props;
		}

		return {
			...props,
			className: classnames(
				props.className,
				getColumnsSettingsClassNames(attributes)
			),
		};
	}
);
