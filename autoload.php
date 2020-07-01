<?php
if (!isset($AUTOLOAD_REPERTOIRES))
{
	$AUTOLOAD_REPERTOIRES = array("inc", "pid_framework");
}
if (!defined("AUTOLOAD_APPLICATION_NOM_CLASSE"))
{
	define("AUTOLOAD_APPLICATION_NOM_CLASSE", "CMonApplication");
}

spl_autoload_register(function($NomClasse)
{
	global $AUTOLOAD_REPERTOIRES;
	$Racine = str_replace("\\", "/", __DIR__);
	$Source = dirname($_SERVER["SCRIPT_FILENAME"]);
	$CheminRelatif = "";
	for ($Compteur = substr_count($Source, "/") - substr_count($Racine, "/"); $Compteur > 0; $Compteur--)
	{
		$CheminRelatif .= "../";
	}
	$TypeClasse = strtoupper(substr($NomClasse, 0, 1));
	if ($TypeClasse == "C") $TypeClasse = "classe"; else if ($TypeClasse == "I") $TypeClasse = "interface"; else unset($TypeClasse);
	if (isset($TypeClasse))
	{
		$NomClasse = strtolower(substr($NomClasse, 1));
		foreach($AUTOLOAD_REPERTOIRES as $Repertoire)
		{
			$NomFichier = $CheminRelatif . $Repertoire . "/" . $TypeClasse . "." . $NomClasse . ".php";
			if (file_exists($NomFichier))
			{
				require_once($NomFichier);
				break;
			}
		}
	}
});
?>