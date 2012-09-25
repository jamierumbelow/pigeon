# Pigeon
### Intelligent, elegant routing for CodeIgniter

CodeIgniter's routing engine is _far too basic_. Pigeon wraps around the core routing system to provide HTTP method based routing, RESTful resources and nested routes. It uses a natural DSL to make writing cleverer routes simple and elegant.

## Synopsis

	Pigeon::map(function($r){
		$r->route('posts/(:num)', 'posts/show/$1');

		$r->get('posts', array( 'Posts', 'index' ));
		$r->post('posts', 'Posts#create' );
		$r->put('posts/(:num)', array( 'Posts', 'update' ));
		$r->delete('posts/(:num)', array( 'Posts', 'delete' ));

		$r->resources('posts');

		$r->resources('posts', function($r){
			$r->resources('comments');
		});
	});

	$route = Pigeon::draw();

## Installation

Install with [Composer](http://getcomposer.org/). Install Composer for your project:

    $ curl -s http://getcomposer.org/installer | php

...and create/edit your `composer.json`:

    {
        "require": {
            "jamierumbelow/pigeon": "*"
        }
    }

...and install it!

    $ php composer.phar install

Remember to include Composer's autoload file in `index.php`:

    require_once './vendor/autoload.php';

Alternatively, download and drag the **Pigeon.php** file into your _application/libraries_ folder. Autoload the library and away you go.

## How It Works

You define your routes using Pigeon's DSL inside your *config/routes.php* file. Pigeon builds up the routes array internally; to expose it to CodeIgniter, you need to set the standard `$route` variable using `draw()`:

	$route = Pigeon::draw();

Remember to do this **after you define your routes**.

## Basic routing

The most basic routing mechanism is the `route` method. You can pass through a traditional CodeIgniter routing pattern here:

	$r->route('posts/(:num)', 'posts/show/$1');

The `route` method also allows a `controller#action` input:

	$r->route('posts/(:num)', 'posts#show');

You can also pass through an array of the controller and action:

	$r->route('posts/(:num)', array( 'Posts', 'show' ));

## HTTP Verb Routing

Pigeon also allows you to only route to a certain function when an HTTP verb is used. This is particularly useful when creating a RESTful system:

	$r->get('posts/(:id)', 'posts/show/$1');
	$r->post('posts', 'posts/create');
	$r->put('posts/(:id)', 'posts/update/$1');
	$r->delete('posts/(:id)', 'posts/delete/$1');
	$r->patch('posts/(:id)', 'posts/delete/$1');
	$r->head('posts/(:id)', 'posts/delete/$1');
	$r->options('posts/(:id)', 'posts/delete/$1');

## RESTful Resources

Pigeon supports RESTful resource based routes. A simple call to `resources` will generate a bunch of routes to facilitate a RESTful style application:

	$r->resources('posts');

...is the same as:

	$r->get('posts', 'posts/index');
	$r->get('posts/new', 'posts/create_new');
	$r->get('posts/(:any)/edit', 'posts/edit/$1');
	$r->get('posts/(:any)', 'posts/show/$1');
	$r->post('posts', 'posts/create');
	$r->put('posts/(:any)', 'posts/update/$1');
	$r->delete('posts/(:any)', 'posts/delete/$1');

You can also define a singular resource with `resource`.

## Nesting Routes

Pigeon also makes it very easy to nest routes inside other routes:

	$r->route('posts/(:num)', 'posts#show', function($r){
		$r->route('comments', 'comments#show');
	});

The above is equivalent to:

	$route['posts/(:num)'] = 'posts/show/$1';
	$route['posts/(:num)/comments'] = 'comments/show/$1';

The nesting engine is clever enough to account for parameters in the URL and any further params in nested URLs, which feels very similar to the usual way we write our routes in CI:
	
	$r->route('posts/(:num)', 'posts#show', function($r){
		$r->route('files/(:num)', 'files#show');
	});

This will grab the post ID from the post URL and pass it through:

	$route['posts/(:num)'] = 'posts/show/$1';
	$route['posts/(:num)/files/(:num)'] = 'files/show/$1/$2';

## Unit Tests

Install [PHPUnit](https://github.com/sebastianbergmann/phpunit). I'm running version 3.6.10.

Then, simply run the `phpunit` command on the test file:

    $ phpunit tests/Pigeon_test.php

## Changelog

**Version 0.2.0**
* Adjusted the resource routing to route to *create_new* rather than *new*
* Replaced `(:any)` in resource routing with `([a-zA-Z0-9\-_]+)`

**Version 0.1.0**
* Initial Release