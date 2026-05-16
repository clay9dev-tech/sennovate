<?php get_header(); ?>

<article class="error404-container">
	<header class="error404-header" role="banner">
		<h1 class="error404-heading"><span>404</span> Page Not Found</h1>
	</header>
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
