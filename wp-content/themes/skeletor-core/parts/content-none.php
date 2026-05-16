<?php
if (is_search()) :
	?>

	<p>No results found for <em>&lsquo;<?php echo get_search_query(); ?>&rsquo;.</em> Please try again with different keywords.</p>
	<?php get_search_form(); ?>

<?php else : ?>

	<p>It seems we can&rsquo;t find what you&rsquo;re looking for. Perhaps searching can help.</p>
	<?php get_search_form(); ?>

<?php
endif;
