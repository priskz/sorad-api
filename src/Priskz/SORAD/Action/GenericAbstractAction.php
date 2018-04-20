<?php namespace Priskz\SORAD\Action;

use Priskz\Payload\Payload;
use Priskz\SORAD\Action\Processor\ProcessorInterface;

abstract class GenericAbstractAction
{
	/**
	 * @var  RAD\ActionProcessor Data Processor
	 */
	protected $processor;

	/**
	 * @var  array 	Keys accepted by this action
	 */
	protected $dataKeys;

	/**
	 * @var  array 	Rules for any data.
	 */
	protected $rules;

	/**
	 *	Constructor
	 */
	public function __construct(ProcessorInterface $processor)
	{
		$this->processor = $processor;
	}

	/**
	 *	Main Method
	 */
	public function __invoke($requestData)
	{
		// Process the incoming data AKA Sanitize && Validate.
		$payload = $this->processor->processActionData($requestData, $this->getDataKeys(), $this->getRules());

		// Verify that the data has been sanitized and validated.
		if ($payload->getStatus() != 'valid')
		{
			return $payload;
		}

		// Execute the action.
		$this->execute($payload->getData());
	}

	/**
	 * Do/perform this action with the data once it has been succesfully processed.
	 *
	 * @return array
	 */
	abstract protected function execute($data);

	/**
	 * Get this Action's data keys.
	 *
	 * @return array
	 */
	public function getDataKeys()
	{
		return $this->dataKeys;
	}

	/**
	 * Get this Action's rules.
	 *
	 * @return array
	 */
	public function getRules()
	{
		return $this->rules;
	}
}
