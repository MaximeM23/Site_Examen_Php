<?php
class CDocumentConnexion extends CDocument
{
	public function __construct()
	{
		parent::__construct();
		
                parent::AjouterFichierCss("css/bootstrap.min.css");
                parent::AjouterFichierCss("css/style.css");
                parent::AjouterFichierJs("pid_framework/js/jquery-3.3.1.js");
                parent::AjouterFichierJs("pid_framework/js/bootstrap.min.js");		
	}
	
	protected function GenererContenu()
	{
		
                print("<div class=\"login-form\">");
                print("<img src=\"Images/BMW.svg\" alt=\"Avatar\" class=\"avatar\">");
                print("<h2 class=\"text-center\">Se connecter</h2>");       

                if (!isset($_SESSION["form_connexion"]))
                {
                $FormConnexion = new CFormulaire("form_connexion");
                $FormConnexion->Url("inc/traitement_formulaire_connexion.php");
                //$FormConnexion->Commande("TraiterFormTest");
                        
                $FormConnexion->AjouterChamp(new CChampTexte("email","email","form-control",null,"Adresse email"));           
                $FormConnexion->AjouterChamp(new CChampTexte("password","motdepasse","form-control",null,"Mot de passe"));           
                $FormConnexion->Champ("email")->LongueurMinimale(5);
                $FormConnexion->Champ("email")->LongueurMaximale(50);
                $FormConnexion->Champ("motdepasse")->LongueurMinimale(5);
                $FormConnexion->Champ("motdepasse")->LongueurMaximale(50);
                $FormConnexion->AjouterChamp(new CChampEnvoi("envoi", null, "Se connecter","btn btn-primary "));
                $_SESSION["form_connexion"] = $FormConnexion;
                }
                else
                {
                        $FormConnexion = $_SESSION["form_connexion"];
                }
                $FormConnexion->Creer();
                
                print("<a href=\"page_inscription\"> <button class=\"btnInscription btn-primary btn-lg btn-block mt--2-5\">Inscription</button></a>");
                print("<a href=\"page_accueil\"> <button class=\"btnInscription btn-primary btn-lg btn-block mt-3\">Accueil</button></a>");
                print("</div>");

	}
	
	public function Proprietes()
	{
		return array_merge(get_object_vars($this), parent::Proprietes());
	}
}
?>
