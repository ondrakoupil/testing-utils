<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require '../bootstrap.php';

use \Tester\Assert;
use \Tester\TestCase;
use \OndraKoupil\Testing\ArrayAccessTestObject;

class ArrAccessTestCase extends TestCase {

	function testArrayAccess() {


		$obj = new ArrayAccessTestObject();
		Assert::true($obj instanceof ArrayAccess);
		Assert::true($obj instanceof Countable);
		Assert::false(is_array($obj));
		Assert::equal(0, count($obj));
		$obj["one"] = "Jednička";
		$obj["two"] = "Dvojka";
		Assert::equal(2, count($obj));
		Assert::equal(array("one" => "Jednička", "two" => "Dvojka"), $obj->getData());
		Assert::equal("Jednička", $obj["one"]);
		Assert::equal("Dvojka", $obj["two"]);
		Assert::true(isset($obj["one"]));
		unset($obj["one"]);
		Assert::false(isset($obj["one"]));
		Assert::equal(1, count($obj));
		Assert::null($obj["one"]);
		Assert::equal("Dvojka", $obj["two"]);

		$array = array("name" => "One", "number" => 1);
		$obj = new ArrayAccessTestObject($array);

		Assert::equal(2, count($obj));
		Assert::equal("One", $obj["name"]);
		Assert::same($array, $obj->getData());

	}

}

$case = new ArrAccessTestCase();
$case->run();
