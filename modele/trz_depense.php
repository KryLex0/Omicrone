<?php

class trz_depense {

    private $montant_depense;
    private $nom_depense;

    public function __construct($unMontant,$unLibelle)
    {

        $this->montant_depense = $unMontant;
        $this->nom_depense=$unLibelle;

    }
        public function getMontant(){return($this->montant_depense);}
        public function getLibelle(){return($this->nom_depense);}



    public function afficherDepense(){
        print($this->montant_depense." ".$this->nom_depense);
    }

}
