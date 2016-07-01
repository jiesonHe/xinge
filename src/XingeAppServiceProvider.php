<?php

namespace Gutplushe\ApnsPHP;

use Illuminate\Support\ServiceProvider;

class XingeServiceProvider extends ServiceProvider
{

	/**
	 * boot process
	 */
	public function boot()
	{
		$this->publishes([
			__DIR__ . '/config/config.php' => config_path('gutplushe-xingePush.php'),
		], 'config');
	}

	/**
	 * Register the service provider.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->mergeConfigFrom(__DIR__ . '/config/config.php', 'gutplushe-xingePush');

		$this->app->singleton('xingePush.ios', function ($app) {

			$config = $app->config->get('gutplushe-xingePush');

			return new XingeApp($config['ios']['accessId'], $config['ios']['secretKey']);
		});

		$this->app->singleton('xingePush.android', function ($app) {

			$config = $app->config->get('gutplushe-xingePush');

			return new XingeApp($config['android']['accessId'], $config['android']['secretKey']);
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
