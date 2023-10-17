<?php

/**
 * @package oik-dates
 * @copyright (C) Copyright Bobbing Wide 2023
 *
 * Unit tests to load all the PHP files for PHP 8.2
 */
class Tests_load_php extends BW_UnitTestCase
{

	/**
	 * set up logic
	 *
	 * - ensure any database updates are rolled back
	 * - we need oik-googlemap to load the functions we're testing
	 */
	function setUp(): void 	{
		parent::setUp();
	}

	function test_load_includes_php() {
		oik_require( 'includes/oik-dates.inc', 'oik-dates');
		$this->assertTrue( true );
	}

	function test_load_shortcodes_php() {
		oik_require( 'shortcodes/oik-otd.php', 'oik-dates');
		$this->assertTrue( true );
	}

	function test_load_plugin_php() {
		oik_require( 'oik-dates.php', 'oik-dates');
		$this->assertTrue( true );
	}
}