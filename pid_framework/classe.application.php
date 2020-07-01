<?php
class CApplication implements IExpositionProprietes
{
	private static $s_Instance;
	
	private $m_CheminPID;
	private $m_JeuCaracteres;
	private $m_EstUtf8;
	private $m_EnProduction;
	private $m_FichiersJS;
	private $m_BD;
	private $m_DocumentCourant;
	
	private $m_DeclarationJeuCaracteres;
	
	public static function Instance()
	{
		if (!isset($_SESSION)) session_start();
		if (CApplication::$s_Instance == null)
		{
			if (defined("AUTOLOAD_APPLICATION_NOM_CLASSE"))
			{
				try
				{
					$NomClasse = AUTOLOAD_APPLICATION_NOM_CLASSE;
					new $NomClasse();
				}
				catch (Exception $Erreur)
				{
					new CApplication();
				}
			}
			else
			{
				new CApplication();
			}
		}
		return CApplication::$s_Instance;
	}
	
	/////////////////////////////////
	// ACCESSEURS ET MODIFICATEURS //
	/////////////////////////////////

	public function JeuCaracteres()
	{
		return $this->m_JeuCaracteres;
	}
	
	public function EnProduction()
	{
		return $this->m_EnProduction;
	}
	
	public function DocumentCourant($Valeur = null)
	{
		if ($Valeur !== null)
		{	// set
			if (!is_object($Valeur) || !is_a($Valeur, "CDocument")) return false;
			$this->m_DocumentCourant = $Valeur;
			return true;
		}
		else
		{	// get
			return $this->m_DocumentCourant;
		}
	}
	
	public function DesenregistrerDocumentCourant()
	{
		$this->m_DocumentCourant = null;
		return true;
	}
	
	public function AfficherDocumentCourant($NomClasseDocument, $InstanciateurDocument)
	{
		if (($this->m_DocumentCourant == null) || !is_a($this->m_DocumentCourant, $NomClasseDocument))
		{
			if (!is_callable($InstanciateurDocument)) return false;
			$NouveauDocument = $InstanciateurDocument();
			if (!is_object($NouveauDocument) || !is_a($NouveauDocument, $NomClasseDocument)) return false;
			$this->DocumentCourant($NouveauDocument);
		}
		return $this->m_DocumentCourant->CreerPage();
	}
	
	protected function AjouterFichierJS($NomFichier)
	{
		if (!is_string($NomFichier) || !file_exists($NomFichier) || in_array($NomFichier, $this->m_FichiersJS)) return false;
		$this->m_FichiersJS[] = $NomFichier;
		return true;
	}
	
	protected function InsererFichierJS($NomFichier)
	{
		if (!is_string($NomFichier) || !file_exists($NomFichier) || in_array($NomFichier, $this->m_FichiersJS)) return false;
		array_unshift($this->m_FichiersJS, $NomFichier);
		return true;
	}
	
	protected function SupprimerFichierJS($NomFichier)
	{
		if (!is_string($NomFichier) || (($Indice = array_search($NomFichier, $this->m_FichiersJS)) === false)) return false;
		unset($this->m_FichiersJS[$Indice]);
		return true;
	}
	
	public function FichiersJS()
	{
		return $this->m_FichiersJS;
	}
	
	public function BD()
	{
		return $this->m_BD;
	}
	
	protected function DefinirBD($BD)
	{
		if (!is_object($BD) || !is_a($BD, "CBD")) return false;
		$this->m_BD = $BD;
		return true;
	}
	
	/////////////////////////////////////////
	// METHODES DU CYCLE DE VIE DE L'OBJET //
	/////////////////////////////////////////
	
	public function __construct()
	{
		require_once("_configuration.php");
		$this->m_CheminPID = CHEMIN_PID;
		$this->m_JeuCaracteres = JEU_CARACTERES;
		$this->m_EstUtf8 = (JEU_CARACTERES == "utf-8");
		$this->m_EnProduction = EN_PRODUCTION;
		$this->m_DeclarationJeuCaracteres = false;
		$this->m_FichiersJS = array();
		$this->AjouterFichierJS(CHEMIN_PID . "js/" . (EN_PRODUCTION ? "jquery-3.3.1.min.js" : "jquery-3.3.1.js"));
		$this->AjouterFichierJS(CHEMIN_PID . "js/" . "application.js.php");
		$this->m_DocumentCourant = null;
		$this->m_BD = null;
		CApplication::$s_Instance = $this;
		if (!isset($_SESSION)) session_start();
		$_SESSION["application"] = $this;
	}
	
	public function __sleep()
	{
		$this->m_DeclarationJeuCaracteres = false;
		return CApplication::CollecterNomsProprietes($this);
	}
	
	public function __wakeup()
	{
		CApplication::$s_Instance = $this;
		$this->m_DeclarationJeuCaracteres = false;
	}

	public function Proprietes()
	{
		return get_object_vars($this);
	}
	
	//////////////////////////////////////////////////////////////////
	// METHODES RELATIVES AUX ACTIONS GEREES PAR COMMUNICATION AJAX //
	//////////////////////////////////////////////////////////////////

	public function Action_TraiterRequete()
	{
		if (isset($_POST["requete"]))
		{
			$Requete = $this->DecoderJson($_POST["requete"]);
			if (isset($Requete["action"]) && isset($Requete["donnees"]))
			{
				$NomMethode = "Action_" . $Requete["action"];
				if (!in_array($NomMethode, array("Action_TraiterRequete", "Action_RepondreReussite", "Action_RepondreEchec"))
					&& method_exists($this, $NomMethode)
					)
				{
					eval("\$this->$NomMethode(\$Requete[\"donnees\"]);");
				}
				else
				{
					$this->Action_RepondreEchec("Action inconnue !");
				}
			}
			else
			{
				$this->Action_RepondreEchec("Action non définie ou incorrectement définie !");
			}
		}
		else
		{
			$this->Action_RepondreEchec("Requête non définie !");
		}
	}
	
	private function Action_AfficherSession($Donnees)
	{
		if (!$this->m_EnProduction)
		{
			$this->Action_RepondreReussite(array("Session"=>$_SESSION));
		}
		else
		{
			$this->Action_RepondreEchec("Action non autorisée car l'application est en production !");
		}
	}
	
	public function RepertorierActions()
	{
		$Actions = array();
		foreach (get_class_methods(get_class($this)) as $NomMethode)
		{
			if ((strlen($NomMethode) >= 8)
			 && (substr($NomMethode, 0, 7) == "Action_")
			 && !in_array($NomMethode, array("Action_TraiterRequete", "Action_RepondreReussite", "Action_RepondreEchec"), true)
			 )
			{
				$Actions[] = $NomMethode;
			}
		}
		return $Actions;
	}
	
	private function Action_AfficherActions($Donnees)
	{
		if (!$this->m_EnProduction)
		{
			$Actions = $this->RepertorierActions();
			sort($Actions);
			$this->Action_RepondreReussite(array("Actions"=>$Actions));
		}
		else
		{
			$this->Action_RepondreEchec("Action non autorisée car l'application est en production !");
		}
	}
	
	private function Action_ViderSession($Donnees)
	{
		if (!$this->m_EnProduction)
		{
			if (isset($Donnees["elements"]) && is_array($Donnees["elements"]))
			{
				$NombreElementsSupprimes = 0;
				if (count($Donnees["elements"]) == 0)
				{
					// Tout supprimer
					$NombreElementsSupprimes = count($_SESSION);
				}
				else 
				{
					foreach ($Donnees["elements"] as $Cle=>$NomElement)
					{
						if (is_string($NomElement) && isset($_SESSION[$NomElement]))
						{
							$NombreElementsSupprimes++;
						}
					}
				}
				if ($NombreElementsSupprimes > 0)
				{
					if ($NombreElementsSupprimes == count($_SESSION))
					{
						$_SESSION = array();
					}
					else
					{
						foreach ($Donnees["elements"] as $Cle=>$NomElement)
						{
							if (is_string($NomElement))
							{
								unset($_SESSION[$NomElement]);
							}
						}
					}
					$this->Action_RepondreReussite();
				}
				else
				{
					$this->Action_RepondreEchec("Session vide ou aucun élément spécifié n'existe !");
				}
			}
			else
			{
				$this->Action_RepondreEchec("Pas de paramètre décrivant le ou les éléments a supprimé de la session !");
			}
		}
		else
		{
			$this->Action_RepondreEchec("Action non autorisée car l'application est en production !");
		}
	}
	
	protected function Action_RepondreReussite($Donnees = null)
	{
		if (is_string($Donnees) || is_bool($Donnees) || is_int($Donnees) || is_long($Donnees) || is_float($Donnees) || is_double($Donnees))
		{
			$Reponse["Donnees"] = $Donnees;
		}
		else if (is_array($Donnees) || is_object($Donnees))
		{
			if (is_object($Donnees))
			{
				$Donnees = ($Donnees instanceof IExpositionProprietes) ? $Donnees->Proprietes() : get_object_vars($Donnees);
			}
			if (isset($Donnees["Reussite"]) && (!is_bool($Donnees["Reussite"]) || $Donnees["Reussite"] === false))
			{
				throw new Exception("Un élément nommé Reussite est passé dans les données de réponse, mais il ne correspond pas à la valeur booléenne true !");
			}
			$Reponse = $Donnees;
		}
		$Reponse["Reussite"] = true;
		print($this->EncoderJson($Reponse));
		return false;
	}
	
	protected function Action_RepondreEchec($MessageErreur = null)
	{
		if (is_string($MessageErreur) || is_bool($MessageErreur) || is_int($MessageErreur) || is_long($MessageErreur) || is_float($MessageErreur) || is_double($MessageErreur))
		{
			$Reponse["Erreur"] = $MessageErreur;
		}
		else if (is_array($MessageErreur) || is_object($MessageErreur))
		{
			if (is_object($MessageErreur))
			{
				$MessageErreur = ($MessageErreur instanceof IExpositionProprietes) ? $MessageErreur->Proprietes() : get_object_vars($MessageErreur);
			}
			if (isset($MessageErreur["Reussite"]) && (!is_bool($MessageErreur["Reussite"]) || $MessageErreur["Reussite"] === false))
			{
				throw new Exception("Un élément nommé Reussite est passé dans les données de réponse, mais il ne correspond pas à la valeur booléenne false !");
			}
			$Reponse = $MessageErreur;
		}
		$Reponse["Reussite"] = false;
		print($this->EncoderJson($Reponse));
		return false;
	}
	
	//////////////////////////
	// METHODES UTILITAIRES //
	//////////////////////////
	
	public static function CollecterNomsProprietes($Objet)
	{
		$DescriptionClasse = new ReflectionClass($Objet);
		$Resultat = array();
		while ($DescriptionClasse)
		{
			$NomClasse = $DescriptionClasse->getName();
			foreach ($DescriptionClasse->getProperties() as $Propriete)
			{
				$NomPropriete = $Propriete->getName();
				if (!$Propriete->isStatic())
				{
					if ($Propriete->isPublic())
					{
						$Resultat[] = $NomPropriete;
					}
					else if ($Propriete->isProtected())
					{
						$Resultat[] = "\0*\0$NomPropriete";
					}
					else if ($Propriete->isPrivate())
					{
						$Resultat[] = "\0$NomClasse\0$NomPropriete";
					}
				}
			}
			$DescriptionClasse = $DescriptionClasse->getParentClass();
		}
		return $Resultat;
	}
	
	public function SignalerErreur($IntituleErreur, $MessageErreur)
	{
		@file_put_contents("../erreurs.txt", date("Y/m/d H:i:s") . "\n" . $IntituleErreur . "\n" . $MessageErreur . "\n\n", FILE_APPEND);
	}
	
	public function TableauEnChaine($Tableau, $SeparateurElement = " ; ", $SeparateurCleValeur = " : ")
	{
		if (!is_array($Tableau)) return "";
		$Chaine = "";
		foreach ($Tableau as $Cle=>$Valeur)
		{
			if ($Chaine != "") $Chaine .= $SeparateurElement;
			$Chaine .= $Cle . $SeparateurCleValeur . $Valeur;
		}
		return $Chaine;
	}
	
	public function DeclarerJeuCaracteres($SousTypeMime = "html")
	{
		if ($this->m_DeclarationJeuCaracteres || !is_string($SousTypeMime)) return false;
		$this->m_DeclarationJeuCaracteres = true;
		header("content-type:text/$SousTypeMime;charset=" . $this->m_JeuCaracteres);
		return true;
	}
	
	private function EncoderJson($Contenu)
	{
		if (!$this->m_EstUtf8)
		{
			$Contenu = $this->TransformerEnUtf8($Contenu);
		}
		$Resultat = json_encode($Contenu);
		if ($Resultat == "")
		{
			throw new Exception("Problème de transformation en JSON de $Contenu !");
		}
		return $Resultat;
	}

	private function DecoderJson($Contenu)
	{
		if (!is_string($Contenu)) return array();
		$Resultat = json_decode($Contenu, true);
		if (!$this->m_EstUtf8)
		{
			$Resultat = $this->TransformerEnAnsi($Resultat);
		}
		return is_array($Resultat) ? $Resultat : array($Resultat);
	}

	private function TransformerEnUtf8($Contenu)
	{
		if (is_string($Contenu))
		{
			return utf8_encode($Contenu);
		}
		else if (is_array($Contenu))
		{
			$Tableau = array();
			foreach ($Contenu as $Cle=>$Valeur)
			{
				$Tableau[is_int($Cle) ? $Cle : $this->TransformerEnUtf8($Cle)] = $this->TransformerEnUtf8($Valeur);
			}
			return $Tableau;
		}
		else if (is_object($Contenu))
		{
			return $this->TransformerEnUtf8(($Contenu instanceof IExpositionProprietes) ? $Contenu->Proprietes() : get_object_vars($Contenu));
		}
		else if (!is_bool($Contenu) && !is_int($Contenu) && !is_long($Contenu) && !is_float($Contenu) && !is_double($Contenu))
		{
			return null;
		}
		else
		{
			return $Contenu;
		}
	}

	private function TransformerEnAnsi($Contenu)
	{
		if (is_string($Contenu))
		{
			return utf8_decode($Contenu);
		}
		else if (is_array($Contenu))
		{
			$Tableau = array();
			foreach ($Contenu as $Cle=>$Valeur)
			{
				$Tableau[is_int($Cle) ? $Cle : $this->TransformerEnAnsi($Cle)] = $this->TransformerEnAnsi($Valeur);
			}
			return $Tableau;
		}
		else if (is_object($Contenu))
		{
			return $this->TransformerEnAnsi(($Contenu instanceof IExpositionProprietes) ? $Contenu->Proprietes() : get_object_vars($Contenu));
		}
		else if (!is_bool($Contenu) && !is_int($Contenu) && !is_long($Contenu) && !is_float($Contenu) && !is_double($Contenu))
		{
			return null;
		}
		else
		{
			return $Contenu;
		}
	}
	
	public function FormaterEnHtml($Texte)
	{
		return str_replace(array("&", "<", ">", "\""), array("&amp;", "&lt;", "&gt;", "&quot;"), $Texte);
		//return htmlentities($Texte, ENT_COMPAT | ENT_HTML5, $this->m_JeuCaracteres);
	}
};
?>