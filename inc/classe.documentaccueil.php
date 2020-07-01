<?php
class CDocumentAccueil extends CDocument
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

		print("<div id=\"myCarousel\" class=\"carousel slide\" data-ride=\"carousel\">");
		print("<ol class=\"carousel-indicators\">");
		print("<li data-target=\"#myCarousel\" data-slide-to=\"0\" class=\"active\"></li>");
		print("<li data-target=\"#myCarousel\" data-slide-to=\"1\"></li>");
		
		print("<li data-target=\"#myCarousel\" data-slide-to=\"2\"></li>");
		print("</ol>");
		print("<div class=\"carousel-inner\">");
		print("<div class=\"carousel-item active\">");
		print("<img class=\"first-slide img-fluid d-block img-fluid imageheader\" src=\"Images/3.jpg\" alt=\"First slide\">");
		print("<div class=\"container\">");
		print("<div class=\"carousel-caption text-left\">");
		print("<h1>Nos voitures</h1>");
		print("<p><a class=\"btn btn-lg btn-primary\" href=\"page_voiture.php\" role=\"button\">Voir plus &raquo;</a></p>");
		print("</div>");
		print("</div>");
		print("</div>");
		print("<div class=\"carousel-item\">");
		print("<img class=\"second-slide img-fluid d-block img-fluid imageheader\" src=\"Images/2.jpg\" alt=\"Second slide\">");
		print("<div class=\"container\">");
		print("<div class=\"carousel-caption text-right\">");
		print("<h1>Nos voitures</h1>");
		print("<p><a class=\"btn btn-lg btn-primary\" href=\"page_voiture.php\" role=\"button\">Voir plus &raquo;</a></p>");
		print("</div>");
		print("</div>");
		print("</div>");           
		
		print("<div class=\"carousel-item\">");
		print("<img class=\"second-slide img-fluid d-block img-fluid imageheader\" src=\"Images/1.jpg\" alt=\"third slide\">");
		print("<div class=\"container\">");
		print("<div class=\"carousel-caption text-right\">");
		print("<h1>Nos voitures</h1>");
		print("<p><a class=\"btn btn-lg btn-primary\" href=\"page_voiture.php\" role=\"button\">Voir plus &raquo;</a></p>");
		print("</div>");
		print("</div>");
		print("</div>");           
		print("</div>");
		print("<a class=\"carousel-control-prev\" href=\"#myCarousel\" role=\"button\" data-slide=\"prev\">");
		print("<span class=\"carousel-control-prev-icon\" aria-hidden=\"true\"></span>");
		print("<span class=\"sr-only\">Previous</span>");
		print("</a>");
		print("<a class=\"carousel-control-next\" href=\"#myCarousel\" role=\"button\" data-slide=\"next\">");
		print("<span class=\"carousel-control-next-icon\" aria-hidden=\"true\"></span>");
		print("<span class=\"sr-only\">Next</span>");
		print("</a>");
		print("</div>");

		print("<div class=\"container\">");
		print("<div class=\"row\">");
		print("<div class=\"col\">");
		print("<img class=\"rounded-circle m-4 ml-5 mt-5\" src=\"Images/3small.jpg\" alt=\"Generic placeholder image\" width=\"140\" height=\"140\">");
		print("<h2 class=\"ml-5\">Nos voitures</h2>");
		print("<p class=\"ml-5 text-justify\">Donec sed odio dui. Etiam porta sem malesuada magna mollis euismod. Nullam id dolor id nibh ultricies vehicula ut id elit. Morbi leo risus, porta ac consectetur ac, vestibulum at eros. Praesent commodo cursus magna.</p>");
		print("<p><a class=\"btn btn-secondary ml-5\" href=\"page_voiture.php\" role=\"button\">Voir plus &raquo;</a></p>");
		print("</div>");		
		print("</div>");
		
	}
	
	public function Proprietes()
	{
		return array_merge(get_object_vars($this), parent::Proprietes());
	}
};
?>