<?php

require __DIR__ . "/../vendor/autoload.php";

define("TMP_TEST_DIR", __DIR__ . "/temp");

\Tester\Environment::setup();

$db_host = "localhost";
$db_user = "root";
$db_pass = "root";
