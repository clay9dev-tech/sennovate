<?php

namespace Skeletor;

include_once ABSPATH . 'wp-includes/class-wp-customize-control.php';

use \WP_Customize_Control as Control;

class Customize_Variable_Color_Control extends Control
{
	public $type = 'variable_color';

	public function render_content()
	{
		$input_id         = '_customize-input-' . $this->id;
		$description_id   = '_customize-description-' . $this->id;
		$setting = $this->manager->get_setting("skeletor_theme_palette[{$this->id}]");
?>
		<?php if (!empty($this->label)) : ?>
			<label for="<?php echo esc_attr($input_id); ?>" class="customize-control-title"><?php echo esc_html($this->label); ?></label>
		<?php endif; ?>
		<?php if (!empty($this->description)) : ?>
			<span id="<?php echo esc_attr($description_id); ?>" class="description customize-control-description"><?php echo $this->description; ?></span>
		<?php endif; ?>
		<div class="customize-variable-color-input-wrapper">
			<input id="<?php echo esc_attr($input_id); ?>" class="customize-variable-color-text-input" type="text" <?php $this->input_attrs(); ?> <?php if (!isset($this->input_attrs['value'])) : ?> value="<?php echo esc_attr($this->value()); ?>" <?php endif; ?> <?php $this->link(); ?> data-default-value="<?php echo esc_attr($setting->default); ?>" />

			<input id="<?php printf('%s--color-input', esc_attr($input_id)); ?>" class="customize-variable-color-input" type="color" />

			<div id="<?php printf('%s--color-sample', esc_attr($input_id)); ?>" class="customize-variable-color-sample" style="background-color: <?php echo esc_attr($this->value()); ?>;">
			</div>

			<button class="customizer-variable-color-reset">Reset</button>
		</div>
<?php
	}
}
