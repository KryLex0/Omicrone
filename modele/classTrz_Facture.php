<?php

class trz_facture{
    private $_idfacture;
    private $_libelle_facture;
    private $_cra;
    private $_montant_facture;
    private $_date_facture;

    public function __construct($unlibelle, $uncra, $unmontant, $datef) {
        $this->_libelle_facture = $unlibelle;
        $this->_cra = $uncra;
        $this->_montant_facture = $unmontant;
        $this->_date_facture = $datef;
    }
    public function getidfacture(){
        return $this->_idfacture;
    }
    public function getclecra(){
        return $this->_cra;
    }
    public function getlibelle(){
        return $this->_libelle_facture;
    }
    public function getdatef(){
        return $this->_date_facture;
    }
    public function getmontant() {
        return $this->_montant_facture;
    }
    public function MontantAvecTVA(){
        return $this->getmontant() * 1.2;
    }
}

class Trz_FactureDao{
    public function __construct() {
        $this->pdo = PdoCommission::getInstance();
    }

    public function getobjectfromid($idfacture){
        $facture = r::load('trz_facture', $idfacture);
        $unefacture = new trz_facture ($facture->libelle_facture, $facture->idcra, $facture->montant_facture, $facture->date_facture);
        return ($unefacture);
    }

    public function collectionfacture(){
        $collection = array();
        $lafacture = r::getAll('select trz_consultant.idutilisateur as idconsultant, trz_omicrone_user.nom_user as nom, trz_omicrone_user.prenom_user as prenom, trz_omicrone_user.adresse_user as adrcons, trz_omicrone_user.ville_user as villecons, trz_omicrone_user.cp_user as cp, trz_omicrone_user.tel_user as tel, trz_omicrone_user.mail_user as email, typecontrat.libelle as typecontrat, trz_consultant.salaire as salaire, trz_consultant.tarif as tarif, trz_contrat.id as idcontrat, date_debut_contrat, date_fin_contrat, mission_cra, trz_client.id as idclient, idcontact, raison_social_client, siret_client, trz_client.adresse_client as clientadr, trz_client.ville_client as clientville, code_postal_client, trz_cra.id as idcra, totaljfacturable_cra, totaljmaladie_cra, totaljconge_cra, astreinte_cra, periode_cra, intervention_cra, trz_contact.id as idcontact, email1, email2, email3, bureau, fax, tel3, trz_facture.id as idfacture ,idcra, libelle_facture, montant_facture, date_facture from trz_omicrone_user, trz_consultant, trz_contrat, trz_client, trz_cra, trz_contact, trz_facture where trz_omicrone_user.id=consultant.idutilisateur and trz_contact.id=trz_client.idcontact and trz_client.id=trz_contrat.idclient and trz_consultant.idutilisateur=trz_contrat.idutilisateur and trz_cra.idcontrat=trz_contrat.id and trz_cra.id=trz_facture.idcra order by id DESC');
        for ($i=0; $i<=count($lafacture)-1; $i++) {
            $objcontact = new trz_contact ($lafacture[$i]['email1'],$lafacture[$i]['email2'],$lafacture[$i]['email3'],$lafacture[$i]['bureau'],$lafacture[$i]['fax'],$lafacture[$i]['tel']);
            $objclient = new trz_client ($lafacture[$i]['raison_social_client'], $objcontact, $lafacture[$i]['siret_client'], $lafacture[$i]['clientadr'], $lafacture[$i]['clientville'], $lafacture[$i]['code_postal_client']);
            $objconsultant = new trz_consultant ($lafacture[$i]['nom'], $lafacture[$i]['prenom'], $lafacture[$i]['adrcons'], $lafacture[$i]['villecons'], $lafacture[$i]['cp'], $lafacture[$i]['tel'], $lafacture[$i]['email'], $lafacture[$i]['typecontrat'], $lafacture[$i]['salaire'], $lafacture[$i]['tarif']);
            $objcontrat = new trz_contrat($lafacture[$i]['idcontrat'], $objclient, $objconsultant, $lafacture[$i]['date_debut_contrat'],$lafacture[$i]['date_fin_contrat'], $lafacture[$i]['mission_cra'],$lafacture[$i]['salaire'], $lafacture[$i]['tarif'], $lafacture[$i]['typecontrat']);
            $objcra = new trz_cra ($lafacture[$i]['totaljfacturable_cra'], $lafacture[$i]['totaljmaladie_cra'],$lafacture[$i]['totaljconge_cra'], $objcontrat, $lafacture[$i]['astreinte_cra'], $lafacture[$i]['periode_cra'], $lafacture[$i]['intervention_cra']);
            $objetfacture = new trz_facture ($lafacture[$i]['libelle_facture'], $objcra, $lafacture[$i]['montant_facture'], $lafacture[$i]['date_facture']);
            $collection[]=$objetfacture;
        }
        return $collection;
    }

    public function getfacture($mois, $idcontrat, $contrat){
        $lafacture = r::getAll("select trz_cra.id as idcra, totaljfacturable_cra, totaljmaladie_cra, totaljconge_cra, astreinte_cra, periode_cra, intervention_cra, trz_facture.id as idfacture, libelle_facture, montant_facture, date_facture from trz_cra join trz_facture on trz_cra.id=trz_facture.idcra where trz_cra.periode_cra='$mois' and trz_cra.idcontrat='$idcontrat'");
        //$lafacture = r::getAll("select trz_omicrone_user.nom_user as nom, trz_omicrone_user.prenom_user as prenom, trz_omicrone_user.adresse_user as adrcons, trz_omicrone_user.ville_user as villecons, trz_omicrone_user.cp_user as cp, trz_omicrone_user.tel_user as tel, trz_omicrone_user.mail_user as email, consultant.typecontrat as typecontrat, consultant.salaire as salaire, consultant.tarif as tarif, contrat.id as idcontrat, datedebut, datefin, mission_cra, client.id as idclient, raisonsocial, siret, client.adr as clientadr, client.ville as clientville, codepostal, cra.id as idcra, totaljfacturable, totaljmaladie, totaljconge, astreinte_cra, periode, trz_contact.id as idcontact, email1, email2, email3, bureau, fax, tel3, facture.id as idfacture ,idcra, libelle, montant, datef from trz_omicrone_user, consultant, contrat, client, cra, contact, facture where trz_omicrone_user.id=consultant.idutilisateur and contact.id=client.idcontact and client.id=contrat.idclient and consultant.idutilisateur=contrat.idutilisateur and cra.idcontrat=contrat.id and cra.id=facture.idcra and cra.periode='".$mois."' and cra.idcontrat =".$idcontrat."");
        //var_dump($lafacture);
        for ($i=0; $i<=count($lafacture)-1; $i++) {
            // $objcontact = new trz_contact ($lafacture[$i]["email1"],$lafacture[$i]["email2"],$lafacture[$i]["email3"],$lafacture[$i]["bureau"],$lafacture[$i]["fax"],$lafacture[$i]["tel"]);
            // $objclient = new client ($lafacture[$i]["raisonsocial"], $objcontact, $lafacture[$i]["siret"], $lafacture[$i]["clientadr"], $lafacture[$i]["clientville"], $lafacture[$i]["codepostal"]);
            // $objconsultant = new consultant ($lafacture[$i]["nom"], $lafacture[$i]["prenom"], $lafacture[$i]["adrcons"], $lafacture[$i]["villecons"], $lafacture[$i]["cp"], $lafacture[$i]["tel"], $lafacture[$i]["email"], $lafacture[$i]["typecontrat"], $lafacture[$i]["salaire"], $lafacture[$i]["tarif"]);
            // $objcontrat = new contrat($lafacture[$i]["idcontrat"], $objclient, $objconsultant, $lafacture[$i]["datedebut"],$lafacture[$i]["datefin"], $lafacture[$i]["mission_cra"],$lafacture[$i]["salaire"], $lafacture[$i]["tarif"], $lafacture[$i]["typecontrat"]);
            $objcra = new trz_cra ($lafacture[$i]["totaljfacturable_cra"], $lafacture[$i]["totaljmaladie_cra"],$lafacture[$i]["totaljconge_cra"], $contrat, $lafacture[$i]["astreinte_cra"], $lafacture[$i]["periode_cra"], $lafacture[$i]["intervention_cra"]);
            $lafacture[$i]["date_facture"] = date('d/m/Y', strtotime($lafacture[$i]["date_facture"]));
            $facture = new trz_facture ($lafacture[$i]["libelle_facture"], $objcra, $lafacture[$i]["montant_facture"], $lafacture[$i]["date_facture"]);
        }

        return $facture;
    }

    public function addfacture($facture){
        $libelle = $facture->getlibelle();
        $cra = $facture->getclecra();
        $date = $facture->getdatef();
        $montant = $facture->getmontant();

        $lafacture = R::dispense('trz_facture');
        $lafacture->libelle_facture = $libelle;
        $lafacture->idcra = $cra;
        $lafacture->date_facture = $date;
        $lafacture->montant_facture = $montant;
        R::store($lafacture);
    }

    public function factureexists($idContrat, $mois){
       $req =  r::getAll("select EXISTS (select trz_facture.id ,idcra, libelle_facture, montant_facture, date_facture from trz_facture join trz_cra on trz_cra.id=trz_facture.idcra where trz_cra.idcontrat=".$idContrat." and trz_cra.periode_cra='".$mois."')");
       //print_r($req);
       return $req[0]["exists"];
    }

    public function getdatefacture($idcontrat){ //retourne les dates de chaque facture pour un contrat
        $collection=array();
        $lesdates= r::getAll("select distinct periode_cra from trz_cra where idcontrat='$idcontrat' order by periode_cra");
        for ($i=0; $i<count($lesdates);$i++){
            $unedate = $lesdates[$i]['periode_cra'];
            $collection[]=$unedate;
        }
        return $collection;
    }

    public function getidfacutrefromcontrat($idContrat){
      //var_dump($idContrat);
      $idfacture =  r::getAll("select trz_facture.id as idfacture from trz_facture join trz_cra on trz_cra.id=trz_facture.idcra where trz_cra.idcontrat=".$idContrat." and chemin IS NOT NULL");
      //var_dump();
      for($i=0; $i<=sizeof($idfacture)-1;$i++){
        $IDFACTURE = $idfacture[$i]['idfacture'];}
        return  $IDFACTURE;
      }

    public function dernieridfacture(){
        $req="SELECT id FROM trz_facture WHERE id = (SELECT MAX(id) FROM trz_facture)";
        //print_r($req);
        $resultat = $this->pdo->query($req);
        $ligne = $resultat->fetch();
        $donnees = $ligne['id'];
        return intval($donnees);
        //return $donnees;
    }

    public function getIdFromIdCra($idcra){
      $idfacture=r::getAll("select id from trz_facture where idcra=$idcra");
      return $idfacture[0]['id'];
    }

    public function getIdCraFromIdcontrat($idcontrat, $leMois){
      $idfacture=r::getAll("select id from trz_cra where idcontrat='$idcontrat' and periode_cra='$leMois' and chemin IS NOT NULL");
      return $idfacture;
    }
}
