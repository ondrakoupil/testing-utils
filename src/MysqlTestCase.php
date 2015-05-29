<?php


namespace OndraKoupil\Testing;

use \Tester\TestCase;
use \Tester\Assert;


class MysqlTestCase extends BaseDatabaseTestCase {

	protected $connection;

	function __construct($connection, $initSqlFile) {

		parent::__construct($initSqlFile);
		$this->connection = $connection;
		mysql_select_db($this->getDatabaseName(), $connection);
		$this->queries = array();

	}

	/**
	 * @param string $query
	 * @return mixed returns of mysql_query
	 */
	public function query($query) {
		$this->logQuery($query);
		return mysql_query($query, $this->connection);
	}

}