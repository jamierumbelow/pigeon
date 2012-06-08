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