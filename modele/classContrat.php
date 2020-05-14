<?php

class contrat {
   private $_idContrat;
   private $_client;
   private $_datedebut;
   private $_datefin;
   private $_mission;
   private $_consultant;

   public function __construct($unIdContrat ,$unClient, $unConsultant, $uneDateDebut, $uneDateFin, $unemission){
       $this->_idContrat = $unIdContrat;
       $this->_client = $unClient;
       $this->_consultant = $unConsultant;
       $this->_mission = $unemission;
       $this->_datedebut = $uneDateDebut;
       $this->_datefin = $uneDateFin;

   }

   public function getidContrat(){
       return $this->_idContrat;
   }

   public function getdatedebut(){
       return $this->_datedebut;
   }

   public function getdatefin(){
       return $this->_datefin;
   }

   public function getmission(){
       return $this->_mission;
   }
   public function getcleclient(){
        return $this->_client;
   }
   public function getcleconsultant(){
       return $this->_consultant;
   }

}

class daoContrat{

    public function __construct() {
        $this->pdo = PdoCommission::getInstance();
    }

    public function getlistecontrat(){ //liste de contrat
        $ligne= r::getAll("select contrat.id, datedebut, datefin,  mission, raisonsocial, utilisateur.nom from client join contrat on client.id=contrat.idclient join utilisateur on contrat.idutilisateur=utilisateur.id where contrat.cacher = false order by id DESC");
        //print_r($ligne);
        //preg_replace("-","/",$ligne['datedebut']);
        //$ligne[1] = date_format(date_create_from_format('Y-m-d', $ligne[1]),'d/m/Y');

        //$timestamp = strtotime($date_from_db);
        for($i=0; $i<=count($ligne)-1; $i++){
          $tmp = date('d/m/Y', strtotime($ligne[$i]['datedebut']));
          $ligne[$i]['datedebut'] = $tmp;
          $tmp = date('d/m/Y', strtotime($ligne[$i]['datefin']));
          $ligne[$i]['datefin'] = $tmp;
        }
        return $ligne;
    }

    public function collectioncontrat(){
        $collectionC= array();
        $lesContrats= r::getAll('select consultant.idutilisateur as idconsultant, utilisateur.nom as nom, utilisateur.prenom as prenom, utilisateur.adresse as adrcons, utilisateur.ville as villecons, utilisateur.cp as cp, utilisateur.tel as tel, utilisateur.email as email, typecontrat.libelle as libelle, consultant.salaire as salaire, consultant.tarif as tarif, contrat.id as idcontrat, datedebut, datefin, salaire, tarif, mission, client.id as idclient, idcontact, raisonsocial, siret, client.adr as clientadr, client.ville as clientville, codepostal from utilisateur join consultant on utilisateur.id=consultant.idutilisateur join contrat on consultant.idutilisateur=contrat.idutilisateur join client on client.id=contrat.idclient join typecontrat on typecontrat.idtype=consultant.idtypecontrat');

        //$untypecontrat = R::getAll('select typecontrat.idtypecontrat as id, typecontrat.libelle as libelle from typecontrat join contrat on typecontrat.idtypecontrat=contrat.id');

        for ($i=0; $i<=count($lesContrats)-1; $i++) {

                $objclient = new client ($lesContrats[$i]['raisonsocial'],$lesContrats[$i]['idcontact'],$lesContrats[$i]['siret'], $lesContrats[$i]['clientadr'], $lesContrats[$i]['clientville'], $lesContrats[$i]['codepostal']);
                $objconsultant = new consultant ($lesContrats[$i]['nom'], $lesContrats[$i]['prenom'], $lesContrats[$i]['adrcons'], $lesContrats[$i]['villecons'], $lesContrats[$i]['cp'], $lesContrats[$i]['tel'], $lesContrats[$i]['email'], $lesContrats[$i]['libelle'], $lesContrats[$i]['salaire'], $lesContrats[$i]['tarif']);
                $objcontrat = new contrat($lesContrats[$i]['idcontrat'], $objclient, $objconsultant, $lesContrats[$i]['datedebut'],$lesContrats[$i]['datefin'], $lesContrats[$i]['mission'],$lesContrats[$i]['salaire'], $lesContrats[$i]['tarif'], $lesContrats[$i]['libelle']);
                $collectionC[] = $objcontrat;
        }
    return $collectionC;
    //var_dump($collectionC);
    }

    public function getobjcontrat($idcontrat){ //retourne un objet contrat en fonction de son id
        $uncontrat = R::load('contrat', $idcontrat);  //équivalent à "select * from contrat where idcontrat ='$idcontrat'"                            recuperation d'un contrat en fonction de son ID
        $idclient = $uncontrat->idclient; //recupère l'id du client dans une variable                                                                 retourne l'id du client rataché a ce contrat
        $client = R::load('client', $idclient); // select * from client en fonction de l'idclient récupérer précedemment                              informations concernant le client rattaché a ce contrat en fonction de l'idclient
        $idcontact = $client->idcontact;                                                                                                            //idcontact du client rattaché a ce contrat
        $contact = r::load('contact', $idcontact);                                                                                                  //recupere le contact en fonction de l'id du contact(client)
        $uncontact = new contact ($contact->email1, $contact->email2, $contact->email3, $contact->bureau, $contact->fax, $contact->tel3);           //creation d'un contact avec les infos du contact recupere juste au dessus
        $unclient =  new client($client->raisonsocial, $uncontact, $client->siret, $client->adr, $client->ville, $client->codepostal);              //creation d'un client avec infos $client
        $idconsultant = $uncontrat->idutilisateur;                                                                                             //recuperation de l'idutilisateur de la table contrat
        //var_dump($idconsultant);
        //$consultant = R::getAll('select * from utilisateur join consultant on utilisateur.id=consultant.idutilisateur where consultant.idutilisateur')
        $consultant = R::getAll("select * from utilisateur join consultant on utilisateur.id=consultant.idutilisateur where consultant.idutilisateur=$idconsultant");
        //var_dump($consultant);
        //var_dump($consultant);
        $consultantidtypecontrat = (int)$consultant[0]['idtypecontrat'];
        //var_dump($consultantidtypecontrat);
        //var_dump($consultantidtypecontrat);
        $libellecontrat = R::getAll('select libelle from typecontrat where typecontrat.idtype='."'$consultantidtypecontrat'".'');
        //var_dump($libellecontrat);
        //var_dump(preg_replace("/\s+/", "",$libellecontrat[0]['libelle']));
        $libellecontrat1 = preg_replace("/\s+/", "",$libellecontrat[0]['libelle']);
        //var_dump($libellecontrat1);


        //preg_replace("/\s+/", "",$untypecontrat[$i]['libelle'])


        //$libellecontrat1 = preg_replace("/\s+/", "",$libellecontrat[0]['libelle']);  //$untypecontrat[$i]['libelle']
        //var_dump($libellecontrat1);
        //$test = R::load('typecontrat',$consultantidtypecontrat);
        //var_dump($test);

        //$test = R::getAll('select libelle from typecontrat where typecontrat.idtype='.$consultantidtypecontrat.'')


        //var_dump($untypecontrat);
        //var_dump($consultant[0]['idtypecontrat']);

        //$untypecontrat = R::getAll('select * from typecontrat join contrat on typecontrat.idtype='.(int)$consultant[0]['idtypecontrat'].'');
        //var_dump($untypecontrat);

        for ($i=0; $i<count($consultant);$i++){
            $unconsultant = new consultant ($consultant[$i]['nom'], $consultant[$i]['prenom'], $consultant[$i]['adresse'], $consultant[$i]['ville'], $consultant[$i]['cp'], $consultant[$i]['tel'], $consultant[$i]['email'], $libellecontrat[0]['libelle'], $consultant[$i]['salaire'], $consultant[$i]['tarif']);
            //var_dump($unconsultant);
        }
        $contrat = new contrat($uncontrat->id, $unclient, $unconsultant, $uncontrat->datedebut,$uncontrat->datefin, $uncontrat->mission, $uncontrat->salaire, $uncontrat->tarif, $libellecontrat);
        //var_dump($contrat);
        return $contrat;
    }

    public function insertcontrat($uncontrat){ //ajouter un contrat
                $idcontrat = $uncontrat->getidContrat();
                $idclient = $uncontrat->getcleclient();
                $idutilisateur = $uncontrat->getcleconsultant();
                $mission = $uncontrat->getmission();

                $debut = $uncontrat->getdatedebut();
                //echo strtotime($debut);
                $dateD = date('Ymd', strtotime($debut));
                echo $dateD;

                $fin = $uncontrat->getdatefin();
                //echo strtotime($fin);
                $dateF = date('Ymd', strtotime($fin));
                echo $dateF;


                $lecontrat = r::dispense('contrat'); //créer un nouvel objet contrat

                //$dateD = date('d-m-Y', strtotime("April 15 2014"));
                //$dateF = date('d-m-Y', strtotime("May 15 2014"));

                $lecontrat->id = $idcontrat; //attribuer des valeurs aux champs
                $lecontrat->idclient = $idclient;
                $lecontrat->idutilisateur = $idutilisateur;
                $lecontrat->datedebut = $dateD; //$dateD;
                $lecontrat->datefin = $dateF;   //$dateF;

                if(empty($mission)){$mission = 'Non communiqué';}
                $lecontrat->mission = $mission;


                r::store($lecontrat); //enregistrer dans la bdd
                r::getAll("insert into contrat values ($idcontrat,$idclient,$idutilisateur,'$dateD','$dateF','$mission','FALSE')");
    }

    //renvoie le dernier contrat
    public function getdernierid(){
        $req="SELECT id FROM contrat WHERE id = (SELECT MAX(id) FROM contrat)";
        //print_r($req);
        $resultat = $this->pdo->query($req);
        $ligne = $resultat->fetch();
        $donnees = $ligne['id'];
        return intval($donnees);
    }

    //renvoie les infos d'un contrat à modifier
    // public function getnfocontratModif($idContrat){
    //     $req="SELECT contrat.id, idclient, idconsultant, datedebut, datefin, mission, salaire, tarif, typecontrat, raisonsocial, nom, prenom from consultant join contrat on consultant.id=contrat.idconsultant join client on contrat.idclient=client.id where contrat.id='$idContrat';";
    //     print_r($req);
    //     $resultat = $this->pdo->query($req);
    //     $ligne= $resultat->fetchAll(PDO::FETCH_ASSOC);
    //     return $ligne;
    // }

    //maj des infos dans la bdd
    public function setcontrat($idcontrat, $datedebut, $datefin, $mission){
        $req="update contrat set
                 datedebut = '$datedebut',
                 datefin = '$datefin',
                 mission = '$mission',
                 cacher = FALSE
                 where contrat.id = '$idcontrat'";
        $this->pdo->exec($req);
        print_r($req);
    }

    public function suppContrat($idContrat){
        $contrat = r::load('contrat',$idContrat);
        $contrat->cacher=true;
        r::store($contrat);
    }

    public function getIdContratFromObject($contrat){ //récupère l'id du contrat en fonction de l'objet
            $consultantDao = new consultantDao();
            $clientDao = new DaoClient();

            $idclient = $clientDao-> getidclientfromchamps($contrat->getcleclient());
            $datedebut= $contrat->getdatedebut();
            $datefin= $contrat->getdatefin();
            $salaire= $contrat->getsalaire();
            $tarif=$contrat->gettarif();
            //$typecontrat= $contrat->gettypecontrat();
            $idconsultant = $consultantDao->getIdConsultantFromobject($contrat->getcleconsultant());
            $idutilisateurcontrat =R::getAll('select contrat.idutilisateur from contrat where contrat.idclient='.$idclient.'');

            $consultantinfos = R::getAll('select * from consultant where consultant.idutilisateur='.$idutilisateurcontrat.'');
            $typecontrat = R::getAll('select typecontrat.libelle from typecrontrat where typecontrat.idtype='.$consultantinfos['idtypecontrat'].'');
            $mission = $contrat->getmission();

            $id=r::find("contrat", "idclient = ? and datedebut = ? and datefin = ? and salaire = ? and tarif = ? and typecontrat = ? and idconsultant = ? and mission = ?",
            array($idclient, $datedebut, $datefin, $salaire, $tarif, $typecontrat, $idconsultant, $mission));

            foreach($id as $unid){
                return($unid->id);
            }
    }

    public function timecontrat($idcontrat){ //retourne le nb de mois où le consultant à travailler sur la période du contrat
        $req = "SELECT EXTRACT(YEAR FROM datefin) - EXTRACT(YEAR FROM datedebut) as nbannee, EXTRACT(MONTH FROM datefin) - EXTRACT(MONTH FROM datedebut) as nbmois from contrat where id='$idcontrat'";
        $resultat = $this->pdo->query($req);
        $laperiode = $resultat->fetch();
        $nbmois = intval($laperiode['nbmois']) ;
       for ($i=0; $i<=$laperiode['nbannee']-1;$i++){;
            $nbmois = $nbmois + (12*$i);
        }
        return $nbmois;
    }

    public function getcontrat($idcontrat) { //retourne un contrat en fonction de son id
        $uncontrat = r::load('contrat', $idcontrat);
        $contrat = new contrat ($uncontrat->id, $uncontrat->idclient, $uncontrat->idutilisateur, $uncontrat->datedebut, $uncontrat->datefin, $uncontrat->mission);
        return $contrat;
    }

}
?>
