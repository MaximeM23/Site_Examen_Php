<?php
abstract class CDocument implements IExpositionProprietes
{
	protected abstract function GenererContenu();

	private $m_Titre;
	private $m_PiedPage;
	private $m_FichiersCss;
	private $m_FichiersJS;
	
	public function Titre($Valeur = null)
	{
		if ($Valeur !== null)
		{	// set
			if (!is_string($Valeur)) return false;
			$this->m_Titre = trim($Valeur);
			return true;
		}
		else
		{	// get
			return $this->m_Titre;
		}
	}
	
	public function PiedPage($Valeur = null)
	{
		if ($Valeur !== null)
		{	// set
			if (!is_string($Valeur)) return false;
			$this->m_PiedPage = trim($Valeur);
			return true;
		}
		else
		{	// get
			return $this->m_PiedPage;
		}
	}
	
	public function AjouterFichierCss($NomFichier)
	{
		if (!is_string($NomFichier) || !file_exists($NomFichier) || in_array($NomFichier, $this->m_FichiersCss)) return false;
		$this->m_FichiersCss[] = $NomFichier;
		return true;
	}
	
	public function SupprimerFichierCss($NomFichier)
	{
		if (!is_string($NomFichier) || (($Indice = array_search($NomFichier, $this->m_FichiersCss)) === false)) return false;
		unset($this->m_FichiersCss[$Indice]);
		return true;
	}
	
	public function FichiersCss()
	{
		return $this->m_FichiersCss;
	}
	
	public function AjouterFichierJS($NomFichier)
	{
		if (!is_string($NomFichier) || !file_exists($NomFichier) || in_array($NomFichier, $this->m_FichiersJS)) return false;
		$this->m_FichiersJS[] = $NomFichier;
		return true;
	}
	
	public function InsererFichierJS($NomFichier)
	{
		if (!is_string($NomFichier) || !file_exists($NomFichier) || in_array($NomFichier, $this->m_FichiersJS)) return false;
		array_unshift($this->m_FichiersJS, $NomFichier);
		return true;
	}
	
	public function SupprimerFichierJS($NomFichier)
	{
		if (!is_string($NomFichier) || (($Indice = array_search($NomFichier, $this->m_FichiersJS)) === false)) return false;
		unset($this->m_FichiersJS[$Indice]);
		return true;
	}
	
	public function FichiersJS()
	{
		return $this->m_FichiersJS;
	}
	
	public function __construct()
	{
		$this->m_Titre = "";
		$this->m_PiedPage = "";
		$this->m_FichiersCss = array();
		$this->m_FichiersJS = array();
	}
	
	public function __sleep()
	{
		return CApplication::CollecterNomsProprietes($this);
	}
	
	function CreerPage()
	{
		CApplication::Instance()->DeclarerJeuCaracteres();
		$JeuCaractere = CApplication::Instance()->JeuCaracteres();
		print("<!doctype html>\n\n<html>\n\n\t<head>\n\t\t<meta charset=\"$JeuCaractere\"/>\n\t\t<meta http-equiv=\"content-type:text/html; charset=$JeuCaractere\"/>\n\t\t<title>"
			  . CApplication::Instance()->FormaterEnHtml($this->m_Titre)
			  . "</title>\n");
		foreach ($this->m_FichiersCss as $FichierCss)
		{
			print("\t\t<link rel=\"stylesheet\" type=\"text/css\" href=\"$FichierCss\"/>\n");
		}
		foreach (array_merge(CApplication::Instance()->FichiersJS(), $this->m_FichiersJS) as $FichierJS)
		{
			print("\t\t<script type=\"text/javascript\" src=\"$FichierJS\"></script>\n");
		}
		print("\t</head>\n\n\t<body>\n");
		if ($this->m_Titre != "")
		{
			print("\t\t<header>\n\t\t\t<h1>"
				  . CApplication::Instance()->FormaterEnHtml($this->m_Titre)
				  . "</h1>\n\t\t</header>\n");
		}
		print("\t\t<main>\n");
		$this->GenererContenu();
		print("\t\t</main>\n");
		if (is_string($this->m_PiedPage))
		{
			print("\t\t<footer>\n\t\t\t"
				  . CApplication::Instance()->FormaterEnHtml($this->m_PiedPage)
				  . "\n\t\t</footer>\n");
		}
		print("\t</body>\n\t\n</html>\n");
		/*
		var_dump(get_class($this));
		var_dump(get_class_methods(get_class($this)));
		var_dump(method_exists($this, "GenererContenu"));
		var_dump(method_exists($this, "ActionDeTest"));
		*/
		return true;
	}
	
	public function Proprietes()
	{
		return get_object_vars($this);
	}
};
?>