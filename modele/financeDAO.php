<?php

class financeDAO{

public function __construct()
{
    $this->pdo = PdoCommission::getInstance();
}

    public function add($finance,$comm){

        $req="INSERT INTO trz_infob (id,idclient,id,codeagence,compte,iban,bic,codebanque,clerib)
        VALUES (nextval('finance_idfinance_seq'::regclass),NULL,'".$comm->getIdCommercial($finance->getOCommercial())."','".$finance->getCodeAgence()."',
        '".$finance->getCompte()."','".$finance->getIban()."','".$finance->getBic()."',
        '".$finance->getCodeBanque()."','".$finance->getCleRib()."')";
        $this->pdo->exec($req);
    }


    public function getFinances()/* retourne une collection de finance*/{
        $lesFinance=array();
        $lesLignes =r::getAll("select nom_user, prenom_user, tel_user, mail_user, adresse_user, ville_user, cp_user, codeagence ,compte ,iban ,bic, codebanque,clerib from trz_omicrone_user join trz_commercial on trz_omicrone_user.id=trz_commercial.idutilisateur left join trz_infob on trz_commercial.idutilisateur=trz_infob.idcommercial where trz_commercial.cacher = false");

        for($i=0;$i<=count($lesLignes)-1;$i++){
            // a finir quand on créra la table client, ajouter l'objet client a la collection quand la classe sera créée
           $comm=new trz_commercial($lesLignes[$i]["nom_user"],$lesLignes[$i]["prenom_user"],$lesLignes[$i]["adresse_user"],$lesLignes[$i]["ville_user"],$lesLignes[$i]["cp_user"],$lesLignes[$i]["tel_user"],
           $lesLignes[$i]["mail_user"]);

            $fin=new information_bancaire(NULL,$comm,$lesLignes[$i]["codeagence"],$lesLignes[$i]["compte"],$lesLignes[$i]["iban"],
                            $lesLignes[$i]["bic"],$lesLignes[$i]["codebanque"],$lesLignes[$i]["clerib"]);
            $lesFinance[]=$fin;
                                             }
        return($lesFinance);
        }

        public function getIdFinanceByObject($finance){
            $req=("SELECT id from trz_infob
            where codeagence='".$finance->getCodeAgence()."'
            and compte='".$finance->getCompte()."'
            and iban='".$finance->getIban()."'
            and bic='".$finance->getBic()."'
            and codebanque='".$finance->getCodeBanque()."'
            and clerib='".$finance->getCleRib()."'");
            $rs=$this->pdo->query($req);
            $laLigne = $rs->fetch();
            return($laLigne["id"]);

            }

        public function getFinance($idF)/* retourne l'objet finance par rapport a l'id*/{
            $req ="select nom_user, prenom_user, tel_user, mail_user, adresse_user, ville_user, cp_user, codeagence ,compte ,iban ,bic, codebanque,clerib from trz_omicrone_user join trz_commercial on trz_omicrone_user.id=trz_commercial.idutilisateur left join trz_infob on trz_commercial.idutilisateur=trz_infob.idcommercial where trz_commercial.cacher = false and trz_infob.id='".$idF."'";
            $rs=$this->pdo->query($req);
            $lesLignes = $rs->fetch(PDO::FETCH_ASSOC);
            $comm=new trz_commercial($lesLignes["nom"],$lesLignes["prenom"],$lesLignes["tel"],
            $lesLignes["email"],$lesLignes["adresse"],$lesLignes["ville"],$lesLignes["cp"]);
            $finance=new information_bancaire(NULL,$comm,$lesLignes["codeagence"],$lesLignes["compte"],$lesLignes["iban"],
            $lesLignes["bic"],$lesLignes["codebanque"],$lesLignes["clerib"]);
            return($finance);

        }

        public function getIdFinanceById($idCommercial)/* recupere l'id de la finance avec l'id du commercial*/{
            $req="select trz_infob.id as id from trz_infob, trz_commercial, trz_omicrone_user where trz_omicrone_user.id=trz_commercial.idutilisateur and trz_commercial.idutilisateur=trz_infob.idcommercial and trz_commercial.idutilisateur=".$idCommercial."";
            $rs=$this->pdo->query($req);
            $laLigne = $rs->fetch(PDO::FETCH_ASSOC);
            return($laLigne["id"]);
        }

    public function update($finance,$idF,$idC){
        $req="UPDATE trz_infob SET idclient=NULL, id='".$idC."', codeagence='".$finance->getCodeAgence()."', compte='".$finance->getCompte()."',
             iban='".$finance->getIban()."', bic='".$finance->getBic()."', codebanque='".$finance->getCodebanque()."', clerib='".$finance->getCleRib()."' WHERE id='".$idF."'";
        $this->pdo->exec($req);
    }
    public function delete($finance){
        $req="delete from trz_infob where id='".$this->getIdFinanceByObject($finance)."'";
        $this->pdo->exec($req);
        }
    public function addfinance($finance, $clientDao){
        $idclient = $clientDao->getidclientfromchamps($finance->getOClient());
        $ca = $finance->getCodeAgence();
        $compte = $finance->getCompte();
        $iban = $finance->getIban();
        $bic = $finance->getBic();
        $cb = $finance->getCodeBanque();
        $clerib = $finance->getCleRib();

        $finance = r::dispense('trz_infob');
        $finance->idclient = $idclient;
        $finance->codeagence = $ca;
        $finance->compte = $compte;
        $finance->iban = $iban;
        $finance->bic = $bic;
        $finance->codebanque = $cb;
        $finance->clerib = $clerib;

        r::store($finance);
    }
}
