<?php
	global $post;
?>
<div class="archive-filter">
	<div class="filter-category">
		<div class="filter-heading">Categories</div>
		<?php
		$categories = get_categories();
		if (!empty($categories)) {
			echo '<ul class="filter-category-lists">';
			echo '<li><a href="' . esc_url(site_url('blog')) . '" class="category-name">All</a></li>';
			foreach ($categories as $category) {
				echo '<li><a href="' . esc_url(get_category_link($category->term_id)) . '" class="category-name">' . esc_html($category->name) . '</a></li>';
			}
			echo '</ul>';
		}
		?>
	</div>
	<div class="filter-search">
		<?php get_search_form(); ?>
	</div>
</div>
