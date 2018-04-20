<?php namespace Priskz\SORAD\Responder\Laravel;

use Input, Route;
use Priskz\SORAD\Responder\ResponderInterface;

abstract class AbstractGenericResponder implements ResponderInterface
{
	/**
	 *	Action
	 */
	protected $action;

	/**
	 *	API Context
	 */
	protected $apiContext = '';

	/**
	 *	Constructor
	 */
	public function __construct($action = null)
	{
		$this->action = $action;
	}

	/**
	 *	Main Method
	 */
	public function __invoke()
	{
		// Get this request's data.
		$requestData = $this->getRequestData();

		// Perform this responder's action.
		if(isset($this->action))
		{
			$payload = $this->action->__invoke($requestData);
		}
		else
		{
			$payload = $requestData;
		}

		// Generate this request's response.
		return $this->generateResponse($payload);
	}

	/**
	 *	Generate Response
	 */
	abstract public function generateResponse($payload);

	/**
	 *	Get Request Data
	 */
	public function getRequestData()
	{
		$requestData = Input::all();

		$requestParamData = Route::getCurrentRoute()->parametersWithoutNulls();

		if ($requestParamData)
		{
			$requestData = array_merge($requestData, $requestParamData);
		}

		$this->setApiContext($requestData);

		return $requestData;
	}

	/**
	 *	Set API Context
	 */
	public function setApiContext($requestData)
	{
		if(array_key_exists('api_context', $requestData))
		{
			$this->apiContext = $requestData['api_context'];	
		}
	}

	/**
	 *	Get API Context
	 *	
	 *  @param  $string $prefix Format string for view string.
	 *  @return string
	 */
	public function getApiContext($prefix = false)
	{
		if(empty($this->apiContext))
		{
			return $this->apiContext;
		}

		// Note: Laravel will convert view periods into directory slashes.
		//       So we can easily interchange . with /
		$apiContextExplode = explode('/', $this->apiContext);

		// Remove 2nd segment if it exists, assuming it is an identifier.
		unset($apiContextExplode[1]);

		// Transform back into a string.
		$genericApiContext = implode('/', $apiContextExplode);

		if($prefix)
		{	
			return $genericApiContext . '/';
		}

		return $this->apiContext;	
	}
}