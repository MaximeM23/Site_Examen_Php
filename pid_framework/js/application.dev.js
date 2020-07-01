
function NotifierInclusion(NomScript)
{
	console.log("Inclusion de " + NomScript);
}

function AfficherSession()
{
	console.log("Affichage de la session en cours...");
	CApplication_Instance().ExecuterAction(
		"AfficherSession",
		function(NomAction, Donnees, Resultat)
		{
			console.log("La session contient :");
			console.dir(Resultat.Session);
		},
		function(NomAction, Donnees, Erreur)
		{
			console.warn("Impossible de r�cup�rer les donn�es de session !");
		});
}

function AfficherActions()
{
	console.log("Affichage des actions existantes...");
	CApplication_Instance().ExecuterAction(
		"AfficherActions",
		function(NomAction, Donnees, Resultat)
		{
			console.log("Voici les actions existantes :");
			for (var Indice = 0; Indice < Resultat.Actions.length; Indice++)
			{
				console.log("* " + Resultat.Actions[Indice].substr(7));
			}
		},
		function(NomAction, Donnees, Erreur)
		{
			console.warn("Impossible de r�cup�rer les donn�es de session !");
		});
}

function ViderSession(NomsElementsASupprimer)
{
	if ((typeof(NomsElementsASupprimer) == "object") && (typeof(NomsElementsASupprimer.length) == "number"))
	{
		var Tableau = new Array();
		for (var Indice = 0; Indice < NomsElementsASupprimer.length; Indice++)
		{
			if (typeof(NomsElementsASupprimer[Indice]) == "string")
			{
				Tableau.push(NomsElementsASupprimer[Indice]);
			}
		}
		NomsElementsASupprimer = Tableau;
	}
	else if (typeof(NomsElementsASupprimer) == "string")
	{
		NomsElementsASupprimer = new Array(NomsElementsASupprimer);
	}
	else
	{
		NomsElementsASupprimer = new Array();
	}
	CApplication_Instance().ExecuterAction(
		"ViderSession",
		{ elements:NomsElementsASupprimer },
		function(NomAction, Donnees, Resultat)
		{
			console.log("La session a �t� vid�e");
		},
		function(NomAction, Donnees, Erreur)
		{
			console.warn("La session n'a pas pu �tre vid�e !");
		});	
}
