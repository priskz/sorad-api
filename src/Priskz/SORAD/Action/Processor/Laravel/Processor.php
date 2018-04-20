<?php namespace Priskz\SORAD\Action\Processor\Laravel;

use Priskz\Payload\Payload;
use Priskz\SORAD\Action\Processor\GenericProcessor;
use Priskz\SORAD\Action\Processor\Laravel\Validator;

/**
 * A Processor is a simple class used to validate/clean data.
 */
class Processor extends GenericProcessor
{
	/**
	 * Create a new Validator.
	 *
	 * @param  SORAD\Action\Processor\ValidatorInterface  $validator
	 */
	public function __construct(Validator $validator)
	{
		parent::__construct($validator);
	}
}