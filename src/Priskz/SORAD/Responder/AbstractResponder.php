<?php

namespace Priskz\SORAD\Responder;

use Exception;
use Priskz\Payload\Payload;
use Priskz\SORAD\Action\ActionInterface;
use Priskz\SORAD\Exception\MisconfiguredStatusException;

/**
 * Class AbstractResponder.
 *
 * @author Zachary Prisk <zachary.prisk@gmail.com>
 */
abstract class AbstractResponder implements ResponderInterface
{
	/**
	 *	@var  array
	 */
	const DEFAULT_STATUS = [
		Payload::STATUS_VALID     => self::HTTP_OK,
		Payload::STATUS_CREATED   => self::HTTP_OK,
		Payload::STATUS_UPDATED   => self::HTTP_OK,
		Payload::STATUS_DELETED   => self::HTTP_OK,
		Payload::STATUS_FOUND     => self::HTTP_OK,
		Payload::STATUS_INVALID   => self::HTTP_UNPROCESSABLE_ENTITY,
		Payload::STATUS_NOT_FOUND => self::HTTP_NOT_FOUND,
		Payload::STATUS_EXCEPTION => self::HTTP_UNPROCESSABLE_ENTITY
	];

	/**
	 *	@var  \Priskz\SORAD\Action\ActionInterface
	 */
	protected $action;

	/**
	 *	@var  \Priskz\Payload\Payload
	 */
	protected $result;

	/**
	 *	@var  array
	 */
	protected $status = [];

	/**
	 *	@var  array
	 */
	protected $request;

	/**
	 *	@var  @todo: Create \Priskz\SORAD\Response\ResponseInterface
	 */
	protected $response;

	/**
	 *	@var  array
	 */
	protected $header = [];

	/**
	 *	@var  string
	 */
	protected $type;

	/**
	 *	@var  integer
	 */
	protected $code;

	/**
	 *	@var  mixed
	 */
	protected $body;

	/**
	 *  @var  string
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
		return $this->process();
	}

	/**
	 *	Process the incoming Request.
	 */
	public function process()
	{
		// Get this request's data.
		$this->parseRequest();

		// Perform logic.
		$this->execute();

		// Parse the result of our business logic.
		$this->parseResult();

		// Build the Response utilizing status, type, body, etc.
		$this->buildResponse();

		// Finally, return the Response.
		return $this->respond();
	}

	/**
	 * Perform logic.
	 */
	public function execute()
	{
		if( ! is_null($this->action))
		{
			$this->setResult($this->action->execute($this->request));
		}
		else
		{
			$this->setResult(new Payload(null, 'valid'));
		}
	}

	/**
	 * Parse Request data based on implementation.
	 * 
	 * @return void
	 */
	abstract public function parseRequest();

	/**
	 * Build Response based on implementation.
	 * 
	 * @return void
	 */
	abstract public function buildResponse();

	/**
	 * @return Response
	 */
	public function respond()
	{
		return $this->response;
	}

	/**
	 * Parse the resulting Action Payload.
	 */
	protected function parseResult()
	{
		if(is_null($this->result))
		{
			$this->setCode(self::HTTP_INTERNAL_SERVER_ERROR);
		}
		else
		{
			$this->setCode($this->parseStatusCode());

			$this->setBody();
		}
	}

	/**
	 * Parse the resulting Payload status to a HTTP code.
	 */
	protected function parseStatusCode()
	{
		try
		{
			// Check if a result status is configured.
			if( ! array_key_exists($this->result->getStatus(), $this->getStatus()))
			{
				throw new MisconfiguredStatusException();
			}

			return $this->getStatus()[$this->result->getStatus()];
		}
		catch(MisconfiguredStatusException $e)
		{
			return self::HTTP_INTERNAL_SERVER_ERROR;
		}
		catch(Exception $e)
		{
			return self::HTTP_BAD_REQUEST;
		}
	}

	/**
	 * Set the response property.
	 */
	protected function setResponse($response)
	{
		$this->response = $response;
	}

	/**
	 * Set the code property.
	 */
	protected function setCode(int $code)
	{
		$this->code = $code;
	}

	/**
	 * Set the result property.
	 */
	protected function setResult(Payload $payload)
	{
		$this->result = $payload;
	}

	/**
	 * Set the body property.
	 */
	protected function setBody()
	{
		$this->body = $this->result;
	}

	/**
	 * Get configured status mappings.
	 */
	protected function getStatus()
	{
		return array_merge(self::DEFAULT_STATUS, $this->status);
	}

	/**
	 *	Set API Context
	 */
	public function setApiContext()
	{
		if(array_key_exists('api_context', $this->request))
		{
			$this->apiContext = $this->request['api_context'];	
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