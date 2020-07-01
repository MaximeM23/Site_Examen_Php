<?php
// Permet de d�crire un champ d'encodage de texte dans un formulaire
class CChampTexte extends CChampEncodage
{
	private static $c_SousTypes = array("textarea", "text", "password", "email", "tel", "image", "datetime-local", "number", "file");
	private $m_SousType;
	private $m_LongueurMinimale;
	private $m_LongueurMaximale;
	private $m_ClassName;
	private $m_Disable;
	
	// Accesseur de sous-type du champ
	public function SousType()
	{
		return $this->m_SousType;
	}
	
	// Accesseur/modificateur de la longueur minimale du champ
	public function LongueurMinimale($Valeur = null)
	{
		if ($Valeur !== null)
		{
			if (!is_numeric($Valeur)) return false;
			if (is_string($Valeur)) $Valeur = (int)$Valeur;
			if (($Valeur < 0) || (($this->m_LongueurMaximale !== false) && ($Valeur > $this->m_LongueurMaximale))) return false;
			$this->EstRequis(($Valeur > 0));
			$this->m_LongueurMinimale = $Valeur;
		}
		return $this->m_LongueurMinimale;
	}
	
	// Accesseur/modificateur de la longueur maximale du champ
	public function LongueurMaximale($Valeur = null)
	{
		if ($Valeur !== null)
		{
			if (!is_numeric($Valeur)) return false;
			if (is_string($Valeur)) $Valeur = (int)$Valeur;
			if (($Valeur < 0) || (($this->m_LongueurMinimale !== false) && ($Valeur < $this->m_LongueurMinimale))) return false;
			$this->m_LongueurMaximale = $Valeur;
		}
		return $this->m_LongueurMaximale;
	}
	
	// Accesseur/modificateur du nom de classe du champ
	public function ClassName($Valeur = null)
	{
		if ($Valeur != null)
		{
			if (!is_string($Valeur)) return false;
			$this->m_ClassName = $Valeur;
		}
		return $this->m_ClassName;
	}

	public function Disable($Valeur = null)
	{
		if ($Valeur != null)
		{
			if (!is_bool($Valeur)) return false;
			$this->m_Disable = $Valeur;
		}
		return $this->m_Disable;
	}
	
	
	// M�thode permettant de g�n�rer le code HTML de ce champ
	public function Afficher()
	{
		$this->AfficherDebutHtml();
		if ($this->m_SousType != "textarea")
		{
			print("<input type=\""
				. $this->m_SousType
				. "\" id=\""
				. CApplication::Instance()->FormaterEnHtml($this->Identifiant())
				. "\" name=\""
				. CApplication::Instance()->FormaterEnHtml($this->Nom())
				. "\"");
		}
		else
		{
			print("<textarea id=\""
				. CApplication::Instance()->FormaterEnHtml($this->Identifiant())
				. "\" name=\""
				. CApplication::Instance()->FormaterEnHtml($this->Nom())
				. "\"");
		}
		if ($this->ClassName() !== false)
		{
			print(" class=\"" . CApplication::Instance()->FormaterEnHtml($this->ClassName()) . "\"");
		}
		if ($this->m_LongueurMaximale !== false)
		{
			print(" maxlength=\"" . $this->m_LongueurMaximale . "\"");
		}
		if ($this->EstRequis())
		{
			print(" required");
		}
		if ($this->MarqueSubstitutive() !== false)
		{
			print(" placeholder=\"" . CApplication::Instance()->FormaterEnHtml($this->MarqueSubstitutive()) . "\"");
		}
		if (($this->m_SousType != "textarea") && ($this->Valeur() != ""))
		{
			print(" value=\"" . CApplication::Instance()->FormaterEnHtml($this->Valeur()) . "\"");
		}
		if ($this->Disable() === true)
		{
			print(" disabled");
		}
		// ICI : autres attributs si n�cessaire

		if ($this->m_SousType != "textarea")
		{
			print("/>");
		}
		else
		{
			print(">");
			if ($this->Valeur() != "")
			{
				print(CApplication::Instance()->FormaterEnHtml(str_replace(array("\n", "<br>", "<BR>", "<br/>", "<BR/>"), "\r\n", str_replace(array("\r\n", "\r"), "\n", $this->Valeur()))));
			}
			print("</textarea>");
		}
		$this->AfficherFinHtml();
		return true;
	}
	
	// M�thode permettant de tester la validit� de ce champ
	public function TesterValidite()
	{
		$ValeurATester = $this->Valeur();
		$LongueurChaine = strlen($ValeurATester);
		if (($this->m_LongueurMinimale !== false) && ($LongueurChaine < $this->m_LongueurMinimale))
		{
			if (!isset($IntituleChamp)) $IntituleChamp = ($this->Etiquette() !== false) ? trim(str_replace(":", "", $this->Etiquette())) : $this->Nom();
			$this->MessageErreur("Le texte de \"$IntituleChamp\" doit contenir au minimum " . $this->m_LongueurMinimale . " caractère" . (($this->m_LongueurMinimale >= 2) ? "s" : "") . " !");
			return false;
		}
		if (($this->m_LongueurMaximale !== false) && ($LongueurChaine > $this->m_LongueurMaximale))
		{
			if (!isset($IntituleChamp)) $IntituleChamp = ($this->Etiquette() !== false) ? trim(str_replace(":", "", $this->Etiquette())) : $this->Nom();
			$this->MessageErreur("Le texte de \"$IntituleChamp\" doit contenir au maximum " . $this->m_LongueurMaximale . " caractère" . (($this->m_LongueurMaximale >= 2) ? "s" : "") . " !");
			return false;
		}
		return true;
	}

	// Constructeur
	public function __construct($SousType, $Nom, $ClassName = null, $Etiquette = null, $MarqueSubstitutive = null, $disable = null, $LongueurMinimale = null, $LongueurMaximale = null)
	{
		if (!is_string($SousType)) throw new Exception("Sous-type de champ texte indéfini !");
		$SousType = strtolower($SousType);
		if(!in_array($SousType, CChampTexte::$c_SousTypes)) throw new Exception("Sous-type de champ texte inconnu !");
		parent::__construct($Nom, $Etiquette, $MarqueSubstitutive, false);
		$this->m_Disable = $disable;
		$this->m_SousType = $SousType;
		$this->m_ClassName = $ClassName;
		$this->m_LongueurMinimale = false;
		$this->m_LongueurMaximale = false;
		$this->LongueurMinimale($LongueurMinimale);
		$this->LongueurMaximale($LongueurMaximale);
	}
	
	// Impl�mentation de la m�thode de s�rialisation pour la communication de cet objet en Ajax
	public function Proprietes()
	{
		return array_merge(get_object_vars($this), parent::Proprietes());
	}
};
?>