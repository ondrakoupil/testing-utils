<?php

namespace OndraKoupil\Testing;

use \Tester\TestCase;
use \Tester\Assert;

/**
 * TestCase with database
 */
abstract class BaseDatabaseTestCase extends \Tester\TestCase {


	/**
	 * @var string
	 */
	private $filename;

	/**
	 * @var string
	 */
	private $tempDatabaseName;

	protected $queries=array();

	protected $databaseExists=false;

	protected $queriesLast=0;


	function __construct($initSqlFile) {

		if (!file_exists($initSqlFile)) {
			throw new \OndraKoupil\Tools\Exceptions\FileException("File not found: $initSqlFile");
		}

		$this->filename=$initSqlFile;

		self::registerFailureHandlerIfNeeded();
		self::registerShutdownHandlerIfNeeded();
	}

	abstract function query($query);

	function setUp() {
		parent::setUp();
		if (!$this->databaseExists) {
			$this->createTempDatabase();
			self::registerInstance($this);
		}
		try {
			$this->importFile($this->filename);
		} catch (\Exception $e) {
			\Tester\Assert::fail("Failed loading init SQL file: ".$e->getMessage());
		}

		$this->resetQueries();

	}

	function logQuery($query) {
		$this->queries[] = $query;
	}

	function assertQueriesCount($expected) {
		$actual=count($this->queries);
		if ($actual!=$expected) {
			Assert::fail("Number of queries should be $expected, was $actual", $actual, $expected);
		}
	}

	function assertQueriesSince($expected) {
		$actual=count($this->queries) - $this->queriesLast;
		if ($actual!=$expected) {
			Assert::fail("Number of queries since last reset should be $expected, was $actual", $actual, $expected);
		}
	}

	function getDatabaseName() {
		return $this->tempDatabaseName;
	}

	function resetQueriesCount() {
		$this->queriesLast=count($this->queries);
		return $this;
	}

	function resetQueries() {
		$this->queriesLast = 0;
		$this->queries = array();
	}

	private function createTempDatabase() {

		$dbName="testTempDatabase".rand(100000,999999);

		$this->tempDatabaseName=$dbName;

		try {
			$ret = $this->query("CREATE DATABASE $dbName");
			if ($ret === false) {
				throw new \RuntimeException("query() method returned false.");
			}

		} catch (\Exception $e) {
			Assert::fail("Could not create temporary database - probably insufficient privileges.");
			return null;
		}

		$this->query("USE $dbName");
		$this->databaseExists=true;
	}

	private function dropTempDatabase() {
		try {
			$ret = $this->query("DROP DATABASE IF EXISTS $this->tempDatabaseName");
			if ($ret === false) {
				throw new \RuntimeException("query() method returned false.");
			}

		} catch (\Exception $e) {
			Assert::fail("Could not delete temporary database \"$this->tempDatabaseName\" - you might want to delete it manually.");
			return null;
		}
		$this->databaseExists=false;
	}

	static private $isRegisteredOnFailure=false;
	static private function registerFailureHandlerIfNeeded() {
		if (self::$isRegisteredOnFailure) return;
		Assert::$onFailure=array(__CLASS__,"failureHandler");
		self::$isRegisteredOnFailure=true;
	}

	static private $isRegisteredShutdown=false;
	static private function registerShutdownHandlerIfNeeded() {
		if (self::$isRegisteredShutdown) return;
		register_shutdown_function(array(__CLASS__,"shutdownHandler"));
		self::$isRegisteredShutdown=true;
	}


	static private $registeredInstances=array();
	static private function registerInstance($instance) {
		self::$registeredInstances[$instance->tempDatabaseName]=$instance;
	}
	static private function unregisterInstance($instance) {
		if (isset(self::$registeredInstances[$instance->tempDatabaseName])) {
			unset(self::$registeredInstances[$instance->tempDatabaseName]);
		}
	}


	static function failureHandler($e) {
		foreach(self::$registeredInstances as $instance) {
			$instance->dropTempDatabase();
		}
		throw $e;
	}

	static function shutdownHandler() {
		foreach(self::$registeredInstances as $instance) {
			$instance->dropTempDatabase();
		}
	}

	/**
	 * @author Nette Framework
	 * @param string $filename
	 * @return int
	 * @throws \OndraKoupil\Tools\Exceptions\FileException
	 */
	function importFile($filename) {
		@set_time_limit(0); // intentionally @

		$handle = @fopen($filename, 'r'); // intentionally @
		if (!$handle) {
			throw new \OndraKoupil\Tools\Exceptions\FileException("Cannot open file '$filename'.");
		}

		$count = 0;
		$delimiter = ';';
		$sql = '';
		while (!feof($handle)) {
			$s = rtrim(fgets($handle));
			if (!strncasecmp($s, 'DELIMITER ', 10)) {
				$delimiter = substr($s, 10);

			} elseif (substr($s, -strlen($delimiter)) === $delimiter) {
				$sql .= substr($s, 0, -strlen($delimiter));
				$this->query($sql, false);
				$sql = '';
				$count++;

			} else {
				$sql .= $s . "\n";
			}
		}
		if (trim($sql) !== '') {
			$this->query($sql, false);
			$count++;
		}
		fclose($handle);
		return $count;
	}


}