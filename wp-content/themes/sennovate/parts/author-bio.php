<?php
namespace Mandy\Skeletor\Blog;
use \WP_Query;
use \Post_Card;
global $post;
?>
<div class="author-bio-container">
	<div class="author-bio">
		<div class="author-image">
			<?php echo get_avatar($post->post_author, 24); ?>
		</div>
		<div class="author-info">
			<p class="name"><?php echo get_the_author_meta('display_name'); ?></p>
		</div>
	</div>
	<div class="blog-share-bio">

		<?php echo do_shortcode('[cher-links]'); ?>
	</div>
</div>
