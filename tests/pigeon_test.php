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
     * GENERIC METHODS
     * ------------------------------------------------------------ */

	public function test_map()
	{
		$test = $this;

		Pigeon::map(function ($r) use (&$test) {
			$test->assertInstanceOf('Pigeon', $r);
		});
	}

	/* --------------------------------------------------------------
     * BASIC ROUTES
     * ------------------------------------------------------------ */

	public function test_route_works_like_ci_routes()
	{
		Pigeon::map(function($r){
			$r->route('posts/(:any)', 'posts/show/$1');
			$r->route('books', 'books/index');
		});

		$this->assertEquals(array( 'posts/(:any)' => 'posts/show/$1', 'books' => 'books/index' ), Pigeon::draw());
	}

	public function test_route_works_with_hash_to()
	{
		Pigeon::map(function($r){
			$r->route('posts/people', 'posts#action');
			$r->route('posts/(:any)', 'posts#show');
			$r->route('posts/(:any)/(:num)', 'posts#show');
		});

		$this->assertEquals(array( 'posts/people' => 'posts/action', 
								   'posts/(:any)' => 'posts/show/$1', 
								   'posts/(:any)/(:num)' => 'posts/show/$1/$2' ), Pigeon::draw());
	}

	public function test_route_works_with_array()
	{
		Pigeon::map(function($r){
			$r->route('posts/people', array( 'Posts', 'action' ));
			$r->route('posts/(:num)', array( 'Posts', 'show' ));
		});

		$this->assertEquals(array( 'posts/people' => 'posts/action', 'posts/(:num)' => 'posts/show/$1' ), Pigeon::draw());
	}

	/* --------------------------------------------------------------
     * HTTP VERB ROUTES
     * ------------------------------------------------------------ */

	public function test_get()
	{
		$_SERVER['REQUEST_METHOD'] = 'GET';

		Pigeon::map(function($r){
			$r->get('posts/(:any)', 'posts/show/$1');
			$r->get('posts/(:num)', 'posts#show');
			$r->get('posts/people', array( 'Posts', 'action' ));
		});

		$this->assertEquals(array( 'posts/(:any)' => 'posts/show/$1', 
								   'posts/people' => 'posts/action',
								   'posts/(:num)' => 'posts/show/$1' ), Pigeon::draw());
	}

	public function test_post()
	{
		$_SERVER['REQUEST_METHOD'] = 'POST';

		Pigeon::map(function($r){
			$r->post('posts/(:any)', 'posts/show/$1');
			$r->post('posts/(:num)', 'posts#show');
			$r->post('posts/people', array( 'Posts', 'action' ));
		});

		$this->assertEquals(array( 'posts/(:any)' => 'posts/show/$1', 
								   'posts/people' => 'posts/action',
								   'posts/(:num)' => 'posts/show/$1' ), Pigeon::draw());
	}

	public function test_put()
	{
		$_SERVER['REQUEST_METHOD'] = 'PUT';

		Pigeon::map(function($r){
			$r->put('posts/(:any)', 'posts/show/$1');
			$r->put('posts/(:num)', 'posts#show');
			$r->put('posts/people', array( 'Posts', 'action' ));
		});

		$this->assertEquals(array( 'posts/(:any)' => 'posts/show/$1', 
								   'posts/people' => 'posts/action',
								   'posts/(:num)' => 'posts/show/$1' ), Pigeon::draw());
	}

	public function test_delete()
	{
		$_SERVER['REQUEST_METHOD'] = 'DELETE';

		Pigeon::map(function($r){
			$r->delete('posts/(:any)', 'posts/show/$1');
			$r->delete('posts/(:num)', 'posts#show');
			$r->delete('posts/people', array( 'Posts', 'action' ));
		});

		$this->assertEquals(array( 'posts/(:any)' => 'posts/show/$1', 
								   'posts/people' => 'posts/action',
								   'posts/(:num)' => 'posts/show/$1' ), Pigeon::draw());
	}

	public function test_patch()
	{
		$_SERVER['REQUEST_METHOD'] = 'PATCH';

		Pigeon::map(function($r){
			$r->patch('posts/(:any)', 'posts/show/$1');
			$r->patch('posts/(:num)', 'posts#show');
			$r->patch('posts/people', array( 'Posts', 'action' ));
		});

		$this->assertEquals(array( 'posts/(:any)' => 'posts/show/$1', 
								   'posts/people' => 'posts/action',
								   'posts/(:num)' => 'posts/show/$1' ), Pigeon::draw());
	}

	public function test_head()
	{
		$_SERVER['REQUEST_METHOD'] = 'HEAD';

		Pigeon::map(function($r){
			$r->head('posts/(:any)', 'posts/show/$1');
			$r->head('posts/(:num)', 'posts#show');
			$r->head('posts/people', array( 'Posts', 'action' ));
		});

		$this->assertEquals(array( 'posts/(:any)' => 'posts/show/$1', 
								   'posts/people' => 'posts/action',
								   'posts/(:num)' => 'posts/show/$1' ), Pigeon::draw());
	}

	public function test_options()
	{
		$_SERVER['REQUEST_METHOD'] = 'OPTIONS';

		Pigeon::map(function($r){
			$r->options('posts/(:any)', 'posts/show/$1');
			$r->options('posts/(:num)', 'posts#show');
			$r->options('posts/people', array( 'Posts', 'action' ));
		});

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

		Pigeon::map(function($r){
			$r->resources('books');
		});

		$this->assertEquals(array( 'books' => 'books/index', 
								   'books/([a-zA-Z0-9\-_]+)' => 'books/show/$1',
								   'books/([a-zA-Z0-9\-_]+)/edit' => 'books/edit/$1',
								   'books/new' => 'books/create_new' ), Pigeon::draw());

		$_SERVER['REQUEST_METHOD'] = 'POST';

		Pigeon::clear();
		Pigeon::map(function($r){
			$r->resources('books');
		});

		$this->assertEquals(array( 'books' => 'books/create' ), Pigeon::draw());

		$_SERVER['REQUEST_METHOD'] = 'PUT';

		Pigeon::clear();
		Pigeon::map(function($r){
			$r->resources('books');
		});

		$this->assertEquals(array( 'books/([a-zA-Z0-9\-_]+)' => 'books/update/$1' ), Pigeon::draw());

		$_SERVER['REQUEST_METHOD'] = 'DELETE';

		Pigeon::clear();
		Pigeon::map(function($r){
			$r->resources('books');
		});

		$this->assertEquals(array( 'books/([a-zA-Z0-9\-_]+)' => 'books/delete/$1' ), Pigeon::draw());
	}

	public function test_resource()
	{
		$_SERVER['REQUEST_METHOD'] = 'GET';

		Pigeon::map(function($r){
			$r->resource('profile');
		});

		$this->assertEquals(array( 'profile' => 'profile/show',
								   'profile/new' => 'profile/create_new',
								   'profile/edit' => 'profile/edit' ), Pigeon::draw());

		$_SERVER['REQUEST_METHOD'] = 'POST';

		Pigeon::clear();
		Pigeon::map(function($r){
			$r->resource('profile');
		});

		$this->assertEquals(array( 'profile' => 'profile/create' ), Pigeon::draw());

		$_SERVER['REQUEST_METHOD'] = 'PUT';

		Pigeon::clear();
		Pigeon::map(function($r){
			$r->resource('profile');
		});

		$this->assertEquals(array( 'profile' => 'profile/update' ), Pigeon::draw());

		$_SERVER['REQUEST_METHOD'] = 'DELETE';

		Pigeon::clear();
		Pigeon::map(function($r){
			$r->resource('profile');
		});

		$this->assertEquals(array( 'profile' => 'profile/delete' ), Pigeon::draw());
	}

	/* --------------------------------------------------------------
     * NESTED RESOURCES
     * ------------------------------------------------------------ */

	public function test_basic_route_works_nested()
	{
		Pigeon::map(function($r){
			$r->route('posts/(:num)', 'posts#show', function($r){
				$r->route('comments', 'comments#show');
				$r->route('assets/(:num)', 'assets#show');
			});
		});

		$this->assertEquals(array( 'posts/(:num)' => 'posts/show/$1', 
								   'posts/(:num)/comments' => 'comments/show/$1',
								   'posts/(:num)/assets/(:num)' => 'assets/show/$1/$2' ), Pigeon::draw());
	}

	public function test_resources_can_be_nested()
	{
		$_SERVER['REQUEST_METHOD'] = 'GET';

		Pigeon::map(function($r){
			$r->resources('books', function($r){
				$r->resources('authors');
			});
		});

		$this->assertEquals(array( 'books' => 'books/index', 
								   'books/([a-zA-Z0-9\-_]+)' => 'books/show/$1',
								   'books/([a-zA-Z0-9\-_]+)/edit' => 'books/edit/$1',
								   'books/new' => 'books/create_new',
								   'books/([a-zA-Z0-9\-_]+)/authors' => 'authors/index/$1',
								   'books/([a-zA-Z0-9\-_]+)/authors/([a-zA-Z0-9\-_]+)' => 'authors/show/$1/$2',
								   'books/([a-zA-Z0-9\-_]+)/authors/([a-zA-Z0-9\-_]+)/edit' => 'authors/edit/$1/$2',
								   'books/([a-zA-Z0-9\-_]+)/authors/new' => 'authors/create_new/$1' ), Pigeon::draw());
	}

	public function test_singular_resources_can_be_nested()
	{
		$_SERVER['REQUEST_METHOD'] = 'GET';

		Pigeon::map(function($r){
			$r->resource('profile', function($r){
				$r->get('rescan', 'profile#rescan');
			});
		});

		$this->assertEquals(array( 'profile' => 'profile/show',
								   'profile/new' => 'profile/create_new',
								   'profile/edit' => 'profile/edit',
								   'profile/rescan' => 'profile/rescan' ), Pigeon::draw());
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

	public function test_parameterfy()
	{
		$this->assertEquals('some/to/string/$1', Pigeon::parameterfy('posts/(:num)', 'some/to/string'));
		$this->assertEquals('blah/blah/$1/$2/$3', Pigeon::parameterfy('books/(:num)/blah/(:any)/(:num)', 'blah/blah'));
	}
}