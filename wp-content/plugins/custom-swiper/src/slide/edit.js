import { __ } from "@wordpress/i18n";
import { applyFilters } from "@wordpress/hooks";
import {
	useInnerBlocksProps,
	useBlockProps,
	InnerBlocks,
} from "@wordpress/block-editor";
import { useSelect } from "@wordpress/data";
import { getAttributeClasses } from "./helpers";
import classnames from "classnames";

import SlideBlockControls from "./block-controls";

import "./editor.scss";

export default function Edit(props) {
	const { clientId } = props;

	const childCount = useSelect(
		(select) => select("core/block-editor").getBlock(clientId)?.innerBlocks?.length
	);

	const blockProps = useBlockProps({
		className: "swiper-slide",
	});

	blockProps.className = classnames(
		blockProps.className,
		getAttributeClasses(props.attributes)
	);

	const innerBlockProps = useInnerBlocksProps({
		allowedBlocks: applyFilters("mandy_swiper_slide_allowed_blocks", [
			"core/image",
			"core/cover",
			"core/group"
		]),
		renderAppender: () => !childCount && <InnerBlocks.ButtonBlockAppender />,
	});

	return (
		<>
			<SlideBlockControls {...props} />
			<div {...blockProps}>
				<InnerBlocks {...innerBlockProps} />
			</div>
		</>
	);
}
