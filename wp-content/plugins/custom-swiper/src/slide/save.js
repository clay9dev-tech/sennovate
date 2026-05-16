/**
 * React hook that is used to mark the block wrapper element.
 * It provides all the necessary props like the class name.
 *
 * @see https://developer.wordpress.org/block-editor/reference-guides/packages/packages-block-editor/#useblockprops
 */
import { InnerBlocks, useBlockProps } from "@wordpress/block-editor";
import classNames from "classnames";
import { getAttributeClasses } from "./helpers";

export default function save({ attributes }) {
	const blockProps = useBlockProps.save({
		className: classNames("swiper-slide", getAttributeClasses(attributes))
	});

	return (
		<div {...blockProps}>
			<InnerBlocks.Content />
		</div>
	);
}
