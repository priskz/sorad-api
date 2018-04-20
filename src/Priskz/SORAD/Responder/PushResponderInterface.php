<?php namespace Priskz\SORAD\Responder;

use Priskz\SORAD\RespondeResponderInterface;

/**
 * A Responder is a simple class that generates the response for an action.
 */
interface PushResponderInterface extends ResponderInterface
{
	/**
	 *	Push a message to a subscription socket.
	 *	@param $string $message json
	 *	@return void
	 */
	public function push($message);
}