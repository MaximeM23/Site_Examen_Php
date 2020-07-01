<?php
class CDocumentInscription extends CDocument
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
		
                print("<div class=\"inscription-form\">");
                print("<img src=\"Images/BMW.svg\" alt=\"Avatar\" class=\"avatar\">");
                print("<h2 class=\"text-center\">Inscription</h2>");       

                if (!isset($_SESSION["form_inscription"]))
                {
                        $FormInscription = new CFormulaire("form_Inscription");
                        $FormInscription->Url("inc/traitement_formulaire_inscription.php");                                                               
                        $FormInscription->AjouterChamp(new CChampTexte("text","Prenom","form-control",null,"Prénom"));        
                        $FormInscription->AjouterChamp(new CChampTexte("text","Nom","form-control",null,"Nom"));        
                        $FormInscription->AjouterChamp(new CChampTexte("email","Email","form-control",null,"Adresse email"));           
                        $FormInscription->AjouterChamp(new CChampTexte("password","Password","form-control",null,"Mot de passe"));                     
                        $FormInscription->AjouterChamp(new CChampTexte("password","ConfirmationPassword","form-control",null,"Confirmation du mot de passe"));          
                        $FormInscription->Champ("Prenom")->LongueurMinimale(2);
                        $FormInscription->Champ("Prenom")->LongueurMaximale(200);
                        $FormInscription->Champ("Nom")->LongueurMinimale(2);
                        $FormInscription->Champ("Nom")->LongueurMaximale(200);
                        $FormInscription->Champ("Email")->LongueurMinimale(5);
                        $FormInscription->Champ("Email")->LongueurMaximale(50);
                        $FormInscription->Champ("Password")->LongueurMinimale(5);
                        $FormInscription->Champ("Password")->LongueurMaximale(50);                
                        $FormInscription->Champ("ConfirmationPassword")->LongueurMinimale(5);
                        $FormInscription->Champ("ConfirmationPassword")->LongueurMaximale(50);
                        $FormInscription->AjouterChamp(new CChampEnvoi("envoi", null, "S'inscrire","btnInscription btn btn-primary"));
                        $_SESSION["form_inscription"] = $FormInscription;
                }
                else
                {
                   $FormInscription = $_SESSION["form_inscription"];
                }
                $FormInscription->Creer(); 
                print("<a href=\"page_accueil\"> <button class=\"btnInscription btn-primary btn-lg btn-block mt--2-5\">Accueil</button></a>");                
                print("<a href=\"page_connexion\"> <button class=\"btnInscription btn-primary btn-lg btn-block mt-3\">Connexion</button></a>");                                
                print("</div>");

	}
	
	public function Proprietes()
	{
		return array_merge(get_object_vars($this), parent::Proprietes());
	}
}
?>
