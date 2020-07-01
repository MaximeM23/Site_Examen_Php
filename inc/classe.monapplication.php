<?php
class CMonApplication extends CApplication
{
	private $m_ConnexionDB;
	//private $m_Coucou;
	
	public function __construct()
	{
		parent::__construct();
		$this->DefinirBD(new CMaBD());
		$this->m_ConnexionDB = "hello";
	}
	/*
	public function Action_Saluer()
	{
		return $this->Action_RepondreReussite(array("Coucou"=>$this->m_Coucou));
	}
	*/
	public function Action_TraiterFormProfil($Donnees)
	{
		if (isset($_SESSION["form_profil"]))
		{
			$_SESSION["form_profil"]->DefinirValeurs($Donnees);
			if ($_SESSION["form_profil"]->TesterValidite())
			{
				return $this->Action_RepondreReussite($Donnees);
			}
			else
			{
				return $this->Action_RepondreEchec($_SESSION["form_profil"]->MessagesErreurs());
			}
		}
		else
		{
			return $this->Action_RepondreEchec("Formulaire non défini !");
		}
	}

	public function Action_TraiterFormAjoutVehicule($Donnees)
	{
		if (isset($_SESSION["form_AjoutVehicule"]))
		{
			$_SESSION["form_AjoutVehicule"]->DefinirValeurs($Donnees);
			if ($_SESSION["form_AjoutVehicule"]->TesterValidite())
			{
				return $this->Action_RepondreReussite($Donnees);
			}
			else
			{
				return $this->Action_RepondreEchec($_SESSION["form_AjoutVehicule"]->MessagesErreurs());
			}
		}
		else
		{
			return $this->Action_RepondreEchec("Formulaire non défini !");
		}
	}


	public function Proprietes()
	{
		return array_merge(get_object_vars($this), parent::Proprietes());
	}
};
?>