<?php
if (! defined('ABSPATH')) {
	exit;
}

/**
 * Customizes the WordPress media library
 */
class Mandy_Media_Library {

	/**
	 * Sets up the class functionality.
	 *
	 * @access public
	 * @since  1.0.0
	 * @return void
	 */
	public function __construct() {
		add_filter('media_view_settings', [$this, 'gallery_default_links']);
		add_filter('jpeg_quality', [$this, 'increase_jpg_compression']);
		add_filter('wp_handle_upload_prefilter', [$this, 'restrict_media_upload_file_size'], 10, 2);
		add_filter('wp_handle_upload_prefilter', [$this, 'restrict_media_upload_image_dimensions'], 10, 2);
	}

	/**
	 * Forces gallery images to link to file.
	 *
	 * @access public
	 * @since  1.0.0
	 * @param  array $settings List of media view settings.
	 * @return array Updates list of media view settings.
	 */
	public function gallery_default_links($settings) {
		$settings['galleryDefaults']['link'] = 'file';
		return $settings;
	}

	/**
	 * Increases JPG compression.
	 * Default is 90.
	 *
	 * @access public
	 * @since  1.0.0
	 * @param  int $quality Quality level between 0 (low) and 100 (high)
	 * @return int New quality level
	 */
	public function increase_jpg_compression($quality) {
		return 70;
	}

	/**
	 * Restrict file uploads in media library to set size
	 *
	 * @param array $file File being uploaded
	 * @return $file
	 */
	public static function restrict_media_upload_file_size($file) {
		$size = '10000'; // kilobytes
		$type = $file['type'];

		// Use this filter to modify above values per-site
		$max_size = apply_filters('media_upload_max_size', $file, $size);
		if (!is_array($max_size)) {
			$size = $max_size;
		}

		// if other restritions are already in place, return
		if (isset($file['error']) && $file['error']) {
			return $file;
		}

		// if file type specified doesnt match, return
		if (!str_contains($file['type'], $type)) {
			return $file;
		}

		// Format size values to readable numbers
		$file_size = $file['size'];
		$file_size_formatted = number_format($file_size / 1000000, 2);
		$max_size_formatted = number_format($size / 1000, 2);

		// Notify user of exceeded limits
		if ($size && $file_size > $size * 1024) {
			return [
				'name'  => $file['name'],
				'error' => "File size is too large. Maximum file size for {$type} is {$max_size_formatted}MB. Uploaded file size is {$file_size_formatted}MB.",
			];
		}

		return $file;
	}

	/**
	 * Restrict image uploads in media library to set dimensions
	 *
	 * @param array $file File being uploaded
	 * @return $file
	 */
	public static function restrict_media_upload_image_dimensions($file) {
		$width = '10000'; // pixels
		$height = '10000'; // pixels

		// Use this filter to modify above values per-site
		$max_dimensions = apply_filters('media_upload_max_dimensions', $file);

		// if other restritions are already in place, return
		if (isset($file['error']) && $file['error']) {
			return $file;
		}

		// Check for apply_filters passed values
		if (isset($max_dimensions['width']) && $max_dimensions['width']) {
			$width = $max_dimensions['width'];
		}
		if (isset($max_dimensions['height']) && $max_dimensions['height']) {
			$height = $max_dimensions['height'];
		}

		// if file type is not an image, return
		if (!str_contains($file['type'], 'image')) {
			return $file;
		}

		// if image is an SVG, return. They dont require dimensions
		if (str_contains($file['type'], 'svg')) {
			return $file;
		}

		// Get file dimensions
		$file_dimensions = getimagesize($file['tmp_name']);
		if ($file_dimensions) {
			$file_width     = $file_dimensions[0];
			$file_height    = $file_dimensions[1];
		}

		// Check for width & height
		if (!$width || !$height) {
			return $file;
		}

		// Notify user of exceeded limits
		if ($file_width > $width) {
			return [
				'name'  => $file['name'],
				'error' => "Image dimensions are too large. Maximum width is {$width}px. Uploaded image width is {$file_width}px.",
			];
		}
		if ($file_height > $height) {
			return [
				'name'  => $file['name'],
				'error' => "Image dimensions are too large. Maximum height is {$height}px. Uploaded image height is {$file_height}px.",
			];
		}

		return $file;
	}
}
