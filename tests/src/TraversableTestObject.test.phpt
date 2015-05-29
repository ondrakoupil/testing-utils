<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require '../bootstrap.php';

use \Tester\Assert;
use \Tester\TestCase;

use OndraKoupil\Testing\TraversableTestObject;

class TraversableTestCase extends TestCase {

	function testTraversable() {

		$obj = new TraversableTestObject(10);

		Assert::true($obj instanceof Traversable);

		$i = 0;
		foreach($obj as $key=>$value) {
			Assert::same($key, $value);
			Assert::same($i, $value);
			$i++;
		}

		Assert::equal(10, $i);


		$obj2 = new TraversableTestObject(4, "something");

		$i = 0;
		foreach($obj2 as $key=>$value) {
			Assert::same("something", $value);
			Assert::same($i, $key);
			$i++;
		}

		Assert::equal(4, $i);

	}

}

$case = new TraversableTestCase();
$case->run();
