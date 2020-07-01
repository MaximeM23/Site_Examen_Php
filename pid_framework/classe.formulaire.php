<?php
// Permet de gérer un formulaire
class CFormulaire implements IExpositionProprietes
{
	private $m_Identifiant;
	private $m_Url;
	private $m_Commande;
	private $m_Champs;
	private $m_ChampsOrdonnances;
	
	// Accesseur de l'identifiant du formulaire
	public function Identifiant()
	{
		return $this->m_Identifiant;
	}

	// Indique si ce formulaire transmet les données par la méthode HTTP-POST du navigateur
	public function UtilisePost()
	{
		return ($this->m_Url !== false);
	}

	// Indique si ce formulaire transmet les données par communication AJAX
	public function UtiliseAjax()
	{
		return ($this->m_Commande !== false);
	}
	
	// Accesseur/modificateur de l'URL de la page de traitement
	public function Url($Valeur = null)
	{
		if ($Valeur !== null)
		{
			if (!is_string($Valeur) || !file_exists($Valeur)) return false;
			$this->m_Url = $Valeur;
			$this->m_Commande = false;
		}
		return $this->m_Url;
	}
	
	// Accesseur/modificateur de l'URL de la page de traitement
	public function Commande($Valeur = null)
	{
		if ($Valeur !== null)
		{
			if (!is_string($Valeur)) return false;
			if ((strlen($Valeur) < 7) || (substr($Valeur, 0, 7) != "Action_")) $Valeur = "Action_" . $Valeur;
			if (!in_array($Valeur, CApplication::Instance()->RepertorierActions(), true)) return false;
			$this->m_Commande = substr($Valeur, 7);
			$this->m_Url = false;
		}
		return $this->m_Commande;
	}
	
	// Permet de supprimer tous les champs
	public function ViderChamps()
	{
		$this->m_Champs = array();
		$this->m_ChampsOrdonnances = array();
		return true;
	}
	
	// Permet d'ajouter un champ
	public function AjouterChamp($Champ)
	{
		if (!is_object($Champ) || !is_a($Champ, "CChamp") || isset($this->m_Champs[$Champ->Nom()]) || ($Champ->Formulaire() != null)) return false;
		$this->m_Champs[$Champ->Nom()] = $Champ;
		$this->m_ChampsOrdonnances[] = $Champ;
		$Champ->Formulaire($this);
		return true;
	}
	
	// Permet d'insérer un champ
	public function InsererChamp($IndiceInsertion, $Champ)
	{
		if (($IndiceInsertion < 0) || ($IndiceInsertion > count($this->m_ChampsOrdonnances))) return false;
		if (!is_object($Champ) || !is_a($Champ, "CChamp") || isset($this->m_Champs[$Champ->Nom()]) || ($Champ->Formulaire() != null)) return false;
		$this->m_Champs[$Champ->Nom()] = $Champ;
		array_splice($this->m_ChampsOrdonnances, $IndiceInsertion, 0, array($Champ));
		$Champ->Formulaire($this);
		return true;
	}
	
	// Permet de supprimer un champ
	public function SupprimerChamp($NomOuIndiceChampASupprimer)
	{
		if (is_numeric($NomOuIndiceChampASupprimer))
		{
			$IndiceChamp = (int)$NomOuIndiceChampASupprimer;
			if (($IndiceChamp < 0) || ($IndiceChamp >= count($this->m_ChampsOrdonnances))) return false;
			$NomChamp = $this->m_ChampsOrdonnances[$IndiceChamp]->Nom();
		}
		else if (is_string($NomOuIndiceChampASupprimer))
		{
			$NomChamp = $NomOuIndiceChampASupprimer;
			if (!isset($this->m_Champs[$NomChamp])) return false;
			$IndiceChamp = 0;
			while ($this->m_ChampsOrdonnances[$IndiceChamp]->Nom() == $NomChamp) $IndiceChamp++;
		}
		else
		{
			return false;
		}
		unset($this->m_Champs[$NomChamp]);
		unset($this->m_ChampsOrdonnances[$IndiceChamp]);
		return true;
	}
	
	// Retourne la définition d'un champ
	public function Champ($NomOuIndiceChampASupprimer)
	{
		if (is_numeric($NomOuIndiceChampASupprimer))
		{
			$IndiceChamp = (int)$NomOuIndiceChampASupprimer;
			if (($IndiceChamp < 0) || ($IndiceChamp >= count($this->m_ChampsOrdonnances))) return null;
			return $this->m_ChampsOrdonnances[$IndiceChamp];
		}
		else if (is_string($NomOuIndiceChampASupprimer))
		{
			$NomChamp = $NomOuIndiceChampASupprimer;
			if (!isset($this->m_Champs[$NomChamp])) return null;
			return $this->m_Champs[$NomChamp];
		}
		else
		{
			return null;
		}
	}
	
	// Permet de créer le code HTML du formulaire
	public function Creer()
	{
		if ($this->m_Url !== false)
		{
			print("\t\t\t<form id=\""
				. CApplication::Instance()->FormaterEnHtml($this->m_Identifiant)
				. "\" method=\"POST\" action=\""
				. $this->m_Url
				. "\"><ul>\n");
		}
		else if ($this->m_Commande !== false)
		{
			print("\t\t\t<form id=\""
				. CApplication::Instance()->FormaterEnHtml($this->m_Identifiant)
				. "\" data-commande=\""
				. $this->m_Commande
				. "\"><ul>\n");
		}
		else
		{
			return false;
		}
		foreach ($this->m_ChampsOrdonnances as $Champ)
		{
			$Champ->Afficher();
		}
		print("\t\t\t</ul></form>\n");
		return true;
	}
	
	// Permet de "pré-remplir les champs du formulaire avec les données spécifiées
	public function DefinirValeurs($Donnees)
	{
		if (!is_array($Donnees)) return false;
		foreach ($Donnees as $NomChamp=>$ValeurChamp)
		{
			if (isset($this->m_Champs[$NomChamp]))
			{
				$this->m_Champs[$NomChamp]->DefinirValeur($ValeurChamp);
			}
		}
		return true;
	}
	
	// Permet de tester la validité des valeurs des champs du formulaire
	public function TesterValidite()
	{
		$EstValide = true;
		foreach ($this->m_ChampsOrdonnances as $Champ)
		{
			$Champ->MessageErreur(false);
			if (!$Champ->TesterValidite()) $EstValide = false;
		}
		return $EstValide;
	}
	
	// Permet de récupérer les messages d'erreurs pour tous les champs du formulaire
	public function MessagesErreurs()
	{
		$Resultat = array();
		foreach ($this->m_Champs as $NomChamp=>$Champ)
		{
			$Resultat[$NomChamp] = $Champ->MessageErreur();
		}
		return $Resultat;
	}

	// Constructeur
	public function __construct($Identifiant)
	{
		if (!is_string($Identifiant)) throw new Exception("Identifiant indéfini !");
		$Identifiant = trim($Identifiant);
		if ($Identifiant == "") throw new Exception("Identifiant vide !");
		$this->m_Identifiant = $Identifiant;
		$this->m_Url = false;
		$this->m_Commande = false;
		$this->m_Champs = array();
		$this->m_ChampsOrdonnances = array();
	}
	
	// Méthode permettant d'agir avant la sérialisation
	public function __sleep()
	{
		$this->m_Champs = null;
		return CApplication::CollecterNomsProprietes($this);
	}
	
	// Méthode permettant d'agir après la désérialisation
	public function __wakeup()
	{
		if (($this->m_Champs == null) && is_array($this->m_ChampsOrdonnances))
		{
			$this->m_Champs = array();
			foreach ($this->m_ChampsOrdonnances as $Champ)
			{
				$Champ->Formulaire($this);
				$this->m_Champs[$Champ->Nom()] = $Champ;
			}
		}
	}
	
	// Implémentation de la méthode de sérialisation pour la communication de cet objet en Ajax
	public function Proprietes()
	{
		return get_object_vars($this);
	}
};
?>