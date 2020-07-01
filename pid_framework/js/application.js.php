<?php
require_once("../../autoload.php");

CApplication::Instance()->DeclarerJeuCaracteres("javascript");
require_once("application.js");
if (!CApplication::Instance()->EnProduction())
{
	print("NotifierInclusion(\"$_SERVER[SCRIPT_NAME]\");");
	require_once("application.dev.js");
}
?>