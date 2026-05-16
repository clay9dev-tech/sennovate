<?php get_header(); ?>

<article class="error404-container">
	<h1 class="error404-heading">404</h1>
	<?php
	$content_404 = false;
	if (function_exists('get_field')) {
		$content_404 = get_field('site_404_content', 'option');
	}

	if ($content_404) {
		printf(
			'<div class="error404-content core">%s</div>',
			$content_404
		);
	}
	?>
	</div>
</article>

<?php
get_footer();
