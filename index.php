<?php
require_once("autoload.php");

CApplication::Instance()->AfficherDocumentCourant("CDocumentAccueil", function () { return new CDocumentAccueil(); });
?>