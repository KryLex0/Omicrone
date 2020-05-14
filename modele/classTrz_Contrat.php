<?php

class trz_contrat {
   private $_idContrat;
   private $_client;
   private $_date_debut_contrat;
   private $_date_fin_contrat;
   private $_mission_contrat;
   private $_consultant;
   private $_salaire_net;

   public function __construct($unIdContrat ,$unClient, $unConsultant, $unedate_debut_contrat, $unedate_fin_contrat, $unemission, $unsalaire){
       $this->_idContrat = $unIdContrat;
       $this->_client = $unClient;
       $this->_consultant = $unConsultant;
       $this->_mission_contrat = $unemission;
       $this->_date_debut_contrat = $unedate_debut_contrat;
       $this->_date_fin_contrat = $unedate_fin_contrat;
       $this->_salaire_net = $unsalaire;

   }

   public function getidContrat(){
       return $this->_idContrat;
   }

   public function getdate_debut_contrat(){
       return $this->_date_debut_contrat;
   }

   public function getdate_fin_contrat(){
       return $this->_date_fin_contrat;
   }

   public function getmission(){
       return $this->_mission_contrat;
   }
   public function getcleclient(){
        return $this->_client;
   }
   public function getcleconsultant(){
       return $this->_consultant;
   }
   public function getsalaire(){
       return($this->_salaire_net);
   }

}

class daoTrz_Contrat{

    public function __construct() {
        $this->pdo = PdoCommission::getInstance();
    }

    public function getlistecontrat(){ //liste de contrat
        $ligne= r::getAll("select trz_contrat.id, date_debut_contrat, date_fin_contrat,  mission_contrat, salaire_net, raison_social_client, trz_omicrone_user.nom_user from trz_client join trz_contrat on trz_client.id=trz_contrat.idclient join trz_omicrone_user on trz_contrat.idutilisateur=trz_omicrone_user.id where trz_contrat.cacher = false order by id DESC");
        //print_r($ligne);
        //preg_replace("-","/",$ligne['date_debut_contrat']);
        //$ligne[1] = date_format(date_create_from_format('Y-m-d', $ligne[1]),'d/m/Y');

        //$timestamp = strtotime($date_from_db);
        for($i=0; $i<=count($ligne)-1; $i++){
          $tmp = date('d/m/Y', strtotime($ligne[$i]['date_debut_contrat']));
          $ligne[$i]['date_debut_contrat'] = $tmp;
          $tmp = date('d/m/Y', strtotime($ligne[$i]['date_fin_contrat']));
          $ligne[$i]['date_fin_contrat'] = $tmp;
        }
        return $ligne;
    }

    public function collectioncontrat(){
        $collectionC= array();
        $lesContrats= r::getAll('select trz_consultant.idutilisateur as idconsultant, trz_omicrone_user.nom_user as nom, trz_omicrone_user.prenom_user as prenom, trz_omicrone_user.adresse_user as adrcons, trz_omicrone_user.ville_user as villecons, trz_omicrone_user.cp_user as cp, trz_omicrone_user.tel_user as tel, trz_omicrone_user.mail_user as email, trz_typecontrat.libelle as libelle, trz_consultant.tarif as tarif, trz_contrat.id as idcontrat, date_debut_contrat, date_fin_contrat, salaire_net, tarif, mission_contrat, trz_client.id as idclient, idcontact, raison_social_client, siret_client, trz_client.adresse_client as clientadr, trz_client.ville_client as clientville, code_postal_client from trz_omicrone_user join trz_consultant on trz_omicrone_user.id=trz_consultant.idutilisateur join trz_contrat on trz_consultant.idutilisateur=trz_contrat.idutilisateur join trz_client on trz_client.id=trz_contrat.idclient join trz_typecontrat on trz_typecontrat.idtype=trz_consultant.idtypecontrat where trz_contrat.cacher=false');


        for ($i=0; $i<=count($lesContrats)-1; $i++) {

                $objclient = new trz_client ($lesContrats[$i]['raison_social_client'],$lesContrats[$i]['idcontact'],$lesContrats[$i]['siret_client'], $lesContrats[$i]['clientadr'], $lesContrats[$i]['clientville'], $lesContrats[$i]['code_postal_client']);
                $objconsultant = new trz_consultant ($lesContrats[$i]['nom'], $lesContrats[$i]['prenom'], $lesContrats[$i]['adrcons'], $lesContrats[$i]['villecons'], $lesContrats[$i]['cp'], $lesContrats[$i]['tel'], $lesContrats[$i]['email'], $lesContrats[$i]['libelle'], $lesContrats[$i]['tarif']);
                $objcontrat = new trz_contrat($lesContrats[$i]['idcontrat'], $objclient, $objconsultant, $lesContrats[$i]['date_debut_contrat'],$lesContrats[$i]['date_fin_contrat'], $lesContrats[$i]['mission_contrat'],$lesContrats[$i]['salaire_net'], $lesContrats[$i]['tarif'], $lesContrats[$i]['libelle']);
                $collectionC[] = $objcontrat;
        }
    return $collectionC;
    }


    public function collectionContratFromId($id){
        $collectionC= array();
        $lesContrats= r::getAll('select trz_consultant.idutilisateur as idconsultant, trz_omicrone_user.nom_user as nom, trz_omicrone_user.prenom_user as prenom, trz_omicrone_user.adresse_user as adrcons, trz_omicrone_user.ville_user as villecons, trz_omicrone_user.cp_user as cp, trz_omicrone_user.tel_user as tel, trz_omicrone_user.mail_user as email, trz_typecontrat.libelle as libelle, trz_consultant.tarif as tarif, trz_contrat.id as idcontrat, date_debut_contrat, date_fin_contrat, salaire_net, tarif, mission_contrat, trz_client.id as idclient, idcontact, raison_social_client, siret_client, trz_client.adresse_client as clientadr, trz_client.ville_client as clientville, code_postal_client from trz_omicrone_user join trz_consultant on trz_omicrone_user.id=trz_consultant.idutilisateur join trz_contrat on trz_consultant.idutilisateur=trz_contrat.idutilisateur join trz_client on trz_client.id=trz_contrat.idclient join trz_typecontrat on trz_typecontrat.idtype=trz_consultant.idtypecontrat where trz_omicrone_user.id='.$id.'');

        for ($i=0; $i<=count($lesContrats)-1; $i++) {

                //$objclient = new trz_client ($lesContrats[$i]['raison_social_client'],$lesContrats[$i]['idcontact'],$lesContrats[$i]['siret_client'], $lesContrats[$i]['clientadr'], $lesContrats[$i]['clientville'], $lesContrats[$i]['code_postal_client']);
                //$objconsultant = new trz_consultant ($lesContrats[$i]['nom'], $lesContrats[$i]['prenom'], $lesContrats[$i]['adrcons'], $lesContrats[$i]['villecons'], $lesContrats[$i]['cp'], $lesContrats[$i]['tel'], $lesContrats[$i]['email'], $lesContrats[$i]['libelle'], $lesContrats[$i]['salaire'], $lesContrats[$i]['tarif']);
                $objcontrat = new trz_contrat($lesContrats[$i]['idcontrat'], $lesContrats[$i]['raison_social_client'], $lesContrats[$i]['nom'], $lesContrats[$i]['date_debut_contrat'],$lesContrats[$i]['date_fin_contrat'], $lesContrats[$i]['mission_contrat'],$lesContrats[$i]['salaire_net'], $lesContrats[$i]['tarif'], $lesContrats[$i]['libelle']);
                $collectionC[] = $objcontrat;
        }
    return $collectionC;
    }




    public function getobjcontrat($idcontrat){ //retourne un objet contrat en fonction de son id
        $uncontrat = R::load('trz_contrat', $idcontrat);  //équivalent à "select * from contrat where idcontrat ='$idcontrat'"                            recuperation d'un contrat en fonction de son ID
        //var_dump($uncontrat);
        $idclient = $uncontrat->idclient; //recupère l'id du client dans une variable                                                                 retourne l'id du client rataché a ce contrat
        $client = R::load('trz_client', $idclient); // select * from client en fonction de l'idclient récupérer précedemment                              informations concernant le client rattaché a ce contrat en fonction de l'idclient
        $idcontact = $client->idcontact;                                                                                                            //idcontact du client rattaché a ce contrat
        $contact = r::load('trz_contact', $idcontact);                                                                                                  //recupere le contact en fonction de l'id du contact(client)
        $uncontact = new trz_contact ($contact->email1, $contact->email2, $contact->email3, $contact->bureau, $contact->fax, $contact->tel3);           //creation d'un contact avec les infos du contact recupere juste au dessus
        $unclient =  new trz_client($client->raison_social_client, $uncontact, $client->siret_client, $client->adresse_client, $client->ville_client, $client->code_postal_client);              //creation d'un client avec infos $client
        $idconsultant = $uncontrat->idutilisateur;                                                                                             //recuperation de l'idutilisateur de la table contrat

        $consultant = R::getAll("select * from trz_omicrone_user join trz_consultant on trz_omicrone_user.id=trz_consultant.idutilisateur where trz_consultant.idutilisateur=$idconsultant");
        $consultantidtypecontrat = (int)$consultant[0]['idtypecontrat'];
        $libellecontrat = R::getAll('select libelle from trz_typecontrat where trz_typecontrat.idtype='."'$consultantidtypecontrat'".'');
        $libellecontrat1 = preg_replace("/\s+/", "",$libellecontrat[0]['libelle']);

        for ($i=0; $i<count($consultant);$i++){
            $unconsultant = new trz_consultant ($consultant[$i]['nom_user'], $consultant[$i]['prenom_user'], $consultant[$i]['adresse_user'], $consultant[$i]['ville_user'], $consultant[$i]['cp_user'], $consultant[$i]['tel_user'], $consultant[$i]['mail_user'], $libellecontrat[0]['libelle'], $consultant[$i]['tarif']);
            //var_dump($unconsultant);
        }
        $contrat = new trz_contrat($uncontrat->id, $unclient, $unconsultant, $uncontrat->date_debut_contrat,$uncontrat->date_fin_contrat, $uncontrat->mission_contrat, $uncontrat->salaire_net, $uncontrat->tarif, $libellecontrat);
        //var_dump($contrat);
        //var_dump($contrat);
        return $contrat;
    }

    public function insertcontrat($uncontrat){ //ajouter un contrat
                $idcontrat = $uncontrat->getidContrat();
                $idclient = $uncontrat->getcleclient();
                $idutilisateur = $uncontrat->getcleconsultant();
                $mission = $uncontrat->getmission();
                $salaire = $uncontrat->getsalaire();

                $debut = $uncontrat->getdate_debut_contrat();
                $dateD = date('Ymd', strtotime($debut));
                echo $dateD;

                $fin = $uncontrat->getdate_fin_contrat();
                $dateF = date('Ymd', strtotime($fin));
                echo $dateF;


                $lecontrat = r::dispense('trz_contrat'); //créer un nouvel objet contrat

                //$dateD = date('d-m-Y', strtotime("April 15 2014"));
                //$dateF = date('d-m-Y', strtotime("May 15 2014"));

                $lecontrat->id = $idcontrat; //attribuer des valeurs aux champs
                $lecontrat->idclient = $idclient;
                $lecontrat->idutilisateur = $idutilisateur;
                $lecontrat->date_debut_contrat = $dateD; //$dateD;
                $lecontrat->date_fin_contrat = $dateF;   //$dateF;
                $lecontrat->salaire_net = $salaire;

                if(empty($mission)){$mission = 'Non communiqué';}
                $lecontrat->mission_contrat = $mission;


                r::store($lecontrat); //enregistrer dans la bdd
                r::getAll("insert into trz_contrat values ($idcontrat,$idclient,$idutilisateur,'$dateD','$dateF','$mission','FALSE', $salaire)");
                $this->insertDatesContrat($lecontrat, $idutilisateur);
    }

    //renvoie le dernier contrat
    public function getdernierid(){
        $req="SELECT id FROM trz_contrat WHERE id = (SELECT MAX(id) FROM trz_contrat)";
        //print_r($req);
        $resultat = $this->pdo->query($req);
        $ligne = $resultat->fetch();
        $donnees = $ligne['id'];
        return intval($donnees);
    }

    //renvoie les infos d'un contrat à modifier
    // public function getnfocontratModif($idContrat){
    //     $req="SELECT contrat.id, idclient, idconsultant, date_debut_contrat, date_fin_contrat, mission, salaire, tarif, typecontrat, raison_social_client, nom, prenom from consultant join contrat on consultant.id=contrat.idconsultant join client on contrat.idclient=client.id where contrat.id='$idContrat';";
    //     print_r($req);
    //     $resultat = $this->pdo->query($req);
    //     $ligne= $resultat->fetchAll(PDO::FETCH_ASSOC);
    //     return $ligne;
    // }

    //maj des infos dans la bdd
    public function setcontrat($idcontrat, $date_debut_contrat, $date_fin_contrat, $mission_contrat, $salaire_contrat){
        $req="update trz_contrat set
                 date_debut_contrat = '$date_debut_contrat',
                 date_fin_contrat = '$date_fin_contrat',
                 mission_contrat = '$mission_contrat',
                 cacher = FALSE,
                 salaire_net = '$salaire_contrat'
                 where trz_contrat.id = '$idcontrat'";
        $this->pdo->exec($req);

        $ancienne_dates_contrat = r::getAll("select * from trz_dates_contrat where idcontrat='$idcontrat'");
        $this->removeDatesContrat($idcontrat);
        $contrat = r::getAll("select * from trz_contrat where trz_contrat.id='$idcontrat' and date_debut_contrat = '$date_debut_contrat' and date_fin_contrat = '$date_fin_contrat' and mission_contrat = '$mission_contrat' and salaire_net = '$salaire_contrat'");
        //var_dump($contrat[0]);
        $this->insertDatesContrat($contrat[0], $contrat[0]['idutilisateur']);
        $nouvelle_dates_contrat = r::getAll("select * from trz_dates_contrat where idcontrat='$idcontrat'");
        $this->updateDatesContrat($ancienne_dates_contrat,$nouvelle_dates_contrat);

        print_r($req);
    }


    public function updateDatesContrat($ancienne_dates_contrat,$nouvelle_dates_contrat){
      //var_dump($ancienne_dates_contrat);
      //var_dump($nouvelle_dates_contrat);
      foreach ($nouvelle_dates_contrat as $nouvelle_dates) {
        foreach ($ancienne_dates_contrat as $ancienne_dates) {
          if($ancienne_dates['aremplir'] == 'true'){
            $aremplir = 'true';
          }else{
            $aremplir = 'false';
          }

          if($ancienne_dates['complet'] == 'true'){
            $complet = 'true';
          }else{
            $complet = 'false';
          }
          $idutilisateur = $ancienne_dates['idutilisateur'];
          $idcontrat = $ancienne_dates['idcontrat'];
          $date_min = $ancienne_dates['date_min'];
          $date_max = $ancienne_dates['date_max'];

          if($nouvelle_dates['date_min'] === $ancienne_dates['date_min']){
            //var_dump($aremplir);
            R::getAll("update trz_dates_contrat set aremplir='$aremplir', complet='$complet' where idutilisateur='$idutilisateur' and idcontrat='$idcontrat' and date_min='$date_min' and date_max='$date_max' ");
          }
        }
      }
    }




    public function suppContrat($idContrat){
        $contrat = r::load('trz_contrat',$idContrat);
        $contrat->cacher=true;
        r::store($contrat);
    }

    public function getIdContratFromObject($contrat){ //récupère l'id du contrat en fonction de l'objet
            $consultantDao = new trz_consultantDao();
            $clientDao = new DaoTrz_Client();

            $idclient = $clientDao-> getidclientfromchamps($contrat->getcleclient());
            $date_debut_contrat= $contrat->getdate_debut_contrat();
            $date_fin_contrat= $contrat->getdate_fin_contrat();
            $salaire= $contrat->getsalaire();
            $tarif=$contrat->gettarif();
            //$typecontrat= $contrat->gettypecontrat();
            $idconsultant = $consultantDao->getIdConsultantFromobject($contrat->getcleconsultant());
            $idutilisateurcontrat =R::getAll('select trz_contrat.idutilisateur from trz_contrat where trz_contrat.idclient='.$idclient.'');

            $consultantinfos = R::getAll('select * from trz_consultant where trz_consultant.idutilisateur='.$idutilisateurcontrat.'');
            $typecontrat = R::getAll('select trz_typecontrat.libelle from typecrontrat where trz_typecontrat.idtype='.$consultantinfos['idtypecontrat'].'');
            $mission = $contrat->getmission();

            $id=r::find("trz_contrat", "idclient = ? and date_debut_contrat = ? and date_fin_contrat = ? and salaire_net = ? and tarif = ? and trz_typecontrat = ? and idconsultant = ? and mission_contrat = ?",
            array($idclient, $date_debut_contrat, $date_fin_contrat, $salaire, $tarif, $typecontrat, $idconsultant, $mission));

            foreach($id as $unid){
                return($unid->id);
            }
    }

    public function timecontrat($idcontrat){ //retourne le nb de mois où le consultant à travailler sur la période du contrat
        $req = "SELECT EXTRACT(YEAR FROM date_fin_contrat) - EXTRACT(YEAR FROM date_debut_contrat) as nbannee, EXTRACT(MONTH FROM date_fin_contrat) - EXTRACT(MONTH FROM date_debut_contrat) as nbmois from trz_contrat where id='$idcontrat'";
        $resultat = $this->pdo->query($req);
        $laperiode = $resultat->fetch();
        $nbmois = intval($laperiode['nbmois']) ;
       for ($i=0; $i<=$laperiode['nbannee']-1;$i++){;
            $nbmois = $nbmois + (12*$i);
        }
        return $nbmois;
    }

    public function getcontrat($idcontrat) { //retourne un contrat en fonction de son id
        $uncontrat = r::load('trz_contrat', $idcontrat);
        $contrat = new trz_contrat ($uncontrat->id, $uncontrat->idclient, $uncontrat->idutilisateur, $uncontrat->date_debut_contrat, $uncontrat->date_fin_contrat, $uncontrat->mission_contrat, $uncontrat->salaire_net);
        return $contrat;
    }


    public function getDatesContrat($idContrat){
      $dates = r::getAll("select * from trz_dates_contrat where idcontrat='$idContrat'");
      //var_dump($dates);
      return $dates;
    }
    public function getAllDatesContrat(){
      $dates = r::getAll("select * from trz_dates_contrat ORDER BY date_min");
      return $dates;
    }
    public function getAllDatesContratARemplir(){
      $dates = r::getAll("select * from trz_dates_contrat where aremplir=true ORDER BY date_min");
      return $dates;
    }

    public function getAllContratFromIdUser($iduser){
      $contrats = r::getAll("select * from trz_dates_contrat where idutilisateur='$iduser' ORDER BY idcontrat,date_min");
      return $contrats;
    }
    //
    // public function craRempli($idutilisateur,$idcontrat,$dateD){
    //   $test = R::getAll("select COUNT(1) from trz_dates_contrat where idutilisateur='$idutilisateur' and idcontrat='$idcontrat' and date_min='$dateD' and complet='TRUE'");
    //   if($test[0]['count'] == 1){
    //     return true;
    //   }else{
    //     return false;
    //   }
    // }
    // public function craEnvoye($idutilisateur,$idcontrat,$dateD){
    //   $test = R::getAll("select COUNT(1) from trz_dates_contrat where idutilisateur='$idutilisateur' and idcontrat='$idcontrat' and date_min='$dateD' and aremplir='TRUE' and complet='FALSE'");
    //
    //   if($test[0]['count'] == 1){
    //     return true;
    //   }else{
    //     return false;
    //   }
    // }
    //
    // public function envoiDemandeCraConsultant($dateD){
    //   list($annee1, $mois1, $jour1) = explode('-', $dateD);
    //   $jour1 = '01';
    //   $dateD = $annee1 . '-' . $mois1 . '-' . $jour1;
    //
    //   R::getAll("update trz_dates_contrat set aremplir='TRUE' where date_min='$dateD' and complet='FALSE'");
    // }
    //
    //
    //
    // public function demandeRemplirCra($idContrat,$dateMin){
    //   r::getAll("update trz_dates_contrat set aremplir='TRUE' where date_min='$dateMin' and idcontrat='$idContrat' and complet='FALSE'");
    // }
    //
    // public function getContratARemplir($idconsultant) { //retourne un contrat en fonction de son id
    //     $dates = r::getAll("select * from trz_dates_contrat where idutilisateur='$idconsultant' and aremplir='TRUE' and complet='FALSE'");
    //     return $dates;
    // }
    //
    // public function completerCra($idutilisateur, $idContrat, $dateMin){
    //   r::getAll("update trz_dates_contrat set aremplir='FALSE', complet='TRUE' where date_min='$dateMin' and idcontrat='$idContrat' and idutilisateur='$idutilisateur'");
    // }


    public function removeDatesContrat($idcontrat){
      R::getAll("delete from trz_dates_contrat where idcontrat='$idcontrat'");
    }



    public function insertDatesContrat($lesContrats, $idutilisateur){
      //foreach ($lesContrats as $unContrat=>$val){
        //var_dump($lesContrats);
      //for($i=0; $i<sizeof($lesContrats);$i++){
      //var_dump($lesContrats[0]->getdate_debut_contrat());
        //var_dump($lesContrats);
        $dateMin = date('d/m/Y', strtotime($lesContrats['date_debut_contrat']));
        $dateMax = date('d/m/Y', strtotime($lesContrats['date_fin_contrat']));
        //var_dump($lesContrats);
        $idcontrat = $lesContrats['id'];
        //var_dump($idcontrat);

        list($jour1, $mois1, $annee1) = explode('/', $dateMin);
        list($jour2, $mois2, $annee2) = explode('/', $dateMax);

        $timestamp1 = mktime(0,0,0,$mois1,$jour1,$annee1);
        $timestamp2 = mktime(0,0,0,$mois2,$jour2,$annee2);


        $i=0;
        $nbJours = abs($timestamp2 - $timestamp1)/86400;
          $diff = (($annee2 - $annee1) * 12) + ($mois2 - $mois1);
          $mois = $mois1;
            while($i<=$diff){
              if($mois > 12){
                $mois = 1;
                $annee1 += 1;
              }
                $jour1 = 1;
                $jourFinMois1 = cal_days_in_month(CAL_GREGORIAN, (int)$mois, (int)$annee1);

                $timestampTemp1 = mktime(0,0,0,$mois,$jour1,$annee1);
                $date1=date('Ymd', $timestampTemp1);

                $timestampTemp2 = mktime(0,0,0,$mois,$jourFinMois1,$annee1);
                $date2=date('Ymd', $timestampTemp2);

                r::getAll("insert into trz_dates_contrat values ($idutilisateur, $idcontrat, '$date1', '$date2', 'FALSE','FALSE')");

                $mois = $mois + 1;
                $i++;
          }
      //}





    }

}
?>
