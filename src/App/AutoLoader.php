<?php

namespace App;

class AutoLoader
{
	/**
	 * AutoLoader constructor.
	 */
	private function __construct() {}

	/**
	 * Register auto loader.
	 */
	public static function register()
	{
		spl_autoload_register(function($className)
		{
			$className = str_replace('\\', '/', $className);

			$filename = __DIR__ . '/../' . $className . '.php';
			if (is_readable($filename))
			{
				require($filename . '');
			}
		});
	}
}