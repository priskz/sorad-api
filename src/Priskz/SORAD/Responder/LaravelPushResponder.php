<?php

namespace Priskz\SORAD\Responder;

use ZMQ, ZMQContext;

/**
 * A Responder is a simple class that generates the response for an action.
 */
class LaravelPushResponder extends LaravelResponder implements PushResponderInterface
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
	 * Broadcasted Payload
	 */
	protected $broadcast;

	/**
	 *	ZMQSocket Configuration
	 */
	protected $connection;
	protected $ioThreadTotal;
	protected $persistent;
	protected $persistenceKey;
	protected $socketType = ZMQ::SOCKET_PUSH;

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

		// Build the broadcasted value.
		$this->buildBroadcast();

		// Broadcast the result to listeners.
		$this->push();

		// Finally, return the Response.
		return $this->respond();
	}

	/**
	 *
	 */
	public function buildResponse()
	{
		$this->setResponse(response($this->body, $this->code));

		$this->response->withHeaders(array_merge($this->header, ['Content-Type', $this->type]));
	}

	/**
	 * Set the result as the default broadcast payload.
	 */
	public function buildBroadcast()
	{
		$this->setBroadcast($this->result);
	}

	/**
	 * Set the result property.
	 */
	protected function setBroadcast(Payload $payload)
	{
		$this->result = $payload;
	}

	/**
	 *	Push a message to a subscription socket.
	 */
	public function push()
	{
		if(isset($this->broadcast) && ! is_null($this->broadcast))
		{
			// Connect to socket server.
			$this->connect();

			// Send the message through the socket.
		    $this->socket->send(json_encode($this->broadcast));
		}
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
}