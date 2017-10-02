<?php

namespace App\Traits;

trait SingletonTrait
{
	/**
	 * @var static[]
	 */
	protected static $instances = [];

	/**
	 * Create static instance of class.
	 * @return static
	 */
	public static function instance()
	{
		$className = get_called_class();

		if (!isset(static::$instances[$className]))
		{
			static::$instances[$className] = new static;
		}

		return static::$instances[$className];
	}
}