<?php

class trz_cra {

    private $totalJfacturable_cra;
    private $totalJmaladie_cra;
    private $totalJconge_cra;
    private $astreinte_cra;
    private $contrat;
    private $periode_cra;
    private $intervention_cra;


    public function __construct($untotalJF,$untotalJM,$untotalJC,$uneastreinte,$unContrat,$unperiode_cra, $uneInterv)
    {
        $this->totalJfacturable_cra=$untotalJF;
        $this->totalJmaladie_cra=$untotalJM;
        $this->totalJconge_cra=$untotalJC;
        $this->astreinte_cra=$uneastreinte;
        $this->contrat=$unContrat;
        $this->periode_cra=$unperiode_cra;
        $this->intervention_cra=$uneInterv;
    }

    public function getJF(){return $this->totalJfacturable_cra;}
    public function getJM(){return $this->totalJmaladie_cra;}
    public function getJC(){return $this->totalJconge_cra;}
    public function getAstreinte(){return $this->astreinte_cra;}
    public function getOContrat(){return $this->contrat;}
    public function getPeriode(){return $this->periode_cra;}
    public function getInterv(){return $this->intervention_cra;}

    public function getTotal(){return $this->totalJfacturable_cra+$this->totalJmaladie_cra+$this->totalJconge_cra;}


}

?>
