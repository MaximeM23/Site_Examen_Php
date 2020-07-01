<?php
require_once("autoload.php");
session_start();

if(isset($_SESSION["UtilisateurConnecter"]))
{
    CApplication::Instance()->AfficherDocumentCourant("CDocumentProfil", function () { return new CDocumentProfil(); });
}
else
{
    CApplication::Instance()->AfficherDocumentCourant("CDocumentAccueil", function () { return new CDocumentAccueil(); });
}

?>