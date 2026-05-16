<div class="post-card-collection-wrapper alignwide">
	<div class="post-card-collection">
		<?php
		while (have_posts()) {
			the_post();
			get_template_part('parts/search', 'item');
		}
		?>
	</div>
</div>
<?php
if (function_exists('mandy_pagination')) {
	mandy_pagination();
}
