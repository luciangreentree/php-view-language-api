<?php
ini_set("display_errors",1);
error_reporting(E_ALL);
require_once(dirname(__DIR__)."/loader.php");
require_once("taglib/test/TestMineTag.php");

$vlp = new ViewLanguageParser("views", "index", "compilations");
$vlp->parse();
echo "OK";