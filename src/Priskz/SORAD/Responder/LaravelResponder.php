<?php

namespace Priskz\SORAD\Responder;

use Input, Route;
use Priskz\Payload\Payload;
use Priskz\SORAD\Responder\AbstractResponder;
use Priskz\SORAD\Responder\ResponderInterface;

/**
 * Class LaravelResponder.
 *
 * @author Zachary Prisk <zachary.prisk@gmail.com>
 */
abstract class LaravelResponder extends AbstractResponder implements ResponderInterface
{
	/**
	 *	@var  string
	 */
	protected $type = self::HEADER_JSON;

	/**
	 *
	 */
	public function buildResponse()
	{
		$this->setResponse(response($this->body, $this->code));

		$this->response->withHeaders(array_merge($this->header, ['Content-Type', $this->type]));
	}

	/**
	 *	Get Request Data
	 */
	public function parseRequest()
	{
		$this->request = Input::all();

		$uri = Route::getCurrentRoute()->parametersWithoutNulls();

		if($uri)
		{
			$this->request = array_merge($uri, $this->request);
		}

		$this->setApiContext();
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