<?php
// Permet de décrire tout champ de formulaire
class CChampEnvoi extends CChamp
{
	private $m_Texte;
	private $m_ClassName;
	
	// Accesseur/modificateur du texte de ce bouton
	public function Texte($Valeur = null)
	{
		if ($Valeur !== null)
		{
			if (!is_string($Valeur) && ($Valeur !== false)) return false;
			$this->m_Texte = $Valeur;
		}
		return $this->m_Texte;
	}

	// Accesseur/modificateur du nom de classe du champ
	public function ClassName($Valeur = null)
	{
		if ($Valeur != null)
		{
			if (!is_string($Valeur) && !is_numeric($Valeur)) return false;
			$this->m_ClassName = $Valeur;
		}
		return $this->m_ClassName;
	}
	
	// Méthode permettant de générer le code HTML de ce champ
	public function Afficher()
	{
		if ($this->Formulaire()->UtilisePost())
		{
			
			$this->AfficherDebutHtml();
			if ($this->ClassName() !== false)
			{
				print("<input type=\"submit\" id=\""
				. CApplication::Instance()->FormaterEnHtml($this->Identifiant())
				. "\" class=\"" 
				. CApplication::Instance()->FormaterEnHtml($this->ClassName())			
				. "\" name=\""				
				. CApplication::Instance()->FormaterEnHtml($this->Nom())
				. "\"");
			}
			else
			{
				print("<input type=\"submit\" id=\""
				. CApplication::Instance()->FormaterEnHtml($this->Identifiant())								
				. "\" name=\""
				. CApplication::Instance()->FormaterEnHtml($this->Nom())
				. "\"");
			}
		}
		else if ($this->Formulaire()->UtiliseAjax())
		{
			$this->AfficherDebutHtml();	
			if ($this->ClassName() !== false)
			{		
				print("<input type=\"button\" id=\""
				. CApplication::Instance()->FormaterEnHtml($this->Identifiant())								
				. "\" class=\"" 
				. CApplication::Instance()->FormaterEnHtml($this->ClassName())		
				. "\" name=\""
				. CApplication::Instance()->FormaterEnHtml($this->Nom())
				. "\" onclick=\"CApplication_Instance().PosterFormulaire(this);\"");
			}
			else
			{
				print("<input type=\"button\" id=\""
				. CApplication::Instance()->FormaterEnHtml($this->Identifiant())															
				. "\" name=\""
				. CApplication::Instance()->FormaterEnHtml($this->Nom())
				. "\" onclick=\"CApplication_Instance().PosterFormulaire(this);\"");
			}
		}
		else
		{
			return false;
		}
		if ($this->m_Texte !== false)
		{
			print(" value=\"" . CApplication::Instance()->FormaterEnHtml($this->m_Texte) . "\"");
		}
		print("/>");
		$this->AfficherFinHtml();
		return true;
	}
	
	// Méthode permettant de tester la validité de ce champ
	public function TesterValidite()
	{
		return true;
	}

	// Méthode permettant de définir la "valeur courante" du champ en fonction de données postées ou prédéfinies
	public function DefinirValeur($Valeur)
	{
		return true;
	}

	// Constructeur
	public function __construct($Nom, $Etiquette = null, $Texte = null, $ClassName = null)
	{
		parent::__construct($Nom, $Etiquette);
		$this->m_Texte = false;
		$this->m_ClassName = false;
		$this->Texte($Texte);
		$this->ClassName($ClassName);
	}
	
	// Implémentation de la méthode de sérialisation pour la communication de cet objet en Ajax
	public function Proprietes()
	{
		return array_merge(get_object_vars($this), parent::Proprietes());
	}
};
?>