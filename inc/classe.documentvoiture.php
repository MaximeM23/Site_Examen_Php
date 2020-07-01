<?php
class CDocumentVoiture extends CDocument
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
		// Contenu de la page
		
		if((isset($_SESSION["UtilisateurConnecter"])) && ($_SESSION["UtilisateurConnecter"]["role"] == "Administrateur"))
		{
			print("<div class=\"card vehiculedispo mr-4 mt-2 ml-4 mb-2 \">");
			print("<div class=\"card-header DarkBlue \">");
			print("Nouveau véhicule");
			print("</div>");
			print("<div class=\"card-body\">");
			print("<blockquote class=\"blockquote mb-0 \">");			
			print("<p class=\"text-primary\">");
			if (!isset($_SESSION["form_AjoutVehicule"]))
			{
				$FormAjoutVehicule = new CFormulaire("form_AjoutVehicule");
				$FormAjoutVehicule->Commande("Action_TraiterFormAjoutVehicule");
				$FormAjoutVehicule->Url("inc/traitement_formulaire_ajout.php");             
						
				$FormAjoutVehicule->AjouterChamp(new CChampTexte("text","Modele","form-control","Modèle"));        
				$FormAjoutVehicule->AjouterChamp(new CChampTexte("number","PrixJournee","form-control","Prix pour la journée"));        
				$FormAjoutVehicule->AjouterChamp(new CChampTexte("number","PrixDemiJournee","form-control","Prix pour la demi-journée"));           
				$FormAjoutVehicule->AjouterChamp(new CChampTexte("file","NomImage","","Nouvelle image"));                     
				$FormAjoutVehicule->Champ("Modele")->LongueurMinimale(2);
				$FormAjoutVehicule->Champ("Modele")->LongueurMaximale(100);
				$FormAjoutVehicule->Champ("PrixJournee")->LongueurMinimale(2);
				$FormAjoutVehicule->Champ("PrixJournee")->LongueurMaximale(5);
				$FormAjoutVehicule->Champ("PrixDemiJournee")->LongueurMinimale(2);
				$FormAjoutVehicule->Champ("PrixDemiJournee")->LongueurMaximale(5);
				$FormAjoutVehicule->Champ("NomImage")->LongueurMinimale(0);
				$FormAjoutVehicule->Champ("NomImage")->LongueurMaximale(200);
				$FormAjoutVehicule->AjouterChamp(new CChampEnvoi("envoi", null, "Valider"," btnInscription btn btn-primary mt-2"));
				$_SESSION["form_AjoutVehicule"] = $FormAjoutVehicule;
			}
			else
			{
				$FormAjoutVehicule = $_SESSION["form_AjoutVehicule"];
			}
        	$FormAjoutVehicule->Creer(); 
			print("</p>");
			print("</blockquote class=\"blockquote mb-0\">");								
			print("</div>");
			print("</div>");
		}

		foreach (CApplication::Instance()->BD()->EnumererEnregistrements("SELECT * FROM vehicule ORDER BY id_vehicule ASC") as $Enregistrement)
		{

			print("<div class=\"card vehiculedispo mr-4 mt-2 ml-4 mb-2 \">");
			print("<div class=\"card-header DarkBlue \">");
			print($Enregistrement["modele"]);
			print("</div>");
			print("<div class=\"card-body\">");
			print("<blockquote class=\"blockquote mb-0 \">");
			print("<img src=" . $Enregistrement["nom_image"] . " class=\"float-left mt--2-5\"><p class=\"text-primary\">
					Pour rouler avec ce véhicule, vous pouvez le commander une journée au prix de ". $Enregistrement["prix_journee"] . "€.</br>
					Pour le commander une demie journée le prix est de ".  $Enregistrement["prix_demi_journee"] ." €</p>");
			print("</blockquote>");
			if(isset($_SESSION["UtilisateurConnecter"]))
			{
				print("<button class=\" btn-primary mt--2-5\">Faire une réservation</button>");			
			}
			else
			{
				$_SESSION["CommandeVehicule"] = true;
				print("<a href=\"page_connexion.php\"><button class=\" btn-primary mt--2-5\">Faire une réservation</button></a>");	
			}
			print("</div>");
			print("</div>");


		}

	}
	
	public function Proprietes()
	{
		return array_merge(get_object_vars($this), parent::Proprietes());
	}
}
?>