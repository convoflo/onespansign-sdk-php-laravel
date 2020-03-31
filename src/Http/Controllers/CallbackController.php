<?php

namespace alexdemers\OneSpanSign\Http\Controllers;

use alexdemers\OneSpanSign\Http\Middleware\VerifySecureCallbackKey;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Str;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class CallbackController
 *
 * Heavily inspirted by Laravel's Cashier Webhook controller
 *
 * @package alexdemers\OneSpanSign\Http\Controllers
 */
abstract class CallbackController extends Controller
{
	/** @var Request */
	protected $request = null;

	protected $callback_key = null;

	/**
	 * Create a new WebhookController instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		if (config('services.onespansign.callback_key')) {
			$this->middleware(VerifySecureCallbackKey::class);
		}
	}

	/**
	 * @param Request $request
	 * @return Response
	 */
	public function __invoke(Request $request)
	{
		$this->request = $request;
		$payload = json_decode($this->request->getContent(), true);
		$method = 'handle' . Str::studly($payload['name']);

		if (method_exists($this, $method)) {
			return $this->{$method}($payload);
		}

		return $this->missingMethod();
	}

	/**
	 * Handle successful calls on the controller.
	 *
	 * @param array $parameters
	 * @return Response
	 */
	protected function successMethod($parameters = [])
	{
		return new Response('Webhook Handled', 200);
	}

	/**
	 * Handle calls to missing methods on the controller.
	 *
	 * @param array $parameters
	 * @return Response
	 */
	protected function missingMethod($parameters = [])
	{
		return new Response();
	}
}
