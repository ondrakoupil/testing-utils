<?php

namespace OndraKoupil\Testing;

use \Tester\Assert as NAssert;
use \OndraKoupil\Tools\Arrays;


class Assert {

	/**
	 * Assert array equality without considering key order and without strict comparision.
	 *
	 * In numeric-indexed arrays, keys are considered insignificant, ie. array(1, 2, 3) and array(3, 2, 1) are equal in terms of this assertion.
	 * In associative arrays, keys are significant, however order of items in array is not.
	 *
	 * Items are compared using non-strict operator ==
	 *
	 * @param array $expected
	 * @param array $actual
	 */
	static function arrayEqual($expected,$actual) {
		if (!self::isArrayEqual($expected, $actual)) {
			NAssert::fail('%1 should be equal (without considering key order) to %2', $actual, $expected);
		}
	}

	/**
	 * Are these two arrays equal, without considering key order?
	 * @param array $expected
	 * @param array $actual
	 * @return bool
	 */
	static function isArrayEqual($expected, $actual) {

		if (Arrays::isNumeric($actual) and Arrays::isNumeric($expected)) {
			sort($expected);
			sort($actual);
		} else {
			asort($expected);
			asort($actual);
		}

		return $expected == $actual;
	}


	/**
	 * Assert array equality without considering key order and with strict comparision
	 *
	 * In numeric-indexed arrays, keys are considered insignificant, ie. array(1, 2, 3) and array(3, 2, 1) are equal in terms of this assertion.
	 * In associative arrays, keys are significant, however order of items in array is not.
	 *
	 * Items are compared using strict operator ===
	 *
	 * @param array $expected
	 * @param array $actual
	 */
	static function arraySame($expected,$actual) {
		if (!self::isArraySame($expected, $actual)) {
			NAssert::fail('%1 should be same (without considering key order) to %2', $actual, $expected);
		}
	}

	/**
	 * Are these two arrays equal, without considering key order?
	 * @param array $expected
	 * @param array $actual
	 * @return bool
	 */
	static function isArraySame($expected, $actual) {

		if (Arrays::isNumeric($actual) and Arrays::isNumeric($expected)) {
			sort($expected);
			sort($actual);
		} else {
			asort($expected);
			asort($actual);
		}
		
		return $expected === $actual;
	}
}
