<?php

namespace Priskz\SORAD\Action\Processor\Laravel;

use Priskz\Payload\Payload;
use Illuminate\Validation\Factory;
use Priskz\SORAD\Action\Processor\ValidatorInterface;

/**
 * A Validator is just a way to check to see if an array values
 * meet certain criteria, such as existence of certain bits of
 * data, data being of certain types, etc.
 */
class Validator implements ValidatorInterface
{
	/**
	 * @var  \Illuminate\Validation\Factory
	 */
	protected $validationFactory;

	/**
	 * Set up a new Validator using a Laravel Validation factory
	 *
	 * @param  \Illuminate\Validation\Factory  $validationFactory
	 */
	public function __construct(Factory $validationFactory)
	{
		$this->validationFactory = $validationFactory;
	}

	/**
	 * Validate input against the specified rules
	 *
	 * @param  array  $data
	 * @param  array  $rules
	 * @param  array  $messages
	 * @return \Payload\Payload
	 */
	public function validate(array $data, array $rules, array $messages = [])
	{
		// Build the validator.
		$validator = $this->validationFactory->make($data, $rules, $messages);

		// Return the errors, if any.
		if($validator->fails())
		{
			return new Payload($validator->messages(), 'invalid');
		}

		return new Payload($data, 'valid');
	}
}