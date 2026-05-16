import { __ } from '@wordpress/i18n';
import { PanelBody, ToggleControl, Notice } from '@wordpress/components';

export const ResponsiveVisiblityControl = (props) => {
	const { hideOnMobile, hideOnDesktop } = props.attributes;

	return (
		<PanelBody
			className={'skeletor-inspector-control'}
			title={__('Responsive Visibility')}
			initialOpen={false}
		>
			<ToggleControl
				label={__('Hide on Mobile')}
				help={__("Element will be hidden when on mobile viewports")}
				checked={hideOnMobile}
				onChange={(hideOnMobile) => props.setAttributes({ hideOnMobile })}
			/>
			<ToggleControl
				label={__('Hide on Desktop')}
				help={__("Element will be hidden when on desktop viewports")}
				checked={hideOnDesktop}
				onChange={(hideOnDesktop) => props.setAttributes({ hideOnDesktop })}
			/>

			{(hideOnDesktop && hideOnMobile) && (
				<Notice status="warning" isDismissible={false} className="full-width-notice">
					<p>{__("NOTE: Hiding on both Desktop & Mobile means this block will be perpetually hidden.")}</p>
				</Notice>
			)}
		</PanelBody>
	);
};
