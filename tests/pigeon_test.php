<?php
/**
 * Intelligent, elegant routing for CodeIgniter
 *
 * @link http://github.com/jamierumbelow/pigeon
 * @copyright Copyright (c) 2012, Jamie Rumbelow <http://jamierumbelow.net>
 */

require_once 'libraries/pigeon.php';

class Pigeon_test extends PHPUnit_Framework_TestCase
{

	/* --------------------------------------------------------------
     * VARIABLES
     * ------------------------------------------------------------ */

	protected $pigeon;

	/* --------------------------------------------------------------
     * TEST INFRASTRUCTURE
     * ------------------------------------------------------------ */

	public function tearDown()
	{
		Pigeon::clear();
	}

	/* --------------------------------------------------------------
     * BASIC ROUTES
     * ------------------------------------------------------------ */

	public function test_route_works_like_ci_routes()
	{
		Pigeon::route('posts/(:any)', 'posts/show/$1');
		Pigeon::route('books', 'books/index');

		$this->assertEquals(array( 'posts/(:any)' => 'posts/show/$1', 'books' => 'books/index' ), Pigeon::draw());
	}

	public function test_route_works_with_hash_to()
	{
		Pigeon::route('posts/people', 'posts#action');
		Pigeon::route('posts/(:any)', 'posts#show');
		Pigeon::route('posts/(:any)/(:num)', 'posts#show');

		$this->assertEquals(array( 'posts/people' => 'posts/action', 
								   'posts/(:any)' => 'posts/show/$1', 
								   'posts/(:any)/(:num)' => 'posts/show/$1/$2' ), Pigeon::draw());
	}

	public function test_route_works_with_array()
	{
		Pigeon::route('posts/people', array( 'Posts', 'action' ));
		Pigeon::route('posts/(:num)', array( 'Posts', 'show' ));

		$this->assertEquals(array( 'posts/people' => 'posts/action', 'posts/(:num)' => 'posts/show/$1' ), Pigeon::draw());
	}

	/* --------------------------------------------------------------
     * UTILITY FUNCTIONS
     * ------------------------------------------------------------ */

	public function test_clear()
	{
		Pigeon::$routes = 'TEST';

		Pigeon::clear();

		$this->assertTrue(is_array(Pigeon::$routes));
		$this->assertEmpty(Pigeon::$routes);
	}

	public function test_draw()
	{
		Pigeon::$routes = array( 'some', 'array', 'here' );

		$this->assertEquals(Pigeon::$routes, Pigeon::draw());
	}
}