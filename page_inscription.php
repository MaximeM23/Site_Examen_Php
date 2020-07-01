<?php
require_once("autoload.php");

session_start();

if(isset($_SESSION["UtilisateurConnecter"]))
{
    CApplication::Instance()->AfficherDocumentCourant("CDocumentAccueil", function () { return new CDocumentAccueil(); });
}
else
{
    CApplication::Instance()->AfficherDocumentCourant("CDocumentInscription", function () { return new CDocumentInscription(); });
}

?>