<?php

get_header();

do_action('before_search_loop');

?>

<div class="post-card-collection-wrapper alignwide">
	<?php if (have_posts()) : ?>
	<div class="post-card-collection">
		<?php do_action('search_results'); ?>
	</div>
	<?php else : ?>
		<?php do_action('search_no_results'); ?>
	<?php endif; ?>
</div>

<?php

do_action('after_search_loop'); 

get_footer();
