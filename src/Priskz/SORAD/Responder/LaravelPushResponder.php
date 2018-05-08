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

		// Broadcast the result to listeners.
		$this->push();

		// Finally, return the Response.
		return $this->respond();
	}

	/**
	 *	Push a message to a subscription socket.
	 */
	public function push()
	{
		$this->connect();

		// Send the message through the socket.
	    $this->socket->send(json_encode($this->result));
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