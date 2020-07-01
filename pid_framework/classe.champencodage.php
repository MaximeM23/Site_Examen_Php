<?php
// Permet de décrire tout champ d'encodage dans un formulaire
abstract class CChampEncodage extends CChamp
{
	private $m_Valeur;
	private $m_MarqueSubstitutive;
	private $m_EstRequis;
	
	// Méthode permettant de définir la "valeur courante" du champ en fonction de données postées ou prédéfinies
	public function DefinirValeur($Valeur)
	{
		return $this->Valeur($Valeur);
	}
	
	// Accesseur/modificateur de valeur du champ
	public function Valeur($Valeur = null)
	{
		if ($Valeur !== null)
		{
			if (!is_string($Valeur) && !is_numeric($Valeur)) return false;
			$this->m_Valeur = $Valeur;
		}
		return $this->m_Valeur;
	}
	
	// Accesseur/modificateur de la marque substitutive du champ
	public function MarqueSubstitutive($Valeur = null)
	{
		if ($Valeur !== null)
		{
			if (!is_string($Valeur) && ($Valeur !== false)) return false;
			$this->m_MarqueSubstitutive = $Valeur;
		}
		return $this->m_MarqueSubstitutive;
	}
	
	// Accesseur/modificateur de la marque substitutive du champ
	public function EstRequis($Valeur = null)
	{
		if ($Valeur !== null)
		{
			if (!is_bool($Valeur)) return false;
			$this->m_EstRequis = $Valeur;
		}
		return $this->m_EstRequis;
	}

	// Constructeur
	public function __construct($Nom, $Etiquette = null, $MarqueSubstitutive = null, $EstRequis = null)
	{
		parent::__construct($Nom, $Etiquette);
		$this->m_MarqueSubstitutive = false;
		$this->m_EstRequis = false;
		$this->m_Valeur = "";
		$this->MarqueSubstitutive($MarqueSubstitutive);
		$this->EstRequis($EstRequis);
	}
	
	// Implémentation de la méthode de sérialisation pour la communication de cet objet en Ajax
	public function Proprietes()
	{
		return array_merge(get_object_vars($this), parent::Proprietes());
	}
};
?>