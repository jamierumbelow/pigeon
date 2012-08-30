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

	public $temporary_routes = array();
	public $namespace = '';

	/* --------------------------------------------------------------
     * GENERIC METHODS
     * ------------------------------------------------------------ */

	public function __construct($namespace = FALSE)
	{
		if ($namespace)
		{
			$this->namespace = $namespace;
		}
	}

	public static function map($callback)
	{
		$pigeon = new Pigeon();
		call_user_func_array($callback, array( &$pigeon ));

		self::$routes = $pigeon->temporary_routes;
	}

	/* --------------------------------------------------------------
     * BASIC ROUTING
     * ------------------------------------------------------------ */

	public function route($from, $to, $nested = FALSE)
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

		// Do we have a namespace?
		if ($this->namespace)
		{
			$from = $this->namespace . '/' . $from;
		}

		// Account for parameters in the URL if we need to
		if ($parameterfy)
		{
			$to = $this->parameterfy($from, $to);
		}

		// Apply our routes
		$this->temporary_routes[$from] = $to;

		// Do we have a nesting function?
		if ($nested && is_callable($nested))
		{
			$nested_pigeon = new Pigeon($from);
			call_user_func_array($nested, array( &$nested_pigeon ));
			$this->temporary_routes = array_merge($this->temporary_routes, $nested_pigeon->temporary_routes);
		}
	}

	/* --------------------------------------------------------------
     * HTTP VERB ROUTING
     * ------------------------------------------------------------ */

	public function get($from, $to)
	{
		if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'GET')
		{
			$this->route($from, $to);
		}
	}

	public function post($from, $to)
	{
		if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'POST')
		{
			$this->route($from, $to);
		}
	}

	public function put($from, $to)
	{
		if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'PUT')
		{
			$this->route($from, $to);
		}
	}

	public function delete($from, $to)
	{
		if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'DELETE')
		{
			$this->route($from, $to);
		}
	}

	public function patch($from, $to)
	{
		if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'PATCH')
		{
			$this->route($from, $to);
		}
	}

	public function head($from, $to)
	{
		if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'HEAD')
		{
			$this->route($from, $to);
		}
	}

	public function options($from, $to)
	{
		if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] == 'OPTIONS')
		{
			$this->route($from, $to);
		}
	}

	/* --------------------------------------------------------------
     * RESTFUL ROUTING
     * ------------------------------------------------------------ */

	public function resources($name, $nested = FALSE)
	{
		$this->get($name, $name . '#index');
		$this->get($name . '/new', $name . '#create_new');
		$this->get($name . '/([a-zA-Z0-9\-_]+)/edit', $name . '#edit');
		$this->get($name . '/([a-zA-Z0-9\-_]+)', $name . '#show');
		$this->post($name, $name . '#create');
		$this->put($name . '/([a-zA-Z0-9\-_]+)', $name . '#update');
		$this->delete($name . '/([a-zA-Z0-9\-_]+)', $name . '#delete');

		if ($nested && is_callable($nested))
		{
			$nested_pigeon = new Pigeon($name . '/([a-zA-Z0-9\-_]+)');
			call_user_func_array($nested, array( &$nested_pigeon ));
			$this->temporary_routes = array_merge($this->temporary_routes, $nested_pigeon->temporary_routes);
		}
	}

	public function resource($name, $nested = FALSE)
	{
		$this->get($name, $name . '/show');
		$this->get($name . '/new', $name . '/create_new');
		$this->get($name . '/edit', $name . '/edit');
		$this->post($name, $name . '/create');
		$this->put($name, $name . '/update');
		$this->delete($name, $name . '/delete');

		if ($nested && is_callable($nested))
		{
			$nested_pigeon = new Pigeon($name);
			call_user_func_array($nested, array( &$nested_pigeon ));
			$this->temporary_routes = array_merge($this->temporary_routes, $nested_pigeon->temporary_routes);
		}
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

	/**
	 * Extract the URL parameters from $from and copy to $to
	 */
	public static function parameterfy($from, $to)
	{
		if (preg_match_all('/\/\((.*?)\)/', $from, $matches))
		{
			$params = '';

			foreach ($matches[1] as $i => $match)
			{
				$i = $i + 1;
				$params .= "/\$$i";
			}

			$to .= $params;
		}

		return $to;
	}
}