<?php

class trz_client {
    private $_id;
    private $_idcontact;
    private $_raison_social_client;
    private $_siret_client;
    private $_adresse_client;
    private $_ville_client;
    private $_code_postal_client;

    public function __construct($uneRS, $unIdContact, $unsiret_client, $uneadresse_client, $uneville_client, $uncode_postal_client){
        //$this->_id =  $unid;
        $this->_raison_social_client =  $uneRS;
        $this->_idcontact = $unIdContact;
        $this->_siret_client = $unsiret_client;
        $this->_adresse_client = $uneadresse_client;
        $this->_ville_client = $uneville_client;
        $this->_code_postal_client = $uncode_postal_client;
    }

    public function getid(){
        return $this->_id;
    }

    public function getclecontact(){
        return $this->_idcontact;
    }

     public function getraison_social_client(){
        return $this->_raison_social_client;
    }
     public function getsiret_client(){
        return $this->_siret_client;
    }
     public function getadresse_client(){
        return $this->_adresse_client;
    }
     public function getville_client(){
        return $this->_ville_client;
    }
     public function getcode_postal_client(){
        return $this->_code_postal_client;
    }
}
class DaoTrz_Client {

     public function __construct() {
        $this->pdo = PdoCommission::getInstance();
    }

    public function listeclient(){
        $ligne = r::getAll("select trz_client.id as id, raison_social_client, siret_client, adresse_client, ville_client, code_postal_client, email1, email2, email3, bureau, fax, tel3 FROM trz_client join trz_contact on trz_client.idcontact=trz_contact.id where trz_client.cacher = false order by trz_client.id ASC");
        return $ligne;
    }

    public function collectionclient(){
        $collectionclient = array();
        $lesclients=r::getAll("SELECT trz_client.id as id, raison_social_client, siret_client, adresse_client, ville_client, code_postal_client, email1, email2, email3, bureau, fax, tel3 FROM trz_client join trz_contact on trz_client.idcontact=trz_contact.id where trz_client.cacher=false order by trz_client.id desc");
        //print_r($req);
        for($i=0; $i<=sizeof($lesclients)-1;$i++){
            $objcontact = new trz_contact ($lesclients[$i]['email1'],$lesclients[$i]['email2'],$lesclients[$i]['email3'],$lesclients[$i]['bureau'],$lesclients[$i]['fax'],$lesclients[$i]['tel3']);
            $objclient = new trz_client ($lesclients[$i]['raison_social_client'],$objcontact,$lesclients[$i]['siret_client'],$lesclients[$i]['adresse_client'],$lesclients[$i]['ville_client'],$lesclients[$i]['code_postal_client']);

            $collectionclient[]=$objclient;
        }
        return $collectionclient;
    }

    public function selectClients(){
        $laligne = r::getAll("select id, raison_social_client from trz_client ");
        return $laligne;
    }

    public function getdernierid(){
        $req="SELECT id FROM client WHERE id = (SELECT MAX(id) FROM client)";
        //print_r($req);
        $resultat = $this->pdo->query($req);
        $ligne = $resultat->fetch();
        $donnees = $ligne['id'];
        //return intval($donnees);
        return $donnees;
    }

    public function ajouterclient($client,$contactDao){
        //var_dump($client);
        $email = $client->getclecontact()->getemail();
        $email2 = $client->getclecontact()->getemail2();
        $email3 = $client->getclecontact()->getemail3();
        $bureau = $client->getclecontact()->getnumbureau();
        $fax = $client->getclecontact()->getfax();
        $tel = $client->getclecontact()->gettel();
        $lecontact = R::dispense('trz_contact'); //créer un objet contact

        $lecontact->email1 = $email;
        $lecontact->email2 = $email2;
        $lecontact->email3 = $email3;
        $lecontact->bureau = $bureau;
        $lecontact->fax = $fax;
        $lecontact->tel3 = $tel;
        R::store($lecontact); //envoie dans la bdd

        $idcontact_fk = $contactDao->getIdContactFromChamps($client->getclecontact());
        $raison_social_client = $client->getraison_social_client();
        $siret_client = $client->getsiret_client();
        $adresse_client = $client->getadresse_client();
        $ville_client = $client->getville_client();
        $code_postal_client = $client->getcode_postal_client();

        $leclient = R::dispense('trz_client'); //créer un objet client
        $leclient->idcontact = $idcontact_fk;
        $leclient->raison_social_client = $raison_social_client;
        $leclient->siret_client = $siret_client;
        $leclient->adresse_client = $adresse_client;
        $leclient->ville_client = $ville_client;
        $leclient->code_postal_client = $code_postal_client;
        R::store($leclient);
        //var_dump($leclient);
    }



    public function getinfoclient($id){
        $req="select id, raison_social_client, adresse_client, siret_client, ville_client, code_postal_client, email1, email2, email3,tel3, bureau, fax FROM trz_client join trz_contact on trz_client.idcontact=trz_contact.id where trz_client.id='$id'";
        //print_r($req);
        $rs = $this->pdo->query($req);
        $ligne = $rs->fetchall(PDO::FETCH_ASSOC);
        return $ligne;
    }

    public function getclient($idduclient)/* recupère l'objet client par rapport à son l'id*/{
       $client = R::load("trz_client",$idduclient);
       $unclient=new trz_client($client->raison_social_client, $client->idcontact, $client->siret_client, $client->adresse_client, $client->ville_client, $client->codepostal);
       return($unclient);
        }


    public function setclient($client,$id,$idcontact_fk){
        //$id = $client->getid();
        //$idcontact_fk = $contactDao->getIdContactFromChamps($client->getclecontact());
        $raison_social_client = $client->getraison_social_client();
        $siret_client = $client->getsiret_client();
        $adresse_client = $client->getadresse_client();
        $ville_client = $client->getville_client();
        $code_postal_client = $client->getcode_postal_client();
        $test = R::getAll("select * from trz_client where id='$id'");
        //var_dump($test);

        $leclient = R::load("trz_client",$id); //créer un objet client
        $leclient->idcontact = $idcontact_fk;
        $leclient->raison_social_client = $raison_social_client;
        $leclient->siret_client = $siret_client;
        $leclient->adresse_client = $adresse_client;
        $leclient->ville_client = $ville_client;
        $leclient->code_postal_client = $code_postal_client;
        $leclient->cacher=false;
        R::store($leclient);
    }
    public function getidfromchamps($client){
        $raison_social_client = $client->getraison_social_client();
        $siret_client = $client->getsiret_client();
        $adresse_client = $client->getadresse_client();
        $ville_client = $client->getville_client();
        //$code_postal_client = $client->getcode_postal_client();
        //var_dump($client);

        //and code_postal_client = ? ////////, $code_postal_client

        $id = R::find("trz_client", "raison_social_client = ? and siret_client = ? and adresse_client = ? and ville_client = ?", array($raison_social_client, $siret_client, $adresse_client, $ville_client));
        //raison_social_client='$raison_social_client' and siret_client='$siret_client' and adresse_client='$adresse_client' and ville_client='$ville_client' and
        // and siret_client='$siret_client' and adresse_client='$adresse_client' and ville_client='$ville_client' and code_postal_client='$code_postal_client'
        //$id = R::getAll("select * from trz_client where raison_social_client='$raison_social_client' and siret_client='$siret_client' and adresse_client='$adresse_client' and ville_client='$ville_client' and code_postal_client=$code_postal_client");
        //var_dump($id);
        foreach($id as $unid){
            return($unid->id);}
    }

    public function suppclient($client){
            //var_dump($client);
            $id = $this->getidfromchamps($client);
            $client = R::load("trz_client", $id);
            //$client = R::getAll("select * from client where id='$id'");
            $client->cacher=true;
            //var_dump($client);
            r::store($client);
        }
}

?>
