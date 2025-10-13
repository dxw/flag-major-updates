<?php

describe(\Dxw\FlagMajorUpdates\Fields::class, function () {
	beforeEach(function () {
		$this->fields = new \Dxw\FlagMajorUpdates\Fields();
	});

	it('implements the registerable interface', function () {
		expect($this->fields)->toBeAnInstanceOf(\Dxw\Iguana\Registerable::class);
	});

	describe('->register()', function () {
		it('adds the actions', function () {
			allow('add_action')->toBeCalled();
			allow('add_filter')->toBeCalled();
			expect('add_action')->toBeCalled()->once()->with('acf/include_fields', [$this->fields, 'addFields']);
			expect('add_filter')->toBeCalled()->once()->with('acf/update_value/name=dxw_flag_major_update', [$this->fields, 'updateLastMajorUpdateDatetime'], 10, 2);

			$this->fields->register();
		});
	});

	describe('->addFields()', function () {
		context('acf_add_local_field_group does not exist', function () {
			it('does nothing', function () {
				allow('function_exists')->toBeCalled()->andReturn(false);
				expect('acf_add_local_field_group')->not->toBeCalled();

				$this->fields->addFields();
			});
		});
		context('acf_add_local_field_group does exist', function () {
			it('registers the field', function () {
				allow('function_exists')->toBeCalled()->andReturn(true);
				allow('apply_filters')->toBeCalled()->andRun(function ($hook, $input) {
					return $input;
				});
				allow('acf_add_local_field_group')->toBeCalled();
				expect('acf_add_local_field_group')->toBeCalled()->once()->with(\Kahlan\Arg::toBeAn('array'));

				$this->fields->addFields();
			});
			it('allows the args to be filtered', function () {
				allow('function_exists')->toBeCalled()->andReturn(true);
				allow('acf_add_local_field_group')->toBeCalled();
				allow('apply_filters')->toBeCalled()->andRun(function () {
					return 'a string';
				});
				expect('acf_add_local_field_group')->toBeCalled()->once()->with(\Kahlan\Arg::toBeA('string'));

				$this->fields->addFields();
			});
		});
	});

	describe('->updateLastMajorUpdateDatetime()', function () {
		context('"Major update?" box is unchecked', function () {
			it('leaves the box unchecked and does nothing else', function () {
				expect('update_field')->not->toBeCalled();
				expect($this->fields->updateLastMajorUpdateDatetime(0, 123))->toEqual(0);
			});
		});
		context('"Major update?" box is checked', function () {
			it('updates the last major update datetime, then returns an unchecked value', function () {
				allow('update_field')->toBeCalled();
				allow('\DateTimeImmutable')->toBe(new \DateTimeImmutable('2025-01-01 09:00:00'));
				expect('update_field')->toBeCalled()->once()->with('dxw_flag_major_update_datetime', '2025-01-01 09:00:00', 123);
				expect($this->fields->updateLastMajorUpdateDatetime(1, 123))->toEqual(0);
			});
		});
	});

	describe('->preserveMajorUpdateDatetime()', function () {
		beforeEach(function () {
			allow('get_field')->toBeCalled()->andReturn('2025-01-01 09:00:00');
			expect('get_field')->toBeCalled()->once()->with('dxw_flag_major_update_datetime', 123);
		});
		context('the new value is an empty string', function () {
			it('returns the current value', function () {
				expect($this->fields->preserveMajorUpdateDatetime('', 123))->toEqual('2025-01-01 09:00:00');
			});
		});
		context('the new value is an empty string and the current value is not set, so returns false', function () {
			it('returns an empty string', function () {
				allow('get_field')->toBeCalled()->andReturn(false);
				expect($this->fields->preserveMajorUpdateDatetime('', 123))->toEqual('');
			});
		});
		context('the new value is earlier than the current value', function () {
			it('returns the current value', function () {
				expect($this->fields->preserveMajorUpdateDatetime('2025-01-01 08:30:00', 123))->toEqual('2025-01-01 09:00:00');
			});
		});
		context('the new value is later than the current value', function () {
			it('returns the later value', function () {
				expect($this->fields->preserveMajorUpdateDatetime('2025-01-01 09:30:00', 123))->toEqual('2025-01-01 09:30:00');
			});
		});
	});
});
