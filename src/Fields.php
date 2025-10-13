<?php

namespace Dxw\FlagMajorUpdates;

class Fields implements \Dxw\Iguana\Registerable
{
	public function register(): void
	{
		/** @psalm-suppress HookNotFound */
		add_action('acf/include_fields', [$this, 'addFields']);
		/** @psalm-suppress HookNotFound */
		add_filter('acf/update_value/name=dxw_flag_major_update', [$this, 'updateLastMajorUpdateDatetime'], 10, 2);
		/** @psalm-suppress HookNotFound */
		add_filter('acf/update_value/name=dxw_flag_major_update_datetime', [$this, 'preserveMajorUpdateDatetime'], 10, 2);
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
					'name' => 'dxw_flag_major_update',
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
				[
					'key' => 'field_bb992f860a1e0',
					'label' => 'Last major update was:',
					'name' => 'dxw_flag_major_update_datetime',
					'type' => 'text',
					'readonly' => 1,
					'conditional_logic' => [
						[
							[
								'field'    => 'field_bb992f860a1e0',
								'operator' => '!=empty'
							],
						],
					],
					'wrapper' => [
						'width' => '100%',
					],
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

	public function updateLastMajorUpdateDatetime(int $input, int $postId): int
	{
		if ($input == 1) {
			$modifiedDate = new \DateTimeImmutable('now', new \DateTimeZone('Europe/London'));
			/** @psalm-suppress UndefinedFunction */
			update_field('dxw_flag_major_update_datetime', $modifiedDate->format('Y-m-d H:i:s'), $postId);
		}
		return 0;
	}

	public function preserveMajorUpdateDatetime(string $input, int $postId): string
	{
		/**
		 * @psalm-suppress UndefinedFunction
		 * @var string|bool $currentValue
		 */
		$currentValue = get_field('dxw_flag_major_update_datetime', $postId);
		if (empty($input) || $input < $currentValue) {
			return (string) $currentValue;
		}
		return $input;
	}

}
