const { withSelect, withDispatch } = wp.data;
const { FocalPointPicker } = wp.components;
const { compose } = wp.compose;
const { addFilter } = wp.hooks;
const { __ } = wp.i18n;

const FeaturedImageFocalPointPicker = ({
	featuredImage,
	focalPoint,
	setFocalPoint,
}) => (
	<FocalPointPicker
		     url={ featuredImage?.source_url }
		   value={ focalPoint || { x: 0.5, y: 0.5 } }
		onChange={ setFocalPoint }
	/>
);

const applyWithSelect = withSelect((select) => {
	const { getEditedPostAttribute } = select('core/editor');

	return {
		focalPoint: getEditedPostAttribute('meta')?.featuredImageFocalPoint,
	};
});

const applyWithDispatch = withDispatch((dispatch) => {
	const { editPost } = dispatch('core/editor');

	return {
		setFocalPoint: (featuredImageFocalPoint) => {
			editPost({
				meta: { featuredImageFocalPoint },
			});
		},
	};
});

const FeaturedImageFocalPointPickerComponent = compose(
	applyWithSelect,
	applyWithDispatch
)(FeaturedImageFocalPointPicker);

addFilter(
	'editor.PostFeaturedImage',
	'featuredImageFocalPoint',
	(FeaturedImage) => (props) => {
		if (!props.media) {
			return <FeaturedImage {...props} />;
		}

		return (
			<>
				<FeaturedImage {...props} />
				<hr />
				<p>
				{__('Click on the thumbnail to set a focal point.')}
				</p>
				<FeaturedImageFocalPointPickerComponent
					featuredImage={props.media}
				/>
			</>
		);
	}
);
