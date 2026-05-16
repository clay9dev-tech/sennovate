<?php

class Child_Views {
    public static function init() {
        add_action('mandy_mustache_view_path', [__CLASS__, 'add_views_path']);
        add_action('mandy_mustache_partial_path', [__CLASS__, 'add_partials_path']);
    }

    public static function add_views_path($path) {
        $child_theme_views = sprintf('%s/views', get_stylesheet_directory());

        if (is_dir($child_theme_views)) {
            array_unshift($path, $child_theme_views);
        }

        return $path;
    }

    public static function add_partials_path($path) {
        $child_theme_views = sprintf('%s/views', get_stylesheet_directory());
        $child_theme_partials = sprintf('%s/partials', $child_theme_views);

        if (is_dir($child_theme_partials)) {
            array_unshift($path, $child_theme_partials);
        }

        return $path;
    }
}

add_action('after_setup_theme', ['Child_Views', 'init'], 1);
