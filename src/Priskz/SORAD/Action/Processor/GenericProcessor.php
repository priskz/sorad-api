<?php namespace Priskz\SORAD\Action\Processor;

use Priskz\Payload\Payload;
use Priskz\SORAD\Action\Processor\ProcessorInterface;

/**
 * A Processor is a simple class used to validate/clean data.
 */
class GenericProcessor implements ProcessorInterface
{
	/**
	 * @var  \SORAD\Laravel\Validator
	 */
	protected $validator;

	/**
	 * @var  array 	Errors for any data.
	 */
	protected $errorPayload;

	/**
	 * Constructor
	 *
	 * @param  \SORAD\Action\Processor\ValidatorInterface  $validator
	 */
	public function __construct(ValidatorInterface $validator)
	{
		$this->validator = $validator;
	}

	/**
	 * Process the given data against the given rules and useable data keys.
	 *
	 * @param  array  $data
	 * @param  array  $dataKeys
	 * @param  array  $rules
	 * @return Payload
	 */
	public function process(array $data, array $dataKeys, array $rules)
	{
		// Intersect the data given the with the data keys provided.
		$specifiedData = array_intersect_key($data, array_flip($dataKeys));

		// Validate and set our errors if they exist.
		$this->errorPayload = $this->validator->validate($specifiedData, $rules);

		// Return sanitized data if no validation errors exist.
		if($this->errorPayload->getStatus() == 'valid')
		{
			return new Payload($specifiedData, 'valid');
		}

		return $this->errorPayload;
	}
}