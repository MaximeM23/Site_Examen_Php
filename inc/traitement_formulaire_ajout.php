<?php
require_once("../autoload.php");
session_start();

if (isset($_SESSION["form_AjoutVehicule"]))
{	
	$_SESSION["form_AjoutVehicule"]->DefinirValeurs($_POST);
	if ($_SESSION["form_AjoutVehicule"]->TesterValidite())
	{



        $NouveauVehicule = new CVehicule($_POST["Modele"],(int)$_POST["PrixJournee"],(int)$_POST["PrixDemiJournee"],$_POST["NomImage"]);
        $NouveauVehicule->AjouterVehicule();
        
        $_SESSION["form_AjoutVehicule"]->Champ("envoi")->MessageReussite("Enregistrement de la nouvelle voiture réussie !"); 
		
		header("location:../page_voiture.php");
		
	}
	else
	{		
		header("location:../page_inscription.php");
    }
}
else
{
	print("Formulaire non défini !");
}



?>