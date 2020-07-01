
CApplication_Instance("iso-8859-1");

function CApplication(JeuCaracteres)
{
	if (typeof(CApplication.prototype.s_Instance) == "undefined")
	{
		CApplication.prototype.s_Instance = null;
	}
	if (CApplication.prototype.s_Instance != null)
	{
		throw "L'objet Application a déjà été créé !";
	}
	if ((typeof(JeuCaracteres) != "string") || ((JeuCaracteres != "iso-8859-1") && (JeuCaracteres != "utf-8")))
	{
		throw "Jeu de caractères indéfini ou incorrect !";
	}
	console.log("Création de l'objet Application");
	this.m_JeuCaracteres = JeuCaracteres;
	CApplication.prototype.s_Instance = this;
}

function CApplication_Instance(JeuCaracteres)
{
	if (typeof(CApplication.prototype.s_Instance) == "undefined")
	{
		CApplication.prototype.s_Instance = null;
	}
	if (CApplication.prototype.s_Instance == null)
	{
		try
		{
			new CApplication(JeuCaracteres);
		}
		catch (Erreur)
		{
			console.error("Echec de la création de l'instance \"singleton\" de la classe CApplication !\n" + Erreur);
		}
	}
	return CApplication.prototype.s_Instance;
}

CApplication.prototype.EstUtf8 = function()
{
	return this.m_JeuCaracteres === "utf-8";
};

CApplication.prototype.ExecuterAction = function(NomAction, Donnees, TraiterReussite, TraiterEchec)
{
	if ((typeof(NomAction) != "string") || (NomAction == "")) return false;
	if (typeof(Donnees) == "function")
	{
		if (typeof(TraiterReussite) != "undefined")
		{
			if (typeof(TraiterReussite) != "function") return false;
			if (typeof(TraiterEchec) != "undefined") return false;
			TraiterEchec = TraiterReussite;
		}
		TraiterReussite = Donnees;
		Donnees = new Array();
	}
	else
	{
		if (typeof(Donnees) == "undefined")
		{
			Donnees = new Array();
		}
		else if (typeof(Donnees) != "object")
		{
			Donnees = new Array(Donnees);
		}
		/*
		else // (typeof(Donnees) == "object")
			Donnees = Donnees;
		*/
		if (typeof(TraiterReussite) != "function") return false;
		/* if (typeof(TraiterEchec) != "function") TraiterEchec = false; */
		if ((typeof(TraiterEchec) != "function") && (typeof(TraiterEchec) != "undefined")) return false;
		if (typeof(TraiterEchec) == "undefined") TraiterEchec = false;
	}
	console.log("Exécution d'une action " + NomAction);
	$.ajax("pid_framework/requete_ajax.php",
	{
		method : "POST",
		contentType : "application/x-www-form-urlencoded; charset=" + (this.EstUtf8() ? "utf-8" : "iso-8859-1"),
		data : "requete=" + encodeURIComponent(JSON.stringify(
			{
				action : NomAction,
				donnees : Donnees
			})),
		success : function(Donnees, Status, XHR)
		{
			var Resultat = JSON.parse(Donnees);
			if (Resultat.Reussite === true)
			{
				TraiterReussite(NomAction, Donnees, Resultat);
			}
			else
			{
				console.warn("L'action " + NomAction + " a échoué !\n" + ((typeof(Resultat.Erreur) == "string") ? Resultat.Erreur : "Erreur indéterminée !"));
				if (TraiterEchec !== false) TraiterEchec(NomAction, Donnees, Resultat.Erreur);
			}
		},
		error : function(XHR, CodeErreur, TexteErreur)
		{
			console.error("Le serveur n'a pas réussi à traiter l'action " + NomAction + " !\n" + CodeErreur + " : " + TexteErreur);
			if (TraiterEchec !== false) TraiterEchec(NomAction, Donnees, TexteErreur);
		}
	});
};

CApplication.prototype.PosterFormulaire = function(BoutonEnvoi)
{
	if ((typeof(BoutonEnvoi) != "object") || (BoutonEnvoi.tagName != "INPUT") || (BoutonEnvoi.getAttribute("type") != "button")) return false;
	var NomBouton = BoutonEnvoi.name;
	var IdBouton = BoutonEnvoi.id;
	if ((NomBouton == "") || (IdBouton == "") || (IdBouton.length < (NomBouton.length + 2))) return false;
	var Idformulaire = IdBouton.substr(0, IdBouton.length - NomBouton.length - 1);
	var Commande = $("form#" + Idformulaire).data("commande");
	if (Commande == "") return false;
	console.log("Envoi par AJAX des données du formulaire '" + Idformulaire + "' via le bouton '" + NomBouton + "' lançant la commande '" + Commande + "'");
	var Donnees = { };
	$("form#" + Idformulaire + ">ul>li").each(function(iE, Element)
	{
		$(Element).children().each(function(iC, Controle)
		{
			var Valeur = null;
			if ((Controle.tagName == "INPUT") || (Controle.tagName == "TEXTAREA"))
			{
				Valeur = Controle.value;
			}
			else if (Controle.tagName == "SELECT")
			{
				if (!Controle.multiple)
				{
					Valeur = Controle.value;
				}
				else
				{
					Valeur = [ ];
					for (var i = 0; i < Controle.options.length; i++)
					{
						if (Controle.options[i].selected)
						{
							Valeur.push(Controle.options[i].value);
						}
					}
				}
			}
			if (Valeur != null)
			{
				Donnees[Controle.name] = Valeur;
				return false;
			}
		});
	});
	if (true)
	{
		console.dir(Donnees);
		this.ExecuterAction(
			Commande,
			Donnees,
			function(NomAction, Donnees, Resultat)
			{
				console.dir(Resultat);			
			},
			function(NomAction, Donnees, Erreur)
			{
				console.warn("Erreur de traitement par AJAX de la commande d'envoi des données du formulaire '" + Idformulaire + "' !");
				console.dir(Donnees);
			});
	}
	return true;
};
