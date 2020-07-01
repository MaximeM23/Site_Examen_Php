<?php

class CUtilisateur implements IExpositionProprietes
{
    private $m_Id;
    private $m_AdresseEmail;
    private $m_MotDePasse;
    private $m_Nom;
    private $m_Prenom;
    private $m_RefRole;
    
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
    
    // Accesseur/modificateur de l'adresse email
    public function AdresseEmail($Valeur = null)
    {
        if ($Valeur !== null)
        {
            if (!is_string($Valeur)) return false;
            $this->m_AdresseEmail = $Valeur;
        }
        return $this->m_AdresseEmail;
    }

    // Accesseur/modificateur du mot de passe
    public function MotDePasse($Valeur = null)
    {
        if ($Valeur !== null)
        {
            if (!is_string($Valeur)) return false;
            $this->m_MotDePasse = $Valeur;
        }
        return $this->m_MotDePasse;
    }

    // Accesseur/modificateur du nom
    public function Nom($Valeur = null)
    {
        if ($Valeur !== null)
        {
            if (!is_string($Valeur)) return false;
            $this->m_Nom = $Valeur;
        }
        return $this->m_Nom;
    }

    // Accesseur/modificateur du nom
    public function Prenom($Valeur = null)
    {
        if ($Valeur !== null)
        {
            if (!is_string($Valeur)) return false;
            $this->m_Prenom = $Valeur;
        }
        return $this->m_Prenom;
    }

    // Accesseur/Modificateur du type d'utilisateur
	public function Role($Valeur = null)
	{
		if ($Valeur !== null)
		{
			if (!is_string($Valeur)) return false;
			$this->m_RefRole = $Valeur;
		}
		return $this->m_RefRole;
	}

    // Constructeur
    public function __construct($Nom,$Prenom,$Email,$MotdePasse,$Role, $IdUtilisateur = null)
    {
        if($IdUtilisateur !== null)
        {
            $this->Identifiant($IdUtilisateur);
        }
        $this->AdresseEmail($Email);
        $this->Nom($Nom);
        $this->Prenom($Prenom);
        $this->MotDePasse($MotdePasse);
        $this->Role($Role);
    }

    public function Inscription()
    {
        return $NouvelIdentifiant = CApplication::Instance()->BD()->Executer("INSERT INTO utilisateur
                                        SET nom = :nom,
                                            prenom = :prenom,
                                            email = :email,
                                            mot_de_passe = SHA2(CONCAT(SHA2(:email, 512), SHA2(:motdepasse, 512)), 512),
                                            fk_id_role = :idrole",
                                        array("nom" => $this->Nom(),
                                                "prenom" => $this->Prenom(),
                                                "email" => $this->AdresseEmail(),
                                                "motdepasse" => $this->MotdePasse(),
                                                "idrole" => $this->Role()));        
    }

    public function ModificationProfil($ModifPassword = false)
    {
        if($ModifPassword === true)
        {
            return $NouvelIdentifiant = CApplication::Instance()->BD()->Executer("UPDATE utilisateur
                                        SET nom = :nom,
                                            prenom = :prenom,
                                            mot_de_passe = SHA2(CONCAT(SHA2(:email, 512), SHA2(:motdepasse, 512)), 512),
                                            fk_id_role = :idrole
                                            WHERE id_utilisateur = :id",
                                        array("nom" => $this->Nom(),
                                                "prenom" => $this->Prenom(),
                                                "email" => $this->AdresseEmail(),
                                                "motdepasse" => $this->MotdePasse(),
                                                "idrole" => $this->Role(),
                                                "id" => $this->Identifiant()));     
        }
        else if ($ModifPassword === false)
        {
            return $NouvelIdentifiant = CApplication::Instance()->BD()->Executer("UPDATE utilisateur
            SET nom = :nom,
                prenom = :prenom,                        
                fk_id_role = :idrole
                WHERE id_utilisateur = :id",
            array("nom" => $this->Nom(),
                    "prenom" => $this->Prenom(),
                    "idrole" => $this->Role(),
                    "id" => $this->Identifiant()));     
        }
    }

    public function VerifierExistanceEmail($IdExclusion = null)
    {
        $UtilisateurExistant = null;
        $_SESSION["EchecConnexionDB"] = false;
        if(!CApplication::Instance()->BD()->SeConnecter())
        {
            $_SESSION["EchecConnexionDB"] = true;
            return false;
        }
        
        if($IdExclusion == null)
        {
            $UtilisateurExistant = CApplication::Instance()->BD()->RecupererEnregistrement("SELECT email FROM utilisateur WHERE email = :email",array("email" => $this->AdresseEmail()));
        }
        else
        {
            $UtilisateurExistant = CApplication::Instance()->BD()->RecupererEnregistrement("SELECT email FROM utilisateur WHERE email = :email AND id_utilisateur != :id",array("email" => $this->AdresseEmail(),
                                                                                                                                                                                 "id" => $IdExclusion));        
        }
        if(!empty($UtilisateurExistant))
        {
            // return true pour dire qu'il existe
            return true;
        }
        else
        {
            // return false pour dire qu'il n'existe pas
            return false;
        }
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