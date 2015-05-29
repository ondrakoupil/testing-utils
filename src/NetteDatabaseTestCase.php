<?php


namespace OndraKoupil\Testing;

use \Tester\TestCase;
use \Tester\Assert;


class NetteDatabaseTestCase extends BaseDatabaseTestCase {

	protected $connection;

	/**
	 * @var \Nette\Database\Context
	 */
	protected $db;

	function __construct(\Nette\Database\Connection $connection, $initSqlFile, \Nette\Database\IStructure $structure = null) {
		parent::__construct($initSqlFile);
		$this->connection = $connection;

		if (!$structure) {
			$storage = new \Nette\Caching\Storages\MemoryStorage();
			$structure = new \Nette\Database\Structure($connection, $storage);
		}
		$this->db = new \Nette\Database\Context($connection, $structure);

		$_this = $this;
		$this->connection->onQuery[] = function($connection, $result) use ($_this) {
			$_this->logQuery($result);
		};
	}

	/**
	 * @return \Nette\Database\Context
	 */
	public function getDb() {
		return $this->db;
	}

	/**
	 * @return \Nette\Database\Context
	 */
	public function getContext() {
		return $this->db;
	}

	/**
	 * @return \Nette\Database\Connection
	 */
	public function getConnection() {
		return $this->connection;
	}

	function importFile($filename) {
		\Nette\Database\Helpers::loadFromFile($this->connection, $filename);
	}

	public function query($query) {
		return $this->connection->query($query);
	}




}
