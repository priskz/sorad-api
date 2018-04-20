<?php namespace Priskz\SORAD\Action\Processor\Laravel;

/**
 * A Sanitizer is a simple class used to sanitize data before
 * calling the corresponding repository method. It will sanitize
 * in the sense of transforming all data to a consistent standard.
 * Think of it like a shortcut that cleans up controller logic.
 */
class Sanitizer
{
	/**
	 * The basic rules for sanitizing.
	 *
	 * @var array
	 */
	protected $rules;

	/**
	 * The text case related sanitize rules.
	 *
	 * @var array
	 */
	protected $caseRules;

	/**
	 * Sanitize data against the specified rules
	 *
	 * @param  array  $data
	 * @return mixed
	 */
	public function sanitize(array $data, array $rules)
	{
		/**
		 * The basic rules for sanitizing.
		 *
		 * @var array
		 */
		$this->rules = ['boolean', 'regex', 'alpha', 'numeric', 'alpha_numeric'];

		/**
		 * The text case related sanitize rules.
		 *
		 * @var array
		 */
		$this->caseRules = ['upper', 'lower', 'capitalized', 'sentence', 'alternating', 'snake', 'camel'];
	}
}
