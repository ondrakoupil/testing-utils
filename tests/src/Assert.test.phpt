<?php

require '../bootstrap.php';

use \Tester\Assert;
use \OndraKoupil\Testing\Assert as OKAssert;
use \Tester\TestCase;


class AssertTestCase extends TestCase {

	function testAssertArraysEqual() {

		// Basic assertions

		Assert::exception(function() {
			OKAssert::arrayEqual(array(1, 2, 3), array(3, 2, 1, 4));
		}, '\\Tester\\AssertException');

		Assert::exception(function() {
			OKAssert::arraySame(array(1, 2, 3), array(3, "2", 1, 4));
		}, '\\Tester\\AssertException');


		OKAssert::arrayEqual(array(3, 1, 2), array(1, 2, 3));
		OKAssert::arraySame(array(3, 1, 2), array(1, 2, 3));

		$array1 = array(1, 2, 3);
		$array2 = array(2, 3, 1);

		Assert::false( Assert::isEqual($array1, $array2) );
		Assert::true( OKAssert::isArrayEqual($array1, $array2) );
		Assert::true( OKAssert::isArraySame($array1, $array2) );

		$array1 = array(1, 2, 3);
		$array2 = array(1, 2, 3);

		Assert::true( Assert::isEqual($array1, $array2) );
		Assert::true( OKAssert::isArrayEqual($array1, $array2) );
		Assert::true( OKAssert::isArraySame($array1, $array2) );


		$array1 = array("name" => "One", "number" => "1");
		$array2 = array("number" => "1", "name" => "One");
		$array3 = array("number" => 1, "name" => "One");

		Assert::true( Assert::isEqual($array1, $array2) );
		Assert::true( OKAssert::isArrayEqual($array1, $array2) );
		Assert::true( OKAssert::isArraySame($array1, $array2) );
		Assert::false( OKAssert::isArraySame($array1, $array3) );

		$array1 = array("name" => "One", "number" => "1");
		$array2 = array("number" => "One", "name" => "1");

		Assert::false( Assert::isEqual($array1, $array2) );
		Assert::false( OKAssert::isArrayEqual($array1, $array2) );
		Assert::false( OKAssert::isArraySame($array1, $array2) );
		Assert::exception(function() use ($array1, $array2) {
			OKAssert::arraySame($array1, $array2);
		}, '\\Tester\\AssertException');


		// Type assertions

		Assert::true( OKAssert::isArrayEqual(array(1, 2, 3), array("2", "3", "1")) );
		Assert::false( OKAssert::isArraySame(array(1, 2, 3), array("2", "3", "1")) );

		Assert::exception(function() {
			OKAssert::arraySame(array(1, 2, 3), array("3", "2", "1"));
		}, '\\Tester\\AssertException');



	}

}


$case = new AssertTestCase();
$case->run();
