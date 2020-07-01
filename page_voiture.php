<?php
require_once("autoload.php");

CApplication::Instance()->AfficherDocumentCourant("CDocumentVoiture", function () { return new CDocumentVoiture(); });
?>