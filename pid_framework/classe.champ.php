<?php
// Permet de décrire tout champ de formulaire
abstract class CChamp implements IExpositionProprietes
{
	// Méthode permettant de définir la "valeur courante" du champ en fonction de données postées ou prédéfinies
	public abstract function DefinirValeur($Valeur);
	
	// Méthode permettant de générer le code HTML de ce champ
	public abstract function Afficher();
	
	// Méthode permettant de tester la validité de ce champ
	public abstract function TesterValidite();
	
	private $m_Formulaire;
	private $m_Nom;
	private $m_Etiquette;
	private $m_MessageErreur;
	private $m_MessageReussite;
	
	// Accesseur/modificateur du formulaire de champ
	public function Formulaire($Valeur = null)
	{
		if (($this->m_Formulaire == null) && is_object($Valeur) && is_a($Valeur, "CFormulaire"))
		{
			$this->m_Formulaire = $Valeur;
		}
		return $this->m_Formulaire;
	}
	
	// Accesseur de l'identifiant de champ
	public function Identifiant()
	{
		if ($this->m_Formulaire == null) throw new Exception("Interrogation de l'identifiant d'un champ non attaché à un formulaire !");
		return $this->m_Formulaire->Identifiant() . "_" . $this->m_Nom;
	}
	
	// Accesseur du nom de champ
	public function Nom()
	{
		return $this->m_Nom;
	}
	
	// Accesseur/modificateur de l'étiquette de champ
	public function Etiquette($Valeur = null)
	{
		if ($Valeur !== null)
		{
			if (!is_string($Valeur) && ($Valeur !== false)) return false;
			$this->m_Etiquette = $Valeur;
		}
		return $this->m_Etiquette;
	}
	
	// Accesseur/modificateur du message d'erreur relatif à ce champ
	public function MessageErreur($Valeur = null)
	{
		if ($Valeur !== null)
		{
			if (!is_string($Valeur) && ($Valeur !== false)) return false;
			$this->m_MessageErreur = $Valeur;
		}
		return $this->m_MessageErreur;
	}

	
	// Accesseur/modificateur du message d'erreur relatif à ce champ
	public function MessageReussite($Valeur = null)
	{
		if ($Valeur !== null)
		{
			if (!is_string($Valeur) && ($Valeur !== false)) return false;
			$this->m_MessageReussite = $Valeur;
		}
		return $this->m_MessageReussite;
	}

	// Méthode permettant d'écrire le début du code HTML de ce champ
	protected function AfficherDebutHtml()
	{
		print("<li id=\"element_" . CApplication::Instance()->FormaterEnHtml($this->Identifiant()) . "\">");
		if ($this->m_Etiquette !== false)
		{
			print("<label for=\""
				. CApplication::Instance()->FormaterEnHtml($this->Identifiant())
				. "\">"
				. CApplication::Instance()->FormaterEnHtml($this->m_Etiquette)
				. "</label>");
		}
	}

	// Méthode permettant d'écrire la fin du code HTML de ce champ
	protected function AfficherFinHtml()
	{
		if ($this->m_MessageErreur !== false)
		{
			print("<div class=\"erreur\"><span>"
				. CApplication::Instance()->FormaterEnHtml($this->m_MessageErreur)
				. "</span></div>");
		}
		else if(($this->m_MessageReussite !== false) && (($this->m_MessageErreur === "")||($this->m_MessageErreur === false)))
		{
			print("<div class=\"reussite\"><span>"
			. CApplication::Instance()->FormaterEnHtml($this->m_MessageReussite)
			. "</span></div>");
		}
		print("</li>");
	}

	// Constructeur
	public function __construct($Nom, $Etiquette = null)
	{
		if (!is_string($Nom)) throw new Exception("Nom indéfini !");
		$Nom = trim($Nom);
		if ($Nom == "") throw new Exception("Nom vide !");
		$this->m_Formulaire = null;
		$this->m_Nom = $Nom;
		$this->m_Etiquette = false;
		$this->m_MessageErreur = false;
		$this->m_MessageReussite = false;
		$this->Etiquette($Etiquette);
	}
	
	// Méthode permettant d'agir avant la sérialisation
	public function __sleep()
	{
		$this->m_Formulaire = null;
		return CApplication::CollecterNomsProprietes($this);
	}
	
	// Méthode permettant d'agir après la désérialisation
	public function __wakeup()
	{
	}
	
	// Implémentation de la méthode de sérialisation pour la communication de cet objet en Ajax
	public function Proprietes()
	{
		$Resultat = get_object_vars($this);
		$Resultat["m_Formulaire"] = null;
		return $Resultat;		
	}
};
?>