<?php
require_once("../autoload.php");
session_start();

if (isset($_SESSION["form_connexion"]))
{	
	$_SESSION["form_connexion"]->DefinirValeurs($_POST);
	if ($_SESSION["form_connexion"]->TesterValidite())
	{
		$UtilisateurExistant = null;
            $_SESSION["EchecConnexionDB"] = false;
            if(!CApplication::Instance()->BD()->SeConnecter())
            {
                $_SESSION["EchecConnexionDB"] = true;
                return false;
            }
    
            $UtilisateurExistant = CApplication::Instance()->BD()->RecupererEnregistrement("SELECT * FROM utilisateur 
                                                                                            JOIN role ON utilisateur.fk_id_role = role.id_role 
																							WHERE email = :email AND mot_de_passe = SHA2(CONCAT(SHA2(:email, 512), SHA2(:mot_de_passe, 512)), 512);"
																							,array("email" =>$_SESSION["form_connexion"]->Champ("email")->Valeur(),
																							"mot_de_passe" => $_SESSION["form_connexion"]->Champ("motdepasse")->Valeur()));			
			if(!empty($UtilisateurExistant) )
			{			

				if((isset($_SESSION["CommandeVehicule"])) && ($_SESSION["CommandeVehicule"] === false))
				{	
					$_SESSION["form_connexion"]->Champ("motdepasse")->MessageErreur(null); 
					$_SESSION["UtilisateurConnecter"] = $UtilisateurExistant;
					header("location:../page_accueil.php");
				}
				else if((isset($_SESSION["CommandeVehicule"])) && ($_SESSION["CommandeVehicule"] === true))
				{
					$_SESSION["form_connexion"]->Champ("motdepasse")->MessageErreur(null); 
					$_SESSION["UtilisateurConnecter"] = $UtilisateurExistant;
					header("location:../page_voiture.php");
				}
				else
				{
					$_SESSION["UtilisateurConnecter"] = $UtilisateurExistant;
					header("location:../page_accueil.php");
				}
			}
			else
			{
				$_SESSION["form_connexion"]->Champ("motdepasse")->MessageErreur("Adresse email ou mot de passe incorrect !"); 
				header("location:../page_connexion.php");
			}
	}
	else{
		header("location:../page_connexion.php");
	}
}
else
{
	print("Formulaire non défini !");
}
?>