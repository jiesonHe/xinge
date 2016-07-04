<?php

namespace Gutplushe\ApnsPHP;

use Illuminate\Support\ServiceProvider;

class XingeAppServiceProvider extends ServiceProvider
{

	/**
	 * boot process
	 */
	public function boot()
	{
		$this->publishes([
			__DIR__ . '/config/config.php' => config_path('gutplushe-xinge.php'),
		], 'config');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->mergeConfigFrom(__DIR__ . '/config/config.php', 'gutplushe-xinge');

		$this->app->singleton('xingePush.ios', function ($app) {

			$config = $app->config->get('gutplushe-xinge');

			return new IosPush($config['ios']['accessId'], $config['ios']['secretKey']);
		});

		$this->app->singleton('xingePush.android', function ($app) {

			$config = $app->config->get('gutplushe-xinge');

			return new AndroidPush($config['android']['accessId'], $config['android']['secretKey']);
		});
	}

	/**
	 * Get the services provided by the provider.
	 *
	 * @return array
	 */
	public function provides()
	{
		return [
			'xingePush.ios',
			'xingePush.android',
		];
	}
}
