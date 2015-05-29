<?php

require '../bootstrap.php';

class NetteTestClass extends \OndraKoupil\Testing\NetteDatabaseTestCase {

	function testDb() {
		$row = $this->db->table("pokus")->where("id", 2)->fetch();
		$name = $row["name"];
		$this->assertQueriesCount(1);
		\Tester\Assert::equal($name, "Dvojka");
	}

}


$initFile = __DIR__ . "/sql/databasetest.sql";

if (!isset($db_host) or !isset($db_user) or !isset($db_pass)) {
	Tester\Environment::skip("Please set some variables in test bootstrap: \$db_pass, \$db_user, \$db_host");
}

if (!class_exists('\\Nette\\Database\\Connection')) {
	Tester\Environment::skip("Skipping tests for Nette Database, the package is not installed.");
}

$connNette = new \Nette\Database\Connection("mysql:".$db_host, $db_user, $db_pass);
$test = new NetteTestClass($connNette, $initFile);
$test->run();
