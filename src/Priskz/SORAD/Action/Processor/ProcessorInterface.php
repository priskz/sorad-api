<?php namespace Priskz\SORAD\Action\Processor;

use Priskz\SORAD\Action\ValidatorInterface;

/**
 *  A Processor is a simple class used to validate/clean data.
 */
interface ProcessorInterface
{
	/**
	 * Process the given data against the given rules and useable data keys.
	 *
	 * @param  array  $data
	 * @param  array  $dataKeys
	 * @param  array  $rules
	 * @return Payload
	 */
	public function process(array $data, array $dataKeys, array $rules);
}