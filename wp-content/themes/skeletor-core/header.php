<!DOCTYPE html>
<html <?php language_attributes(); ?> <?php html_class(); ?>>

<head>

	<?php wp_head(); ?>

</head>

<body <?php body_class(); ?>>
	<?php wp_body_open(); ?>

	<a class="screen-reader-text" href="#main">Skip to content</a>

	<?php
	if (is_landing_page()) {
		get_template_part('parts/header-landing', null, $args);
	} else {
		get_template_part('parts/header-default', null, $args);
	}
	?>

	<main id="main" class="main" role="main">
