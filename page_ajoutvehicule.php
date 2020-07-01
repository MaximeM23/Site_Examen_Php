<?php
require_once("autoload.php");
session_start();

if((isset($_SESSION["UtilisateurConnecter"])) && (isset($_SESSION["UtilisateurConnecter"]["role"] == "Administrateur")))
{
    CApplication::Instance()->AfficherDocumentCourant("CDocumentAjoutVehicule", function () { return new CDocumentAjoutVehicule(); });
}
else
{
    CApplication::Instance()->AfficherDocumentCourant("CDocumentAccueil", function () { return new CDocumentAccueil(); });
}

?>