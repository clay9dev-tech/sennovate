import { __ } from '@wordpress/i18n';
import { PanelBody, ToggleControl } from '@wordpress/components';
import { JustifyContentControl, BlockControls } from '@wordpress/block-editor';

export const ColumnsSettingsControl = (props) => {
	const { 
		dividers,
		responsiveColumns,
		reverseStackingOrder,
		justification,
		equalHeight,
		align, 
		removeSideMargins
	} = props.attributes;

	return (
		<PanelBody
			className={'skeletor-inspector-control'}
			title={__('Extra Columns Settings')}
		>
			<ToggleControl
				label={__("Dividers")}
				help={__("Display divider lines between columns")}
				checked={dividers}
				onChange={(dividers) => props.setAttributes({ dividers })}
			/>

			<ToggleControl
				label={__("Responsive Columns")}
				help={__("Columns will layout in a 50/50 arrangement on medium-sized viewports")}
				checked={responsiveColumns}
				onChange={(responsiveColumns) => props.setAttributes({ responsiveColumns })}
			/>

			<ToggleControl
				label={__("Equal Height Columns")}
				help={__("Make all columns have the same height")}
				checked={equalHeight}
				onChange={(equalHeight) =>
					props.setAttributes({ equalHeight })
				}
			/>

			<ToggleControl
				label={__("Reverse Stacking Order")}
				help={__('Columns will be stacked from right to left')}
				checked={reverseStackingOrder}
				onChange={(reverseStackingOrder) =>
					props.setAttributes({ reverseStackingOrder })
				}
			/>

			{align === 'full' && (
				<ToggleControl
					label={__("Remove Negative Side Margins")}
					help={__('When an element is set to `alignfull` it will include negative side margins, effectively reducing it\'s parent\'s side padding. Use this option to remove those negative side margins.')}
					checked={removeSideMargins}
					onChange={(removeSideMargins) =>
						props.setAttributes({ removeSideMargins })
					}
				/>
			)}


			<BlockControls>
				<JustifyContentControl
					value={justification}
					onChange={(justification) => {
						props.setAttributes({ justification });
					}}
				/>
			</BlockControls>
		</PanelBody>
	);
};
