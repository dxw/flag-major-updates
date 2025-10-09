<?php

describe(\Dxw\FlagMajorUpdates\Fields::class, function () {
	beforeEach(function () {
		$this->fields = new \Dxw\FlagMajorUpdates\Fields();
	});

	it('implements the registerable interface', function () {
		expect($this->fields)->toBeAnInstanceOf(\Dxw\Iguana\Registerable::class);
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
});
