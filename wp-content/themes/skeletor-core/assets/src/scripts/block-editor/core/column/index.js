import { cloneElement } from '@wordpress/element';
import { addFilter } from '@wordpress/hooks';
import { createHigherOrderComponent } from '@wordpress/compose';
import { InspectorControls } from '@wordpress/block-editor';

import { columnAttributes } from './attributes';
import { ColumnSettingsControl } from './control';


const BLOCK_NAME = 'core/column';

addFilter(
	'blocks.registerBlockType',
	'columnSettings.attributes',
	(settings, name) => {
		if (name !== BLOCK_NAME) {
			return settings;
		}

		return {
			...settings,
			attributes: {
				...settings.attributes,
				...columnAttributes,
			},
		};
	}
);

addFilter(
	'editor.BlockEdit',
	'columnSettings.control',
	createHigherOrderComponent(
		(BlockEdit) => (props) => {
			if (props.name !== BLOCK_NAME) {
				return <BlockEdit {...props} />;
			}

			return (
				<>
					<BlockEdit {...props} />
					<InspectorControls>
						<ColumnSettingsControl {...props} />
					</InspectorControls>
				</>
			);
		},
		'withColumnSettingsControl'
	)
);

addFilter(
	'blocks.getSaveElement',
	'columnSettings.saveElement',
	(element, blockType, attributes) => {
		if (blockType.name !== BLOCK_NAME) {
			return element;
		}

		if (attributes.stickyContent) {
			return cloneElement(
				element,
				{},
				<div className="sticky-content">{element.props.children}</div>
			);
		}

		return element;
	}
);
