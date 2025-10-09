<?php

namespace Dxw\FlagMajorUpdates;

class Fields implements \Dxw\Iguana\Registerable
{
	public function register(): void
	{
		/** @psalm-suppress HookNotFound */
		add_action('acf/include_fields', [$this, 'addFields']);
	}

	public function addFields(): void
	{
		if (! function_exists('acf_add_local_field_group')) {
			return;
		}

		/** @var array */
		$args = apply_filters('dxw_flag_major_update_field_args', [
			'key' => 'group_68e7be5bd593a',
			'title' => 'Flag major updates',
			'fields' => [
				[
					'key' => 'field_68e7be5c15bec',
					'label' => 'Major update?',
					'name' => 'major_update',
					'aria-label' => '',
					'type' => 'true_false',
					'instructions' => '',
					'required' => 0,
					'conditional_logic' => 0,
					'wrapper' => [
						'width' => '',
						'class' => '',
						'id' => '',
					],
					'message' => 'Tick if this is a significant change to the page content.',
					'default_value' => 0,
					'allow_in_bindings' => 0,
					'ui' => 0,
					'ui_on_text' => '',
					'ui_off_text' => '',
				],
			],
			'location' => [
				[
					[
						'param' => 'post_type',
						'operator' => '==',
						'value' => 'page',
					],
				],
			],
			'menu_order' => -100,
			'position' => 'side',
			'style' => 'default',
			'label_placement' => 'left',
			'instruction_placement' => 'field',
			'hide_on_screen' => '',
			'active' => true,
			'description' => '',
			'show_in_rest' => 1,
		]);

		acf_add_local_field_group($args);
	}
}
