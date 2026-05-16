<?php
// Get all categories
$all_categories = get_categories();

if ( ! empty( $all_categories ) ) {
	?>
	<div class="blog-categories-widget">
	<h5 class="wp-block-heading eyebrow-underline">Categories</h5>
	<ul class="blog-categories-list">
	<?php
	foreach ( $all_categories as $category ) {
		echo '<li><a href="' . esc_url( get_category_link( $category->term_id ) ) . '">' . esc_html( $category->name ) . '</a></li>';
	}
	?>
	</ul>
	</div>
	<?php
} else {
	echo '<p>No categories found.</p>';
}
?>
