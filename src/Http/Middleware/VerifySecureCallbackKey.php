<?php

namespace alexdemers\OneSpanSign\Http\Middleware;

use Closure;
use Illuminate\Contracts\Config\Repository as Config;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

/**
 * Class VerifySecureCallbackKey
 * @package alexdemers\OneSpanSign\Http\Middleware
 */
class VerifySecureCallbackKey
{
	/**
	 * The application instance.
	 *
	 * @var Application
	 */
	protected $app;
	/**
	 * The configuration repository instance.
	 *
	 * @var Repository
	 */
	protected $config;

	/**
	 * Create a new middleware instance.
	 *
	 * @param Application $app
	 * @param Config $config
	 */
	public function __construct(Application $app, Config $config)
	{
		$this->app = $app;
		$this->config = $config;
	}

	/**
	 * @param Request $request
	 * @param Closure $next
	 * @param string|null $callback_key
	 * @return mixed
	 */
	public function handle(Request $request, Closure $next, ?string $callback_key = null )
	{
		$request_key = Str::after($request->header('Authorization'), 'Basic ');
		$callback_key = $this->getCallbackKey($request, $callback_key);

		if ($request_key !== $callback_key) {
            abort(403);
        }

		return $next($request);
	}

	/**
	 * This can be overridden
	 *
	 * @param Request $request
	 * @param string|null $callback_key
	 * @return mixed
	 */
	protected function getCallbackKey(Request $request, ?string $callback_key = null)
	{
		return $callback_key ?: $this->config->get('services.onespansign.callback_key');
	}
}
