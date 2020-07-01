<?php
// Classe gérant une connexion à un serveur MySQL et permettant d'exploiter une base de données via des requêtes SQL
class CBD implements IExpositionProprietes
{
	// Membre privé stockant la référence de l'objet PDO d'accès à un serveur MySQL
	private $m_PDO;
	// Membre privé stockant le nom de l'utilisateur MySQL
	private $m_Utilisateur;
	// Membre privé stockant le mot de passe de l'utilisateur MySQL
	private $m_MotDePasse;
	// Membre privé stockant le nom de la base de données MySQL
	private $m_NomBaseDonnees;
	// Membre privé stockant l'adresse du serveur MySQL
	private $m_AdresseServeur;

	// Constructeur
	public function __construct($Utilisateur, $MotDePasse, $NomBaseDonnees, $AdresseServeur = "localhost")
	{
		$this->m_PDO = null;
		$this->m_Utilisateur = $Utilisateur;
		$this->m_MotDePasse = $MotDePasse;
		$this->m_NomBaseDonnees = $NomBaseDonnees;
		$this->m_AdresseServeur = $AdresseServeur;
	}
	
	// Méthode permettant d'agir avant la sérialisation
	public function __sleep()
	{
		$this->SeDeconnecter();
		return CApplication::CollecterNomsProprietes($this);
	}
	
	// Méthode permettant d'agir après la désérialisation
	public function __wakeup()
	{
		$this->m_PDO = null;
	}
	
	// Méthode permettant de se connecter au serveur MySQL
	public function SeConnecter()
	{
		if ($this->m_PDO != null) return true;
		try
		{
			$this->m_PDO = new PDO("mysql:dbname=" . $this->m_NomBaseDonnees . ";host=" . $this->m_AdresseServeur, $this->m_Utilisateur, $this->m_MotDePasse);
			$this->m_PDO->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			return true;
		}
		catch (Exception $Erreur)
		{
			CApplication::Instance()->SignalerErreur("Erreur dans CBD::SeConnecter() !", $Erreur->getMessage());
			return false;
		}
	}
	
	// Méthode permettant de se déconnecter du serveur MySQL
	public function SeDeconnecter()
	{
		if ($this->m_PDO == null) return true;
		$this->m_PDO = null;
		return true;
	}
	
	// Méthode permettant d'exécuter une requête d'action
	public function Executer($Requete = null, $Arguments = null)
	{
		if (($Requete == null) || !is_string($Requete)) return false;
		$Requete = trim($Requete);
		$MotCle = strtoupper(substr($Requete, 0, 6));
		if (($MotCle != "INSERT") && ($MotCle != "UPDATE") && ($MotCle != "DELETE")) return false;
		if (!$this->SeConnecter()) return false;
		try
		{
			$Commande = $this->m_PDO->prepare($Requete);
			$Resultat = (is_array($Arguments)) ? @$Commande->execute($Arguments) : @$Commande->execute();
			if (!$Resultat) return false;
			if (($MotCle == "INSERT") && ($Commande->rowCount() == 1))
			{
				return $this->m_PDO->lastInsertId();
			}
			else
			{
				return $Commande->rowCount();
			}
		}
		catch (Exception $Erreur)
		{
			CApplication::Instance()->SignalerErreur("Erreur dans CBD::Executer() !\nRequête : $Requete" . (is_array($Arguments) ? "\nArguments : " . CApplication::Instance()->TableauEnChaine($Arguments) : "") , $Erreur->getMessage());
			return false;
		}
	}
	
	// Méthode permettant d'énumérer chaque enregistrement résultant d'une requête de consultation
	public function EnumererEnregistrements($Requete = null, $Arguments = null)
	{
		if (($Requete == null) || !is_string($Requete)) return CEnumerateurEnregistrement::Aucun();
		$Requete = trim($Requete);
		$MotCle = strtoupper(substr($Requete, 0, 6));
		if ($MotCle != "SELECT") return CEnumerateurEnregistrement::Aucun();
		if (!$this->SeConnecter()) return CEnumerateurEnregistrement::Aucun();
		try
		{
			$Commande = $this->m_PDO->prepare($Requete);
			$Resultat = (is_array($Arguments)) ? @$Commande->execute($Arguments) : @$Commande->execute();
			if (!$Resultat) return CEnumerateurEnregistrement::Aucun();
			return new CEnumerateurEnregistrement($Commande);
		}
		catch (Exception $Erreur)
		{
			CApplication::Instance()->SignalerErreur("Erreur dans CBD::EnumererEnregistrements() !\nRequête : $Requete" . (is_array($Arguments) ? "\nArguments : " . CApplication::Instance()->TableauEnChaine($Arguments) : "") , $Erreur->getMessage());
			return CEnumerateurEnregistrement::Aucun();
		}
	}
	
	// Méthode permettant de récupérer le premier enregistrement résultant d'une requête de consultation
	public function RecupererEnregistrement($Requete = null, $Arguments = null)
	{
		if (($Requete == null) || !is_string($Requete)) return array();
		$Requete = trim($Requete);
		$MotCle = strtoupper(substr($Requete, 0, 6));
		if ($MotCle != "SELECT") return array();
		if (!$this->SeConnecter()) return array();
		try
		{
			$Commande = $this->m_PDO->prepare($Requete);
			$Resultat = (is_array($Arguments)) ? @$Commande->execute($Arguments) : @$Commande->execute();
			if (!$Resultat) return array();
			$Resultat = $Commande->fetch(PDO::FETCH_BOTH);
			return ($Resultat === false) ? array() : $Resultat;
		}
		catch (Exception $Erreur)
		{
			CApplication::Instance()->SignalerErreur("Erreur dans CBD::RecupererEnregistrement() !\nRequête : $Requete" . (is_array($Arguments) ? "\nArguments : " . CApplication::Instance()->TableauEnChaine($Arguments) : "") , $Erreur->getMessage());
			return array();
		}
	}
	
	// Méthode permettant de récupérer la valeur du premier champ du premier enregistrement résultant d'une requête de consultation si possible, sinon la valeur par défaut spécifiée
	public function RecupererValeur($Requete = null, $Arguments = null)
	{
		if (($Requete == null) || !is_string($Requete)) return null;
		$Requete = trim($Requete);
		$MotCle = strtoupper(substr($Requete, 0, 6));
		if ($MotCle != "SELECT") return null;
		if (!$this->SeConnecter()) return null;
		try
		{
			$Commande = $this->m_PDO->prepare($Requete);
			$Resultat = (is_array($Arguments)) ? @$Commande->execute($Arguments) : @$Commande->execute();
			if (!$Resultat) return null;
			$Enregistrement = $Commande->fetch(PDO::FETCH_NUM);
			return ($Enregistrement !== false) ? $Enregistrement[0] : null;
		}
		catch (Exception $Erreur)
		{
			CApplication::Instance()->SignalerErreur("Erreur dans CBD::RecupererValeur() !\nRequête : $Requete" . (is_array($Arguments) ? "\nArguments : " . CApplication::Instance()->TableauEnChaine($Arguments) : "") , $Erreur->getMessage());
			return null;
		}
	}
	
	// Méthode permettant de récupérer la valeur du premier champ du premier enregistrement résultant d'une requête de consultation si possible, sinon la valeur par défaut spécifiée
	public function RecupererValeurOuDefaut($Requete = null, $Arguments = null, $ValeurParDefaut = null)
	{
		if (($Requete == null) || !is_string($Requete)) return $ValeurParDefaut;
		$Requete = trim($Requete);
		$MotCle = strtoupper(substr($Requete, 0, 6));
		if ($MotCle != "SELECT") return $ValeurParDefaut;
		if (!$this->SeConnecter()) return $ValeurParDefaut;
		try
		{
			$Commande = $this->m_PDO->prepare($Requete);
			$Resultat = (is_array($Arguments)) ? @$Commande->execute($Arguments) : @$Commande->execute();
			if (!$Resultat) return $ValeurParDefaut;
			$Enregistrement = $Commande->fetch(PDO::FETCH_NUM);
			return ($Enregistrement !== false) ? $Enregistrement[0] : $ValeurParDefaut;
		}
		catch (Exception $Erreur)
		{
			CApplication::Instance()->SignalerErreur("Erreur dans CBD::RecupererValeurOuDefaut() !\nRequête : $Requete" . (is_array($Arguments) ? "\nArguments : " . CApplication::Instance()->TableauEnChaine($Arguments) : "") , $Erreur->getMessage());
			return $ValeurParDefaut;
		}
	}
	
	public function Proprietes()
	{
		$Resultat = get_object_vars($this);
		$Resultat["m_MotDePasse"] = str_repeat("*", strlen($Resultat["m_MotDePasse"]));
		return $Resultat;
	}
};

// Classe permettant l'itération sur les résultats de requête de consultation
class CEnumerateurEnregistrement implements Iterator
{
	// Membre statique privé stockant une itération d'aucun enregistrement
	private static $s_Aucun;

	// Accesseur statique publique fournissant une itération d'aucun enregistrement
	public static function Aucun()
	{
		if (!CEnumerateurEnregistrement::$s_Aucun) CEnumerateurEnregistrement::$s_Aucun = new CEnumerateurEnregistrement(null);
		return CEnumerateurEnregistrement::$s_Aucun;
	}
	
	// Membre privé référençant l'objet résultant de l'exécution d'une requête de consultation
	private $m_Commande;
	
	// Membre privé stockant le contenu du dernier enregistrement lu
	private $m_DernierEnregistrement;
	
	// Membre privé stockant le contenu du dernier enregistrement lu
	private $m_IndiceEnregistrement;
	
	// Contructeur de cet objet d'itération des enregistrements résultant d'une requête de consultation
	public function __construct($Commande)
	{
		$this->m_Commande = $Commande;
		$this->m_DernierEnregistrement = false;
		$this->m_IndiceEnregistrement = 0;
	}
	
	// Méthode permettant de récupérer l'élément courant de l'itération
	public function current()
	{
		return is_array($this->m_DernierEnregistrement) ? $this->m_DernierEnregistrement : array();
	}
	
	// Méthode permettant de récupérer la clé de l'élément courant de l'itération
	public function key()
	{
		return $this->m_IndiceEnregistrement;
	}
	
	// Méthode permettant de passer à l'élément suivant de l'itération
	public function next()
	{
		$this->m_IndiceEnregistrement++;
	}
	
	// Méthode permettant de recommencer l'itération (si possible)
	public function rewind()
	{
		$this->m_IndiceEnregistrement = 0;
	}
	
	// Méthode permettant de vérifier si un autre élément est encore disponible dans l'itération en cours
	public function valid()
	{
		if ($this->m_Commande == null) return false;
		$this->m_DernierEnregistrement = $this->m_Commande->fetch(PDO::FETCH_BOTH);
		return ($this->m_DernierEnregistrement !== false);
	}
};
?>