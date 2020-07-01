<?php
require_once("autoload.php");
session_start();
unset($_SESSION["UtilisateurConnecter"]);
unset($_SESSION["CommandeVehicule"]);
unset($_SESSION["form_connexion"]);
unset($_SESSION["form_profil"]);
unset($_SESSION["form_inscription"]);
unset($_SESSION["form_AjoutVehicule"]);
CApplication::Instance()->AfficherDocumentCourant("CDocumentAccueil", function () { return new CDocumentAccueil(); });
?>