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