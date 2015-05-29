<?php

require '../bootstrap.php';

class MySqlTestClass extends \OndraKoupil\Testing\MysqlTestCase {

	function testDb() {
		$u = mysql_query("select * from pokus order by id");
		$this->assertQueriesCount(0);
		\Tester\Assert::equal(4, mysql_num_rows($u));
		$r = mysql_fetch_array($u);
		\Tester\Assert::equal("1", $r["id"]);
		\Tester\Assert::equal("Jednička", $r["name"]);

		$this->query("select * from pokud where id = 3");
		$this->assertQueriesCount(1);
	}

}

class PdoTestClass extends \OndraKoupil\Testing\PdoTestCase {

	function testDb() {
		$this->assertQueriesCount(0);
		$row = $this->db->query("select * from pokus where id = 3")->fetch();
		$name = $row["name"];
		$this->assertQueriesCount(0);
		\Tester\Assert::equal($name, "Trojka");

		$row = $this->query("select * from pokus where id = 1")->fetch();
		$name = $row["name"];
		\Tester\Assert::equal($name, "Jednička");
		$this->assertQueriesCount(1);
	}

}

$initFile = __DIR__ . "/sql/databasetest.sql";

if (!isset($db_host) or !isset($db_user) or !isset($db_pass)) {
	Tester\Environment::skip("Please set some variables in test bootstrap: \$db_pass, \$db_user, \$db_host");
}

$connMySql = mysql_connect($db_host, $db_user, $db_pass);
$test = new MySqlTestClass($connMySql, $initFile);
$test->run();

$pdo = new PDO("mysql:host=$db_host", $db_user, $db_pass);
$test = new PdoTestClass($pdo, $initFile);
$test->run();
