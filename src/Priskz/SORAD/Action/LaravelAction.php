<?php

namespace Priskz\SORAD\Action;

use App;
use Priskz\SORAD\Action\GenericAbstractAction;
use Priskz\SORAD\Action\Processor\ProcessorInterface;

class LaravelAction extends GenericAbstractAction
{
	public function __construct(ProcessorInterface $processor = null)
	{
		// If a custom processor is not given then use the default generic Laravel\Processor.
		if($processor === null)
		{
			$processor = App::make('Priskz\SORAD\Action\Processor\Laravel\Processor');
		}

		parent::__construct($processor);
	}
}