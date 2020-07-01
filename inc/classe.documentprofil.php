<?php
class CDocumentProfil extends CDocument
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
	//Menu de navigation
        print("<nav class=\"navbar navbar-expand-lg navbar-dark\">");
        print("<a class=\"navbar-brand\" href=\"page_accueil.php\"><img class=\"imagelogo\" src=\"Images/BMW.svg\" alt=\"logo_bmw\"></a>");
        print("<button class=\"navbar-toggler\" type=\"button\" data-toggle=\"collapse\" data-target=\"#navbarSupportedContent\" aria-controls=\"navbarSupportedContent\" aria-expanded=\"false\" aria-label=\"Toggle navigation\">");
        print("<span class=\"navbar-toggler-icon\"></span>");
        print("</button>");
        print("<div class=\"navbar-collapse collapse w-100 order-1 order-md-0 dual-collapse2\" id=\"navbarSupportedContent\">");
        print("<ul class=\"navbar-nav mr-auto\">");
          print("<li class=\"nav-item \">");
        print("<a class=\"nav-link m-2 pr-4 pl-5 menu-item\" href=\"page_accueil.php\">Accueil<span class=\"sr-only\"></span></a>");
        print("</li>");
        print("<li class=\"nav-item \">");
        print("<a class=\"nav-link m-2 pr-4 \" href=\"page_voiture.php\" >Nos voitures</a>");
        print("</li>");
        
        print("</ul>");
        
        print("<ul class=\"navbar-nav ml-auto\">");
        
        if(isset($_SESSION["UtilisateurConnecter"]))
        {	
                print("<li class=\"nav-item\">");		
                print("<a class=\"nav-link m-2 pr-4 menu-item navbar-right\" href=\"page_profil.php\">Mon profil</a>");		
                print("</li>");
        }
        else{			
                print("<a class=\"nav-link m-2 pr-4 menu-item navbar-right\" href=\"page_inscription.php\">S'inscrire</a>");
        }
        print("<li class=\"nav-item\">");		
        if(isset($_SESSION["UtilisateurConnecter"]))
        {									
                
                print("<a class=\"nav-link m-2 pr-4 menu-item navbar-right\" href=\"page_deconnexion.php\">Se déconnecter</a>");
        }		
        else
        {
                
                print("<a class=\"nav-link m-2 pr-4 menu-item navbar-right\" href=\"page_connexion.php\">Connexion</a>");
        }
        print("</li>");
        
        
        print("</ul>");
        print("</div>");
        print("</nav>");
        
        // contenu de la page

        print("<div class=\"inscription-form mt-5\">");
        print("<h2 class=\"text-center\">Modification du profil</h2>"); 
        if (!isset($_SESSION["form_profil"]))
        {
                $FormProfil = new CFormulaire("form_profil");
                $FormProfil->Url("inc/traitement_formulaire_profil.php");               
                $FormProfil->AjouterChamp(new CChampTexte("text","Email","form-control",null,null,true));    
                $FormProfil->AjouterChamp(new CChampTexte("text","Prenom","form-control",null,"Prénom"));        
                $FormProfil->AjouterChamp(new CChampTexte("text","Nom","form-control",null,"Nom"));                
                $FormProfil->AjouterChamp(new CChampTexte("password","Password","form-control",null,"Mot de passe"));                     
                $FormProfil->AjouterChamp(new CChampTexte("password","ConfirmationPassword","form-control",null,"Confirmation du mot de passe"));          
                $FormProfil->Champ("Prenom")->LongueurMinimale(2);
                $FormProfil->Champ("Prenom")->LongueurMaximale(200);
                
                $FormProfil->Champ("Email")->DefinirValeur($_SESSION["UtilisateurConnecter"]["email"]); 
                $FormProfil->Champ("Prenom")->DefinirValeur($_SESSION["UtilisateurConnecter"]["prenom"]);        
                $FormProfil->Champ("Nom")->LongueurMinimale(2);
                $FormProfil->Champ("Nom")->LongueurMaximale(200);         
                $FormProfil->Champ("Nom")->DefinirValeur($_SESSION["UtilisateurConnecter"]["nom"]); 
                $FormProfil->AjouterChamp(new CChampEnvoi("envoi", null, "Modifier la ou les informations","btnInscription btn btn-primary"));
                $_SESSION["form_profil"] = $FormProfil;
        }
        else
        {
                $FormProfil = $_SESSION["form_profil"];
        }
        $FormProfil->Creer(); 
        print("</div>");
        }

        public function Proprietes()
        {
                return array_merge(get_object_vars($this), parent::Proprietes());
        }
}
?>
