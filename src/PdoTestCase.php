<?php


namespace OndraKoupil\Testing;

use \Tester\TestCase;
use \Tester\Assert;

/**
 * Test class using PDO.
 */
class PdoTestCase extends BaseDatabaseTestCase {

	/**
	 * @var \PDO
	 */
	protected $db;

	/**
	 * @param \PDO $pdo Use PDO without specified database name, i.e. "mysql:host=localhost".
	 * The database will be created automatically.
	 * @param string $initSqlFile
	 */
	function __construct(\PDO $pdo, $initSqlFile) {
		parent::__construct($initSqlFile);
		$this->db = $pdo;
	}

	/**
	 * @return \PDO
	 */
	public function getDb() {
		return $this->db;
	}

	/**
	 * @return \PDO
	 */
	public function getPdo() {
		return $this->db;
	}

	public function query($query) {
		$this->logQuery($query);
		return $this->db->query($query);
	}




}
