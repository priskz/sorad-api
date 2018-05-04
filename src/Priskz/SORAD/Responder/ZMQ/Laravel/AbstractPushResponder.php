<?php

namespace Priskz\SORAD\Responder\ZMQ\Laravel;

use Input, Route;
use Priskz\SORAD\Responder\PushResponderInterface;
use ZMQ, ZMQContext;

/**
 * A Responder is a simple class that generates the response for an action.
 */
abstract class AbstractPushResponder implements PushResponderInterface
{
	/**
	 *	ZMQ Context
	 */
	protected $context;

	/**
	 *	Socket
	 */
	protected $socket;

	/**
	 *	ZMQSocket Configuration
	 */
	protected $connection;
	protected $ioThreadTotal;
	protected $persistent;
	protected $persistenceKey;
	protected $socketType = ZMQ::SOCKET_PUSH;

	/**
	 *	Constructor
	 */
	public function __construct()
	{

	}

	/**
	 *	Main Method
	 */
	public function __invoke()
	{
		// Get this request's data.
		$requestData = $this->parseRequest();

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
	abstract public function generateResponse($data);

	/**
	 *	Get Request Data
	 *	
	 *  Note: This method assumes AJAX and other JSON
	 *  data will be in the request data as 'json'.
	 */
	public function parseRequest()
	{
		$requestData = Input::all();

		// Prepare the incoming json request data.
		foreach( json_decode($requestData['json'], true) as $json )
		{
			$requestData[$json['name']] = $json['value'];
		}

		// Remove the unprepped json data.
		unset( $requestData['json'] );
		
		$requestParamData = Route::getCurrentRoute()->parametersWithoutNulls();

		if($requestParamData)
		{
			$requestData = array_merge($requestData, $requestParamData);
		}

		return $requestData;
	}

	/**
	 *	Push a message to a subscription socket.
	 */
	public function push($json)
	{
		$this->connect();

		// Send the message through the socket.
	    $this->socket->send(json_encode($json));
	}

	/**
	 *	Connect to the subscription socket.
	 */
	protected function connect()
	{
		$this->context = new ZMQContext($this->ioThreadTotal, $this->persistent);
		$this->socket  = $this->context->getSocket($this->socketType, $this->persistenceKey);
	    $this->socket->connect($this->connection);
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