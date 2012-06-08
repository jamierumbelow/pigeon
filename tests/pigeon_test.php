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
	protected $pigeon;

	public function setUp()
	{
		$this->pigeon = new Pigeon();
	}

	public function test_okay()
	{
		$this->assertTrue(true);
	}
}