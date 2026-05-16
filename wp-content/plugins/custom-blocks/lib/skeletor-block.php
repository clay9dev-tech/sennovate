<?php
namespace Mandy;

abstract class Skeletor_Block {
	/**
	 * @static Array of classes extending Skeletor_Block
	 * @var array
	 */
	private static $blocks = [];

	/**
	 * Block Title
	 *
	 * @var string
	 */
	public static $title;

	/**
	 * This should match the mustache template filename
	 *
	 * @var string
	 */
	public static $name;

	/**
	 * Settings passed into acf_register_block_type. Skeletor_Block will set
	 * some reasonable defaults, but concrete Skeletor_Blocks can set their
	 * own $block_settings to override that
	 *
	 * @var array
	 * @see https://www.advancedcustomfields.com/resources/acf_register_block_type/
	 * @see https://developer.wordpress.org/block-editor/developers/block-api/block-registration/#registerblocktype
	 */
	public static $block_settings = [];

	/**
	 * Data describing the innerBlocks for the Block. ACF currently only
	 * allows for a maximum of one block insertion point per block, but in
	 * case we get support for multiple in the future, there's an "extra"
	 * array.
	 *
	 * @var array
	 */
	public static $inner_blocks = [];

	/**
	 * List of ACF field properties. This will be passed through
	 * Skeletor_Block::set_field_defaults(), so you don't need to be overly
	 * specific with the field settings
	 *
	 * @var array
	 */
	public static $field_group = [];

	/**
	 * Called on after_theme_setup. This is used to bind filters to classes
	 * extending Skeletor_Block.
	 *
	 * @return void
	 */
	public static function init() {
		$name = static::$name;
		$block = get_called_class();
		$view = $block::get_view();

		Skeletor_Block::$blocks[$name] = $block;

		static::add_field_group();

		add_filter("before_render_{$view}", [$block, 'add_block_attributes']);
		add_filter("before_render_{$view}", [$block, 'before_render']);
		add_filter("before_render_{$view}", [$block, 'add_innerblocks_to_block_data']);
		add_filter("block_class_{$view}", [$block, 'block_class'], 10, 2);
		add_filter("block_style_{$view}", [$block, 'block_style'], 10, 2);
		add_filter("block_attributes_{$view}", [$block, 'block_attributes'], 10, 2);
	}

	/**
	 * Returns the array of classes that extend Skeletor_Block and have been
	 * initialized.
	 *
	 * @return Array
	 */
	public static function get_blocks() {
		return Skeletor_Block::$blocks;
	}

	/**
	 * add_field_group uses this method to get the field data. This is used
	 * to set the automatic defaults, like for example using a sluggified
	 * version of the title for the name if that's left out.
	 *
	 * @return Array
	 */
	protected static function get_block_fields() {
		$this_class = get_called_class();
		$block_name = $this_class::$name;
		
		$defined_field_group = \apply_filters('skeletor_block_field_group', static::$field_group, $block_name, $this_class);

		return array_map([$this_class, 'set_field_defaults'], $defined_field_group);
	}

	/**
	 * Take an array that defines an ACF field and set some reasonable defaults.
	 *
	 * type defaults to text if not set
	 * name defaults to the label, but sanitized like a slug
	 * key is "field_{group name}_{field name}
	 *
	 *
	 * @param Array $field
	 * @return Array
	 */
	public static function set_field_defaults($field) {
		//Set the field type to be text if undefined
		if (!isset($field['type'])) {
			$field['type'] = 'text';
		}

		//Set the field label to be an empty string if undefined.
		if (!isset($field['label'])) {
			$field['label'] = '';
		}

		//If the field wasn't given a name, generate one using the label
		if (!isset($field['name'])) {
			if (empty($field['label']) && !in_array($field['type'], ['tab', 'message', 'clone'])) {
				$err = sprintf('Failed to generate a field name in %s because it has no label', get_called_class());
				throw new \Exception($err);
			}

			$field['name'] = str_replace('-', '_', sanitize_title($field['label']));
		}

		//If the field wasn't given a key, generate one using the block + label names
		if (!isset($field['key'])) {
			$field['key'] = sprintf('field_%s_%s', static::$name, $field['name']);
		}

		$sub_fields = [];
		if (isset($field['sub_fields'])) {
			$sub_fields = $field['sub_fields'];
		}

		$field['sub_fields'] = array_map([get_called_class(), 'set_field_defaults'], $sub_fields);

		return $field;
	}

	/**
	 * Called by init, this function takes the static $field_group array of
	 * fields and returns the array to pass into acf_add_local_field_group
	 *
	 * @return void
	 */
	public static function add_field_group() {
		if (!function_exists('acf_add_local_field_group')) {
			return;
		}

		acf_add_local_field_group([
			'key'      => sprintf('group_%s', static::$name),
			'title'    => static::$title,
			'fields'   => static::get_block_fields(),
			'location' => [
				[
					[
						'param'    => 'block',
						'operator' => '==',
						'value'    => 'acf/' . acf_slugify(static::$name),
					],
				],
			],
		]);
	}

	/**
	 * This function is bound to the before_render_{$name} filter. Provide
	 * an implementation of this method in a subclass to make any
	 * modifications to the block data before it's used to generate the
	 * attributes and render.
	 *
	 * @param Array $block_data
	 * @return Array
	 */
	public static function before_render($block_data) {
		return $block_data;
	}

	/**
	 * Returns the class list based on the specified block data. Block
	 * classes extending Skeletor_Block should implement this method to alter
	 * the block's class when rendered.
	 *
	 * @param Array $class_list
	 * @param Array $block_data
	 * @return Array
	 */
	public static function block_class($class_list, $block_data) {
		return $class_list;
	}

	/**
	 * Returns the style array based on the specified block data. Block
	 * classes extending Skeletor_Block should implement this method to alter
	 * the block's class when rendered.
	 *
	 * @param Array $style
	 * @param Array $block_data
	 * @return Array
	 */
	public static function block_style($style, $block_data) {
		return $style;
	}

	/**
	 * Returns the attribute array based on the specified block data. Block
	 * classes extending Skeletor_Block should implement this method to alter
	 * the block's attributes when rendered.
	 *
	 * @param Array $block_attributes
	 * @param Array $block_data
	 * @return Array
	 */
	public static function block_attributes($block_attributes, $block_data) {
		return $block_attributes;
	}

	/**
	 * Get this block rendered with the given data.
	 *
	 * @param Array $block_data
	 * @param Boolean $return_string
	 * @return String
	 */
	public static function render($block_data) {
		if (!$view = static::get_view($block_data)) {
			return '';
		}

		if (!class_exists('\Mandy\MandyMustache') || !MandyMustache::$engine) {
			trigger_error(
				sprintf('Failed to render "%s", missing MandyMustache engine!', $view),
				E_USER_WARNING
			);

			return '';
		}

		return MandyMustache::render($view, $block_data, true);
	}

	/**
	 * Filter on `before_render`
	 *
	 * Adds the innerblocks jsx tag to $block_data['innerBlocks']
	 *
	 * @param Array $block_data
	 * @return Array
	 */
	public static function add_innerblocks_to_block_data($block_data) {
		$block_data['innerBlocks'] = self::get_innerblocks('', isset($block_data['is_preview']));

		return $block_data;
	}

	/**
	 * Parse the Skeletor_Block's $inner_blocks into a jsx tag for the block
	 * editor.
	 *
	 * @param String $name
	 * @param Boolean $is_preview
	 * @return String
	 */
	public static function get_innerblocks($name = '', $is_preview = false) {
		$this_class = get_called_class();
		$block_name = $this_class::$name;
		$inner_blocks = $this_class::$inner_blocks;
		$inner_blocks = apply_filters('skeletor_block_inner_blocks', $inner_blocks, $block_name, $this_class);
		if (!is_array($inner_blocks)) {
			$inner_blocks = [];
		}

		if ($name && isset($inner_blocks[$name])) {
			$ib = $inner_blocks[$name];
		} else {
			if (count($inner_blocks) > 0) {
				$ib = $inner_blocks[0];
			} else {
				$ib = [];
			}
		}

		$ib_data = [];
		foreach ($ib as $key => $val) {
			if (!$val) {
				continue;
			}

			$encoded_val = $val;
			if ($key != 'templateLock') {
				$encoded_val = wp_json_encode($val);
			}

			$ib_data[] = sprintf('%s="%s"', $key, esc_attr($encoded_val));
		}

		$tmpl = '<InnerBlocks %s/>';
		if ($is_preview) {
			$tmpl = '<InnerBlocks %s></InnerBlocks>';
		}

		return sprintf($tmpl, implode(' ', $ib_data));
	}

	/**
	 * Just a simple wrapper around static::$name. Doesn't do much on its own
	 * but useful in a subclass for using a view that doesn't match the name.
	 *
	 * @return String
	 */
	protected static function get_view($block_data = null) {
		return static::$name;
	}

	/**
	 * Pass the block data into the specified view using MandyMustache.
	 *
	 * @param String $view
	 * @param Array $block_data
	 * @return String
	 */
	public static function render_view($view, $block_data) {
		if (!$view) {
			return '';
		}

		if (!class_exists('\Mandy\MandyMustache') || !MandyMustache::$engine) {
			trigger_error(
				sprintf('Failed to render "%s", missing MandyMustache engine!', $view),
				E_USER_WARNING
			);

			return '';
		}

		return MandyMustache::render($view, $block_data, true);
	}

	/**
	 * Auto-generate block data params.
	 *
	 * @param Array $block_data
	 * @return String
	 */
	public static function add_block_attributes($block_data) {
		$block_id = static::get_block_id($block_data);
		if (!isset($block_data['_block_id'])) {
			$block_data['_block_id'] = $block_id;
		}
		$block_class = static::get_block_class($block_data);
		$block_style = static::get_block_style($block_data);
		$block_atts = static::get_block_attributes($block_data);

		return array_merge($block_data, [
			'_block_id'         => $block_id,
			'_block_class'      => $block_class,
			'_block_style'      => $block_style,
			'_block_attributes' => $block_atts,
		]);
	}

	/**
	 * Retrieves a string of key value pairs of this block's attributes
	 *
	 * @param Array $block_data
	 * @return String
	 */
	public static function get_block_attributes($block_data) {
		$block_id = static::get_block_id($block_data);
		$block_class = static::get_block_class($block_data);
		$block_style = static::get_block_style($block_data);

		$name = static::$name;

		$block_atts = [
			'id'    => $block_id,
			'class' => $block_class,
			'style' => $block_style,
		];

		$block_atts = apply_filters('block_attributes', $block_atts, $block_data);
		$block_atts = apply_filters("block_attributes_{$name}", $block_atts, $block_data);

		$block_atts_arr = [];

		foreach ($block_atts as $attribute => $value) {
			if (!$value) {
				continue;
			}

			$block_atts_arr[] = MandyMustache::render('{{ attribute }}="{{ value }}"', [
				'attribute' => $attribute,
				'value'     => $value,
			], true);
		}

		return implode(' ', $block_atts_arr);
	}

	/**
	 * Gets the value for _block_id, passing it through some filters
	 *
	 * @param Array $block_data
	 * @return String
	 */
	public static function get_block_id($block_data) {
		if (isset($block_data['_block_id'])) {
			return $block_data['_block_id'];
		}

		$name = str_replace('_', '-', static::$name);
		$ret = uniqid("{$name}-");

		$ret = apply_filters('block_id', $ret, $block_data);
		$ret = apply_filters("block_id_{$name}", $ret, $block_data);

		return $ret;
	}

	/**
	 * Gets the value for _block_class, passing it through some filters
	 *
	 * @param Array $block_data
	 * @return String
	 */
	public static function get_block_class($block_data) {
		$class_list = [];
		if (isset($block_data['_block_class'])) {
			$class_list = explode(' ', $block_data['_block_class']);
		}

		$name = static::$name;

		$class_list[] = str_replace('_', '-', $name);

		$class_list = apply_filters('block_class', $class_list, $block_data);
		$class_list = apply_filters("block_class_{$name}", $class_list, $block_data);

		$class_list = array_unique($class_list);

		return implode(' ', $class_list);
	}

	/**
	 * Gets the value for _block_style, passing it through some filters
	 *
	 * @param Array $block_data
	 * @return String
	 */
	public static function get_block_style($block_data) {
		$style = [];
		if (isset($block_data['_block_style'])) {
			$style = $block_data['_block_style'];
			if (!is_array($style)) {
				if (!$style) {
					$style = [];
				} else {
					$style = [$style];
				}
			}
		}

		$name = static::$name;

		$style = apply_filters('block_style', $style, $block_data);
		$style = apply_filters("block_style_{$name}", $style, $block_data);

		return static::style_array_to_string($style);
	}

	/**
	 * Converts array [key => val, foo => bar] to string 'key: val; foo: bar;'
	 *
	 * @param Array $style
	 * @return String
	 */
	private static function style_array_to_string($style) {
		if (!isset($style['background-image'])) {
			$properties = ['background-size', 'background-position', 'background-repeat'];
			foreach ($properties as $prop) {
				if (isset($style[$prop])) {
					unset($style[$prop]);
				}
			}
		}

		$ret = [];
		foreach ($style as $key => $val) {
			$ret[] = sprintf('%s: %s;', $key, $val);
		}

		return implode(' ', $ret);
	}

	/**
	 * Like sluggify but snake case. Used for generating a block name based
	 * on its title.
	 *
	 * @param String $block_title
	 * @return String
	 */
	private static function sanitize_block_title($block_title) {
		$ret = str_replace('-', '_', sanitize_title_with_dashes($block_title));

		return $ret;
	}
}
