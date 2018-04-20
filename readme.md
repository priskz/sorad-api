# SORAD API Package

## Purpose

A service oriented API built around the Responder-Action-Domain pattern.

## Install via Composer

Add the following to your "require" schema:

```
"require": {
     "priskz/sorad-api": "~0.0.1"
}
```

Run: ```composer update```

Add ```'SORAD\Laravel\APIServiceProvider'``` to the ```'providers'``` in ```/app/laravel/config/app.php``` to enable the newly added service.

Run: ```php artisan vendor:publish``` to publish the configuration file in ```/app/laravel/config/``` directory.

Register SORAD API based modules via the ```/app/laravel/config/sorad.php``` modules array. These entries should be the directory path to the modules. PSR4 autoloading recommended. These modules will require a routes.php file.

Example:
```
'modules' => [
    'API/Account',
    'API/Admin',
    'API/Auth',
    'API/Front',
],
```
In the above example, we have a dedicated API directory that holds modules.