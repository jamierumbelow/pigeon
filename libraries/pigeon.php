<?php
/**
 * Intelligent, elegant routing for CodeIgniter
 *
 * @link http://github.com/jamierumbelow/pigeon
 * @copyright Copyright (c) 2012, Jamie Rumbelow <http://jamierumbelow.net>
 */

class Pigeon
{
	
	/* --------------------------------------------------------------
     * VARIABLES
     * ------------------------------------------------------------ */

	public static $routes = array();

	/* --------------------------------------------------------------
     * BASIC ROUTING
     * ------------------------------------------------------------ */

	public static function route($from, $to)
	{
		$parameterfy = FALSE;

		// Allow for array based routes and hashrouters
		if (is_array($to))
		{
			$to = strtolower($to[0]) . '/' . strtolower($to[1]);
			$parameterfy = TRUE;
		}
		elseif (preg_match('/^([a-zA-Z\_\-0-9\/]+)#([a-zA-Z\_\-0-9\/]+)$/m', $to, $matches))
		{
			$to = $matches[1] . '/' . $matches[2];
			$parameterfy = TRUE;
		}

		// Account for parameters in the URL if we need to
		if ($parameterfy && preg_match_all('/\/\((.*?)\)/', $from, $matches))
			{
				$params = '';

				foreach ($matches[1] as $i => $match)
				{
					$i++;
					$params .= "/\$$i";
				}

				$to .= $params;
			}

		self::$routes[$from] = $to;
	}

	/* --------------------------------------------------------------
     * HTTP VERB ROUTING
     * ------------------------------------------------------------ */

	public static function get($from, $to)
	{
		if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'GET')
		{
			self::route($from, $to);
		}
	}

	public static function post($from, $to)
	{
		if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST')
		{
			self::route($from, $to);
		}
	}

	public static function put($from, $to)
	{
		if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'PUT')
		{
			self::route($from, $to);
		}
	}

	public static function delete($from, $to)
	{
		if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'DELETE')
		{
			self::route($from, $to);
		}
	}

	public static function patch($from, $to)
	{
		if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'PATCH')
		{
			self::route($from, $to);
		}
	}

	public static function head($from, $to)
	{
		if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'HEAD')
		{
			self::route($from, $to);
		}
	}

	public static function options($from, $to)
	{
		if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'OPTIONS')
		{
			self::route($from, $to);
		}
	}

	/* --------------------------------------------------------------
     * RESTFUL ROUTING
     * ------------------------------------------------------------ */

	public static function resources($name)
	{
		self::get($name, $name . '/index');
		self::get($name . '/new', $name . '/new');
		self::get($name . '/(:any)/edit', $name . '/edit/$1');
		self::get($name . '/(:any)', $name . '/show/$1');
		self::post($name, $name . '/create');
		self::put($name . '/(:any)', $name . '/update/$1');
		self::delete($name . '/(:any)', $name . '/delete/$1');
	}

	public static function resource($name)
	{
		self::get($name, $name . '/show');
		self::get($name . '/new', $name . '/new');
		self::get($name . '/edit', $name . '/edit');
		self::post($name, $name . '/create');
		self::put($name, $name . '/update');
		self::delete($name, $name . '/delete');
	}

	/* --------------------------------------------------------------
     * UTILITY FUNCTIONS
     * ------------------------------------------------------------ */

	/**
	 * Clear out the routing table
	 */
	public static function clear()
	{
		self::$routes = array();
	}

	/**
	 * Return the routes array
	 */
	public static function draw()
	{
		return self::$routes;
	}
}