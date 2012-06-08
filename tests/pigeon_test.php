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
     * HTTP VERB ROUTES
     * ------------------------------------------------------------ */

	public function test_get()
	{
		$_SERVER['REQUEST_METHOD'] = 'GET';

		Pigeon::get('posts/(:any)', 'posts/show/$1');
		Pigeon::get('posts/(:num)', 'posts#show');
		Pigeon::get('posts/people', array( 'Posts', 'action' ));

		$this->assertEquals(array( 'posts/(:any)' => 'posts/show/$1', 
								   'posts/people' => 'posts/action',
								   'posts/(:num)' => 'posts/show/$1' ), Pigeon::draw());
	}

	public function test_post()
	{
		$_SERVER['REQUEST_METHOD'] = 'POST';

		Pigeon::post('posts/(:any)', 'posts/show/$1');
		Pigeon::post('posts/(:num)', 'posts#show');
		Pigeon::post('posts/people', array( 'Posts', 'action' ));

		$this->assertEquals(array( 'posts/(:any)' => 'posts/show/$1', 
								   'posts/people' => 'posts/action',
								   'posts/(:num)' => 'posts/show/$1' ), Pigeon::draw());
	}

	public function test_put()
	{
		$_SERVER['REQUEST_METHOD'] = 'PUT';

		Pigeon::put('posts/(:any)', 'posts/show/$1');
		Pigeon::put('posts/(:num)', 'posts#show');
		Pigeon::put('posts/people', array( 'Posts', 'action' ));

		$this->assertEquals(array( 'posts/(:any)' => 'posts/show/$1', 
								   'posts/people' => 'posts/action',
								   'posts/(:num)' => 'posts/show/$1' ), Pigeon::draw());
	}

	public function test_delete()
	{
		$_SERVER['REQUEST_METHOD'] = 'DELETE';

		Pigeon::delete('posts/(:any)', 'posts/show/$1');
		Pigeon::delete('posts/(:num)', 'posts#show');
		Pigeon::delete('posts/people', array( 'Posts', 'action' ));

		$this->assertEquals(array( 'posts/(:any)' => 'posts/show/$1', 
								   'posts/people' => 'posts/action',
								   'posts/(:num)' => 'posts/show/$1' ), Pigeon::draw());
	}

	public function test_patch()
	{
		$_SERVER['REQUEST_METHOD'] = 'PATCH';

		Pigeon::patch('posts/(:any)', 'posts/show/$1');
		Pigeon::patch('posts/(:num)', 'posts#show');
		Pigeon::patch('posts/people', array( 'Posts', 'action' ));

		$this->assertEquals(array( 'posts/(:any)' => 'posts/show/$1', 
								   'posts/people' => 'posts/action',
								   'posts/(:num)' => 'posts/show/$1' ), Pigeon::draw());
	}

	public function test_head()
	{
		$_SERVER['REQUEST_METHOD'] = 'HEAD';

		Pigeon::head('posts/(:any)', 'posts/show/$1');
		Pigeon::head('posts/(:num)', 'posts#show');
		Pigeon::head('posts/people', array( 'Posts', 'action' ));

		$this->assertEquals(array( 'posts/(:any)' => 'posts/show/$1', 
								   'posts/people' => 'posts/action',
								   'posts/(:num)' => 'posts/show/$1' ), Pigeon::draw());
	}

	public function test_options()
	{
		$_SERVER['REQUEST_METHOD'] = 'OPTIONS';

		Pigeon::options('posts/(:any)', 'posts/show/$1');
		Pigeon::options('posts/(:num)', 'posts#show');
		Pigeon::options('posts/people', array( 'Posts', 'action' ));

		$this->assertEquals(array( 'posts/(:any)' => 'posts/show/$1', 
								   'posts/people' => 'posts/action',
								   'posts/(:num)' => 'posts/show/$1' ), Pigeon::draw());
	}

	/* --------------------------------------------------------------
     * RESTFUL ROUTES
     * ------------------------------------------------------------ */

	public function test_resources()
	{
		$_SERVER['REQUEST_METHOD'] = 'GET';

		Pigeon::resources('books');

		$this->assertEquals(array( 'books' => 'books/index', 
								   'books/(:any)' => 'books/show/$1',
								   'books/(:any)/edit' => 'books/edit/$1',
								   'books/new' => 'books/new' ), Pigeon::draw());

		$_SERVER['REQUEST_METHOD'] = 'POST';

		Pigeon::clear();
		Pigeon::resources('books');

		$this->assertEquals(array( 'books' => 'books/create' ), Pigeon::draw());

		$_SERVER['REQUEST_METHOD'] = 'PUT';

		Pigeon::clear();
		Pigeon::resources('books');

		$this->assertEquals(array( 'books/(:any)' => 'books/update/$1' ), Pigeon::draw());

		$_SERVER['REQUEST_METHOD'] = 'DELETE';

		Pigeon::clear();
		Pigeon::resources('books');

		$this->assertEquals(array( 'books/(:any)' => 'books/delete/$1' ), Pigeon::draw());
	}

	public function test_resource()
	{
		$_SERVER['REQUEST_METHOD'] = 'GET';

		Pigeon::resource('profile');

		$this->assertEquals(array( 'profile' => 'profile/show',
								   'profile/new' => 'profile/new',
								   'profile/edit' => 'profile/edit' ), Pigeon::draw());

		$_SERVER['REQUEST_METHOD'] = 'POST';

		Pigeon::clear();
		Pigeon::resource('profile');

		$this->assertEquals(array( 'profile' => 'profile/create' ), Pigeon::draw());

		$_SERVER['REQUEST_METHOD'] = 'PUT';

		Pigeon::clear();
		Pigeon::resource('profile');

		$this->assertEquals(array( 'profile' => 'profile/update' ), Pigeon::draw());

		$_SERVER['REQUEST_METHOD'] = 'DELETE';

		Pigeon::clear();
		Pigeon::resource('profile');

		$this->assertEquals(array( 'profile' => 'profile/delete' ), Pigeon::draw());
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