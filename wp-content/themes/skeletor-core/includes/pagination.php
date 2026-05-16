<?php

namespace Skeletor;

class Pagination {
	public static function setup() {
		add_action('skeletor_archive_pagination', [__CLASS__, 'archive_pagination']);
	}

	public static function archive_pagination($queried_object) : void {
		$classes = ['wp-block-group', 'archive-pagination'];
		if (is_a($queried_object, 'WP_Post_Type')) {
			$classes[] = sprintf('%s__archive-pagination', $queried_object->name);
		}
		$classes = apply_filters('archive_pagination_classes', $classes, $queried_object);
		if (!is_array($classes)) {
			$classes = [$classes];
		}
		?>
		<section class="<?php echo implode(' ', $classes); ?>">
			<?php
			if (function_exists('facetwp_display')) {
				echo facetwp_display('pager');
			}
			?>
		</section>
		<?php				
	}
}
add_action('after_setup_theme', ['\\Skeletor\\Pagination', 'setup']);

