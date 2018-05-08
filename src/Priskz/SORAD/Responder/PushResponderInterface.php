<?php

namespace Priskz\SORAD\Responder;

use Priskz\SORAD\RespondeResponderInterface;

/**
 * A Responder is a simple class that generates the response for an action.
 */
interface PushResponderInterface extends ResponderInterface
{
	/**
	 *	Push a message to a subscription socket.
	 *	@return void
	 */
	public function push();
}