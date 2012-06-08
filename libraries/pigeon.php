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
		if (preg_match('/^([a-zA-Z\_\-0-9\/]+)#([a-zA-Z\_\-0-9\/]+)$/m', $to, $matches))
		{
			$to = $matches[1] . '/' . $matches[2];

			if (preg_match_all('/\/\((.*?)\)/', $from, $matches))
			{
				$params = '';

				foreach ($matches[1] as $i => $match)
				{
					$i++;
					$params .= "/\$$i";
				}

				$to .= $params;
			}
		}

		self::$routes[$from] = $to;
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