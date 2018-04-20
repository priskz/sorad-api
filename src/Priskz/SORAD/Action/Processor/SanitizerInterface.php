<?php namespace Priskz\SORAD\Action\Processor;

/**
 * A Sanitizer is a simple class used to sanitize data before
 * calling the corresponding repository method. It will sanitize
 * in the sense of transforming all data to a consistent standard.
 * Think of it like a shortcut that cleans up controller logic.
 */
interface SanitizerInterface
{
	/**
	 * Sanitize data against the specified rules
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	public function sanitize(array $data);
}