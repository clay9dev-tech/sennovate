	</main> <!-- #main -->

	<?php
	if (is_landing_page()) {
		get_template_part('parts/footer-landing', null, $args);
	} else {
		get_template_part('parts/footer-default', null, $args);
	}
	?>

	<?php wp_footer(); ?>

</body>
</html>
