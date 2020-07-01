<?php

class CVehicule implements IExpositionProprietes
{
    private $m_Id;
    private $m_Modele;
    private $m_PrixJournee;
    private $m_PrixDemiJournee;
    private $m_NomImage;
    
    // Accesseur/modificateur de l'id de l'utilisateur
    public function Identifiant($Valeur = null)
	{
		if ($Valeur !== null)
		{
			if (!is_string($Valeur)) return false;
			$this->m_Id = $Valeur;
		}
		return $this->m_Id;
    }
    
    // Accesseur/modificateur de nom du modèle
    public function Modele($Valeur = null)
    {
        if ($Valeur !== null)
        {
            if (!is_string($Valeur)) return false;
            $this->m_Modele = $Valeur;
        }
        return $this->m_Modele;
    }

    // Accesseur/modificateur du prix d'une journée
    public function PrixJournee($Valeur = null)
    {
        if ($Valeur !== null)
        {
            if (!is_int($Valeur)) return false;
            $this->m_PrixJournee = $Valeur;
        }
        return $this->m_PrixJournee;
    }

    // Accesseur/modificateur du prix d'une demi journée
    public function PrixDemiJournee($Valeur = null)
    {
        if ($Valeur !== null)
        {
            if (!is_int($Valeur)) return false;
            $this->m_PrixDemiJournee = $Valeur;
        }
        return $this->m_PrixDemiJournee;
    }

    // Accesseur/modificateur du nom
    public function Image($Valeur = null)
    {
        if ($Valeur !== null)
        {
            if (!is_string($Valeur)) return false;
            $this->m_NomImage = $Valeur;
        }
        return $this->m_NomImage;
    }


    // Constructeur
    public function __construct($Modele,$PrixJournee,$PrixDemiJournee,$Image,$IdVehicule = null)
    {
        if($IdVehicule !== null)
        {
            $this->Identifiant($IdVehicule);
        }
        $this->Modele($Modele);
        $this->PrixJournee($PrixJournee);
        $this->PrixDemiJournee($PrixDemiJournee);
        if($Image == "")
        {
            $this->Image("Images/bmw_i8.png");
        }
        else
        {
            $this->Image($Image);
        }
        
    }

    public function AjouterVehicule()
    {
        return $NouvelIdentifiant = CApplication::Instance()->BD()->Executer("INSERT INTO vehicule
                                        SET modele = :modele,
                                            prix_journee = :prixjournee,
                                            prix_demi_journee = :prixdemijournee,
                                            nom_image = :nom_image",
                                        array("modele" => $this->Modele(),
                                                "prixjournee" => $this->PrixJournee(),
                                                "prixdemijournee" => $this->PrixDemiJournee(),
                                                "nom_image" => $this->Image()));
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