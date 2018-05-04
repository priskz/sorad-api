<?php

namespace Priskz\SORAD\Action;

use Priskz\Payload\Payload;
use Priskz\SORAD\Action\ActionInterface;
use Priskz\SORAD\Action\Processor\ProcessorInterface;

class GenericAbstractAction implements ActionInterface
{
	/**
	 * @var  
	 */
	protected $processor;

	/**
	 * @var  array  Data configuration.
	 */
	protected $config = [];

	/**
	 *	Constructor
	 */
	public function __construct(ProcessorInterface $processor)
	{
		$this->processor = $processor;
	}

	/**
	 * Run this action's implemented logic.
	 *
	 * @return array
	 */
	public function execute($data)
	{
		// Process the incoming data AKA Sanitize && Validate.
		return $this->processor->process($data, $this->config);
	}
}
