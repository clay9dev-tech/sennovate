<?php
global $post;
?>
<section class="wp-block-group blog-page__post-navigation">
	<?php
		the_post_navigation( array(
			'prev_text'  => __( '<span class="blog-page__post-navigation--prev-arrow">PREV</span> <p>%title</p>' ),
			'next_text'  => __( '<span class="blog-page__post-navigation--next-arrow">NEXT</span> <p>%title</p>' ),
		) );
	?>
</section>
