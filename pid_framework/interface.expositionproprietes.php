<?php
interface IExpositionProprietes
{
	public function Proprietes();
};

/*
class CContenuSession implements IExpositionProprietes
{
	private $m_Session;
	
	public function __construct()
	{
		if (!isset($_SESSION)) session_start();
		$this->m_Session = $_SESSION;
	}
	
	public function Proprietes()
	{
		return get_object_vars($this);
	}
};

class CContenuAvance extends CContenuSession
{
	private $m_Bidule;
	
	public function __construct()
	{
		parent::__construct();
		$this->m_Bidule = "truc";
	}
	
	public function Proprietes()
	{
		return array_merge(get_object_vars($this), parent::Proprietes());
	}
};
*/
?>