<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

require '../bootstrap.php';

use \Tester\Assert;
use \Tester\TestCase;
use OndraKoupil\Testing\FilesTestCase;

class FilesTest extends FilesTestCase {

	function testSomething() {
		$dir = $this->getTempDir();

		\Tester\Assert::true(file_exists($dir));
		\Tester\Assert::true(is_dir($dir));
		\Tester\Assert::true(is_writable($dir));

		$file = $dir."/file.txt";

		file_put_contents($file, "Some file contents");

		\Tester\Assert::true(file_exists($file));
		\Tester\Assert::false(is_dir($file));
		\Tester\Assert::true(is_writable($file));
		Tester\Assert::equal(18, filesize($file));

		Tester\Assert::equal("Some file contents", file_get_contents($file));

	}

	/**
	 * @multiple 3
	 */
	function testTempDirIsFree() {

		$files = glob(TMP_TEST_DIR."/*");
		if ($files === false) {
			$files = array();
		}

		if (count($files) != 0) {
			Assert::fail("Temp dir ".TMP_TEST_DIR." is not empty. This is not necessarily a fail, maybe there were some files left from before. Delete all files and directories in it and try again.");
		}

		$dir = $this->getTempDir();

		file_put_contents($dir . "/somefile.txt", "AAA");
		file_put_contents($dir . "/somefile-2.txt", "AAA");

		$files = glob(TMP_TEST_DIR."/*");
		$filesInDir = glob($dir."/*");

		Assert::equal(1, count($files));
		Assert::equal(2, count($filesInDir));

	}

}

$case = new FilesTest();
$case->run();
