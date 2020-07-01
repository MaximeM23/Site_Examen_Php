<?php
require_once("../autoload.php");
session_start();

if (isset($_SESSION["form_inscription"]))
{	
	$_SESSION["form_inscription"]->DefinirValeurs($_POST);
	if ($_SESSION["form_inscription"]->TesterValidite())
	{
		$EmailExistant = false;
		$MotDePassePasIdentique = false;

		if($_SESSION["form_inscription"]->Champ("Password")->Valeur() != $_SESSION["form_inscription"]->Champ("ConfirmationPassword")->Valeur())
		{
			$_SESSION["form_inscription"]->Champ("ConfirmationPassword")->MessageErreur("Le mot de passe de vérification ne correspond pas "); 
			$MotDePassePasIdentique = true;
		}
		else
		{
			$_SESSION["form_inscription"]->Champ("ConfirmationPassword")->MessageErreur(null);
			$MotDePassePasIdentique = false;
			
		}

		$NouvelUtilisateur = new CUtilisateur($_SESSION["form_inscription"]->Champ("Nom")->Valeur(),
												$_SESSION["form_inscription"]->Champ("Prenom")->Valeur(),
												$_SESSION["form_inscription"]->Champ("Email")->Valeur(),
												$_SESSION["form_inscription"]->Champ("Password")->Valeur(), "1");

		if($NouvelUtilisateur->VerifierExistanceEmail() === true)
		{
			$_SESSION["form_inscription"]->Champ("Email")->MessageErreur("Cet email est déjà utilisé par un utilisateur"); 
			$EmailExistant = true;
		}
		else
		{
			$_SESSION["form_inscription"]->Champ("Email")->MessageErreur(null);
			$EmailExistant = false;
		}
		
		if($EmailExistant === false && $MotDePassePasIdentique === false)
		{
			$NouvelId = $NouvelUtilisateur->Inscription();
			$_SESSION["UtilisateurConnecter"] = CApplication::Instance()->BD()->RecupererEnregistrement("SELECT * FROM utilisateur JOIN role ON utilisateur.fk_id_role = role.id_role
																											WHERE id_utilisateur = :id"
																											,array("id" => $NouvelId));
			
			header("location:../page_accueil.php");
		}
		else
		{			
			header("location:../page_inscription.php");
		}
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