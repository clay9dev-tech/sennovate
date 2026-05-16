import classnames from 'classnames';

import { headingAttributes } from './attributes';
import { HeadingSettingsControl } from './control';
import { getHeadingSettingsClassNames } from './helpers';

const { addFilter } = wp.hooks;
const { createHigherOrderComponent } = wp.compose;
const { InspectorControls } = wp.blockEditor;

const BLOCK_NAME = 'core/heading';

addFilter(
	'blocks.registerBlockType',
	'headingSettings.attributes',
	(settings, name) => {
		if (name !== BLOCK_NAME) {
			return settings;
		}

		return {
			...settings,
			attributes: {
				...settings.attributes,
				...headingAttributes,
			},
		};
	}
);

addFilter(
	'editor.BlockEdit',
	'headingSettings.control',
	createHigherOrderComponent((BlockEdit) => (props) => {
		if (props.name !== BLOCK_NAME) {
			return <BlockEdit {...props} />;
		}

		return (
			<>
				<BlockEdit {...props} />
				<InspectorControls>
					<HeadingSettingsControl {...props} />
				</InspectorControls>
			</>
		);
	}, 'withHeadingSettingsControl')
);


addFilter(
	'editor.BlockListBlock',
	'headingSettings.editorBlock',
	createHigherOrderComponent((BlockListBlock) => (props) => {
		if (props.name !== BLOCK_NAME) {
			return <BlockListBlock {...props} />;
		}

		const blockListProps = {
			...props,
			className: classnames(
				props.className,
				getHeadingSettingsClassNames(props.attributes)
			),
		};

		return <BlockListBlock {...blockListProps} />;
	})
);

addFilter(
	'blocks.getSaveContent.extraProps',
	'headingSettings.className',
	(props, blockType, attributes) => {
		if (blockType.name !== BLOCK_NAME) {
			return props;
		}

		return {
			...props,
			className: classnames(
				props.className,
				getHeadingSettingsClassNames(attributes)
			),
		};
	}
);
