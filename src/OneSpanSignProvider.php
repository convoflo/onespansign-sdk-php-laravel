<?php

namespace alexdemers\OneSpanSign;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;
use alexdemers\OneSpanSign\Client;

/**
 * Class OneSpanSignProvider
 * @package alexdemers\OneSpanSign
 */
class OneSpanSignProvider extends ServiceProvider
{
	/**
	 * @inheritDoc
	 */
	public function register()
	{
		$this->app->singleton('onespansign', function (Application $app) {
			$config = $app['config']->get('services.onespansign');
			return new Client($config['api_key'] ?? null, $config['base_url'] ? rtrim($config['base_url'], '/') : null);
		});
	}

	/**
	 * @inheritDoc
	 */
	public function provides()
	{
		return ['onespansign'];
	}
}
