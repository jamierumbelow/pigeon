# Pigeon
## Stupidly simple routing for CodeIgniter

CodeIgniter's routing engine is _far too basic_. Pigeon wraps around the core routing system to provide HTTP method based routing, RESTful resources and nested routes. It uses a natural DSL to make writing cleverer routes simple and elegant.

## Synopsis

	Pigeon::route('posts/(:num)', 'posts/show/$1');

	Pigeon::get('posts', array( 'Posts', 'index' ));
	Pigeon::post('posts', 'Posts#create' );
	Pigeon::put('posts/(:num)', array( 'Posts', 'update' ));
	Pigeon::delete('posts/(:num)', array( 'Posts', 'delete' ));

	Pigeon::resources('posts');

	Pigeon::resources('posts', function($r){
		$r->resources('comments');
	});

	$route = Pigeon::draw();

## Basic routing

The most basic routing mechanism is the `route` method. You can pass through a traditional CodeIgniter routing pattern here:

	Pigeon::route('posts/(:num)', 'posts/show/$1');

The `route` method also allows a `controller#action` input:

	Pigeon::route('posts/(:num)', 'posts#show');

You can also pass through an array of the controller and action:

	Pigeon::route('posts/(:num)', array( 'Posts', 'show' ));

## HTTP Verb Routing

Pigeon also allows you to only route to a certain function when an HTTP verb is used. This is particularly useful when creating a RESTful system:

	Pigeon::get('posts/(:id)', 'posts/show/$1');
	Pigeon::post('posts', 'posts/create');
	Pigeon::put('posts/(:id)', 'posts/update/$1');
	Pigeon::delete('posts/(:id)', 'posts/delete/$1');
	Pigeon::patch('posts/(:id)', 'posts/delete/$1');
	Pigeon::head('posts/(:id)', 'posts/delete/$1');
	Pigeon::options('posts/(:id)', 'posts/delete/$1');

## RESTful Resources

Pigeon supports RESTful resource based routes. A simple call to `resources` will generate a bunch of routes to facilitate a RESTful style application:

	Pigeon::resources('posts');

...is the same as:

	Pigeon::get('posts', 'posts/index');
	Pigeon::get('posts/new', 'posts/new');
	Pigeon::get('posts/(:any)/edit', 'posts/edit/$1');
	Pigeon::get('posts/(:any)', 'posts/show/$1');
	Pigeon::post('posts', 'posts/create');
	Pigeon::put('posts/(:any)', 'posts/update/$1');
	Pigeon::delete('posts/(:any)', 'posts/delete/$1');

You can also define a singular resource with `resource`.

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

Unit Tests
----------

Install [PHPUnit](https://github.com/sebastianbergmann/phpunit). I'm running version 3.6.10.

Then, simply run the `phpunit` command on the test file:

    $ phpunit tests/Pigeon_test.php