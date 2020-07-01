<?php
require_once("autoload.php");
CApplication::Instance()->AfficherDocumentCourant("CDocumentConnexion", function () { return new CDocumentConnexion(); });
?>