<?php
require_once("../autoload.php");
session_start();

if (isset($_SESSION["form_profil"]))
{	
	$_SESSION["form_profil"]->DefinirValeurs($_POST);
	if ($_SESSION["form_profil"]->TesterValidite())
	{
		$_SESSION["EchecConnexionDB"] = false;
		if(!CApplication::Instance()->BD()->SeConnecter())
		{
			$_SESSION["EchecConnexionDB"] = true;
			return false;
		}

        $UtilisateurExistant = CApplication::Instance()->BD()->RecupererEnregistrement("SELECT * FROM utilisateur 
																							WHERE email = :email AND id_utilisateur = :id"
																							,array("email" => $_SESSION["UtilisateurConnecter"]["email"],
																								   "id" => 	$_SESSION["UtilisateurConnecter"]["id_utilisateur"]));
		if(!empty($UtilisateurExistant))
		{			
			
			
						
			$NouvelUtilisateur = new CUtilisateur($_POST["Nom"],
												$_POST["Prenom"],
												$_SESSION["UtilisateurConnecter"]["email"],
												$_POST["Password"],
												$_SESSION["UtilisateurConnecter"]["id_role"],
												$_SESSION["UtilisateurConnecter"]["id_utilisateur"]);



			
			//Si mot de passe différent d'une chaine vide
			if($_SESSION["form_profil"]->Champ("Password")->Valeur() != "")
			{
				$MotDePassePasIdentique = false;
				// Alors je vérifie si mes mots de passe correspondent
				if($_SESSION["form_profil"]->Champ("Password")->Valeur() != $_SESSION["form_profil"]->Champ("ConfirmationPassword")->Valeur())
				{
					// Si ils correpondent pas, je retourne une message d'erreur
					$_SESSION["form_profil"]->Champ("ConfirmationPassword")->MessageErreur("Le mot de passe de vérification ne correspond pas "); 					
					$_SESSION["form_profil"]->Champ("envoi")->MessageReussite(false); 
					$MotDePassePasIdentique = true;
				}
				else
				{
					// Sinon c'est bon je nettoie et indique qu'ils sont indentique
					$_SESSION["form_profil"]->Champ("ConfirmationPassword")->MessageErreur(null);
					$MotDePassePasIdentique = false;				
					
				}
				// Si les mot de passes sont les mêmes
				if($MotDePassePasIdentique === false)
				{
					$NouvelUtilisateur->ModificationProfil(true);
					$_SESSION["UtilisateurConnecter"] = CApplication::Instance()->BD()->RecupererEnregistrement("SELECT * FROM utilisateur JOIN role ON utilisateur.fk_id_role = role.id_role
																												WHERE id_utilisateur = :id"
																												,array("id" => $_SESSION["UtilisateurConnecter"]["id_utilisateur"]));
					$_SESSION["form_profil"]->Champ("envoi")->MessageReussite("Changement réussi !"); 
					header("location:../page_profil.php");
				}
				else
				{
					$_SESSION["form_profil"]->Champ("ConfirmationPassword")->MessageErreur("Les mot de passe ne sont pas identiques !"); 				
					$_SESSION["form_profil"]->Champ("envoi")->MessageReussite(false); 
					header("location:../page_profil.php");
				}
			}
			else
			{
				$NouvelUtilisateur->ModificationProfil();
				$_SESSION["UtilisateurConnecter"] = CApplication::Instance()->BD()->RecupererEnregistrement("SELECT * FROM utilisateur JOIN role ON utilisateur.fk_id_role = role.id_role
				WHERE id_utilisateur = :id"
				,array("id" => $_SESSION["UtilisateurConnecter"]["id_utilisateur"]));
				$_SESSION["form_profil"]->Champ("envoi")->MessageReussite("Changement réussi !"); 
				header("location:../page_profil.php");
			}
		}
		else
		{
			header("location:../page_connexion.php");
		}
	}
}
else
{
	print("Formulaire non défini !");
}
?>