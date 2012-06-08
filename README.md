# Pigeon
## Stupidly simple routing for CodeIgniter

CodeIgniter's routing engine is _far too basic_. Pigeon wraps around the core routing system to provide HTTP method based routing, RESTful resources and nested routes. It uses a natural DSL to make writing cleverer routes simple and elegant.

## Synopsis

	Pigeon::route('posts/(:id)', 'posts/show/$1');

	Pigeon::get('posts', array( 'Posts', 'index' ));
	Pigeon::post('posts', 'Posts#create' );
	Pigeon::put('posts/(:id)', array( 'Posts', 'update' ));
	Pigeon::delete('posts/(:id)', array( 'Posts', 'delete' ));

	Pigeon::resources('posts');

	Pigeon::resources('posts', function($r){
		$r->resources('comments');
	});

	$route = Pigeon::draw();

## Basic routing

The most basic routing mechanism is the `route` method. You can pass through a traditional CodeIgniter routing pattern here:

	Pigeon::route('posts/(:id)', 'posts/show/$1');

The `route` method also allows a `controller#action` input:

	Pigeon::route('posts/(:id)', 'posts#show');

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