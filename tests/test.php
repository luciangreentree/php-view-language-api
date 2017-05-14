<?php
ini_set("display_errors",1);
error_reporting(E_ALL);
require_once(dirname(__DIR__)."/loader.php");

try {
	// code necessary to produce a compilation
	$vlp = new ViewLanguageParser("templates","php","compilations","taglib");
	$compilationFile = $vlp->compile("index");
	
	// test that will go through all files above
	$user["id"] = 11;
	$names[11] = "Tester";
	require_once($compilationFile);
} catch(ViewException $e) {
	echo $e->getMessage();
}