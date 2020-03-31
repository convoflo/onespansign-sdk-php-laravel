<?php

namespace alexdemers\OneSpanSign\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Facade for OneSpanSign
 * @package alexdemers\OneSpanSign\Facades
 */
class OneSpanSign extends Facade
{
	/**
	 * @return string
	 */
	protected static function getFacadeAccessor()
	{
		return 'onespansign';
	}

}
