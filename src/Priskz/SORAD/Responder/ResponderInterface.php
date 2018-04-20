<?php namespace Priskz\SORAD\Responder;

/**
 * A Responder is a simple class that generates the response for an action.
 */
interface ResponderInterface
{
	/**
	 *	Main Method
	 */
	public function __invoke();

	/**
	 *  Generate Response
	 */
	public function generateResponse($data);

	/**
	 *  Get Request Data
	 */
	public function getRequestData();

	/**
	 *	Set API Context
	 */
	public function setApiContext($requestData);

	/**
	 *	Get API Context
	 */
	public function getApiContext();
}
