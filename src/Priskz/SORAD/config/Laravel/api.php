<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Route File\Class Locations
	|--------------------------------------------------------------------------
	|
	*/

	'routes' => [

		// Autoloaded directories within /app where a routes.php file exists to include.
		'file'  => [],

		// Autoloaded directories where a SORAD Routes class exists to load().
		'class' => [
			// 'Priskz\SORAD\Front\API\Laravel'   => ['prefix' => '/',       'middleware' => ['web']],
			// 'Priskz\SORAD\Account\API\Laravel' => ['prefix' => 'account', 'middleware' => ['web', 'auth']],
			// 'Priskz\SORAD\Auth\API\Laravel'    => ['prefix' => 'auth',    'middleware' => ['web']],
			// 'Priskz\SORAD\Admin\API\Laravel'   => ['prefix' => 'admin',   'middleware' => ['web', 'auth']],
			// 'Priskz\SORAD\CMS\API\Laravel'     => ['prefix' => 'content', 'middleware' => ['web']],
		],
	]
];