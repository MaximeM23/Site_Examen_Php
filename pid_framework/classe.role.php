<?php

class CRole implements IExpositionProprietes
{
    private $m_Id;
    private $m_Role;
    
    // Accesseur/modificateur de l'id de role
    public function Identifiant($Valeur)
	{
		if ($Valeur !== null)
		{
			if (!is_string($Valeur)) return false;
			$this->m_Id = $Valeur;
		}
		return $this->m_Id;
    }
    
    // Accesseur/modificateur du role
    public function Role($Valeur = null)
    {
        if ($Valeur !== null)
        {
            if (!is_string($Valeur) && ($Valeur !== false)) return false;
            $this->m_AdresseEmail = $Valeur;
        }
        return $this->m_AdresseEmail;
    }

    // Constructeur
    public function __construct($Id, $Role)
    {
        $this->m_Id = $Id;
        $this->m_Role = $Role;
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
		
	}
	
	
	// Implémentation de la méthode de sérialisation pour la communication de cet objet en Ajax
	public function Proprietes()
	{
		return get_object_vars($this);
	}


}

?>