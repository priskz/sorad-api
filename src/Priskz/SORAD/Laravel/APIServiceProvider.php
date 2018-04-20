<?php namespace Priskz\SORAD\Laravel;

use Config;

// @todo: Discovery & Remove
// 
// use Illuminate\Support\Facades\Config;
// use Illuminate\Support\Facades\Route;
// use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class APIServiceProvider extends \Illuminate\Support\ServiceProvider
{
	/**
	 * Indicates if loading of the provider is deferred.
	 *
	 * @var bool
	 */
	protected $defer = false;

	/**
	 * Default Routes File Name
	 *
	 * @var string
	 */
	const ROUTES_FILE_NAME = 'routes.php';

	/**
	 * Default Routes Class Name
	 *
	 * @var string
	 */
	const ROUTES_CLASS_NAME = 'Routes';

	/**
	 *	Boot
	 */
	public function boot()
	{
		// Publish Config File
	    $this->publishes([
	    	realpath(__DIR__ . '/..') . '/config/Laravel/api.php' => config_path('sorad/api.php')
	    ]);

	    // Load routes.
	    $this->loadRoutes();
	}

	/**
	 * Load Routes
	 *
	 * @return string
	 */
	public function loadRoutes()
	{
		$this->loadRoutesClasses();
		$this->includeRoutesFiles();
	}

	/**
	 * Include Routes Files
	 *
	 * @return void
	 */
	public function includeRoutesFiles()
	{
		// Application Specific Module Route File Locations
		if(Config::get('sorad.api.routes.file') && is_array(Config::get('sorad.api.routes.file')))
		{
			foreach(Config::get('sorad.api.routes.file') as $route)
			{
				// Check if is an app directory and use default routes file name.
				if(is_dir(app_path($route)))
				{
					$this->loadRoutesFrom(app_path($route . '/' . $this->getRouteFileName()));
				}
				elseif(is_file(app_path($route)))
				{
					$this->loadRoutesFrom(app_path($route));
				}
			}
		}
	}

	/**
	 * Load Routes Classes
	 *
	 * @return void
	 */
	public function loadRoutesClasses()
	{
		// Application Specific Module Route File Locations
		if(Config::get('sorad.api.routes.class') && is_array(Config::get('sorad.api.routes.class')))
		{
			foreach(Config::get('sorad.api.routes.class') as $route => $config)
			{
				$route = sprintf('\\%s', $route);

				if(class_exists($route))
				{
					$route::load($config);
				}
				elseif(class_exists(sprintf('%s\\%s', $route,$this->getRouteClassName())))
				{
					sprintf('%s\\%s', $route,$this->getRouteClassName())::load($config);
				}
			}
		}
	}

	/**
	 * Get Route File Name
	 *
	 * @return string
	 */
	public static function getRouteFileName()
	{
		return self::ROUTES_FILE_NAME; 
	}

	/**
	 * Get Routes Class Name
	 *
	 * @return string
	 */
	public static function getRouteClassName()
	{
		return self::ROUTES_CLASS_NAME; 
	}
}