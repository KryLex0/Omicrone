<?php
class utilisateur {
    private $id;
    private $nom;
    private $prenom;
    private $tel;
    private $email;
    private $adr;
    private $ville;
    private $cp;

    public function __construct($unNom,$unPrenom,$uneAdresse,$uneVille,$unCp,$unTel,$unEmail)
    {
        $this->nom = $unNom;
        $this->prenom=$unPrenom;
        $this->adr=$uneAdresse;
        $this->ville=$uneVille;
        $this->cp=$unCp;
        $this->tel=$unTel;
        $this->email=$unEmail;
    }

    public function getId(){
        return $this->id;
    }
    public function getNom(){
        return($this->nom);
    }
    public function getPrenom(){
        return($this->prenom);
    }
    public function getAdresse(){
        return($this->adr);
    }
    public function getVille(){
        return($this->ville);
    }
    public function getCp(){
        return($this->cp);
    }
    public function getTel(){
        return($this->tel);
    }
    public function getEmail(){
        return($this->email);
    }
}

class consultant extends utilisateur {
     private $typecontrat;
     private $salaire;
     private $tarif;

     public function __construct($unNom,$unPrenom,$uneAdresse,$uneVille,$unCp,$unTel, $unEmail, $untypecontrat, $unSalaire, $unTarif){
        parent::__construct($unNom,$unPrenom,$uneAdresse,$uneVille,$unCp,$unTel, $unEmail);
        $this->typecontrat = $untypecontrat;
        $this->salaire = $unSalaire;
        $this->tarif = $unTarif;
     }

     public function gettypecontrat(){
         return($this->typecontrat);
     }

     public function getsalaire(){
         return($this->salaire);
     }

     public function gettarif(){
         return($this->tarif);
     }
}

class commercial extends utilisateur {
    public function __construct($unNom,$unPrenom,$uneAdresse,$uneVille,$unCp,$unTel, $unEmail){
        parent::__construct($unNom,$unPrenom,$uneAdresse,$uneVille,$unCp,$unTel, $unEmail);
    }

}


class utilisateurDao {
    public function getiduser($utilisateur){
        $nom= $utilisateur->getNom();
        $prenom= $utilisateur->getPrenom();
        $adr=$utilisateur->getAdresse();
        $ville=$utilisateur->getVille();
        $cp=$utilisateur->getCp();
        $tel= $utilisateur->getTel();
        $email= $utilisateur->getEmail();

        $id = r::find('utilisateur', 'nom = ? and prenom = ? and adresse = ? and ville = ? and cp = ? and tel = ? and email = ? ',
        array($nom,$prenom,$adr,$ville,$cp,$tel,$email));
         foreach($id as $unid){
            return($unid->id);
        }
    }
}
class UconsultantDao  {


  public function add($consultant){
        $nom= $consultant->getNom();
        $prenom= $consultant->getPrenom();
        $tel= $consultant->getTel();
        $email= $consultant->getEmail();
        $adresse=$consultant->getAdresse();
        $ville=$consultant->getVille();
        $cp=$consultant->getCp();
        $typecontrat = $consultant->gettypecontrat();
        $idtypecontrat = R::getAll('select idtype from typecontrat where typecontrat.libelle='.$typecontrat.'');
        $idtypecontrat = (int)$idtypecontrat[0]['idtype'];
        $salaire = $consultant->getsalaire();
        $tarif = $consultant->gettarif();

        //var_dump($idtypecontrat);


        $consultant = R::dispense('utilisateur'); // on crée un commercial

        $consultant->nom = $nom; // on lui donne les champs
        $consultant->prenom = $prenom;
        $consultant->tel=$tel;
        $consultant->email=$email;
        $consultant->adresse=$adresse;
        $consultant->ville=$ville;
        $consultant->cp=$cp;
        R::store($consultant); // on le sauvegarde en BDD
        $idC = r::getAll("SELECT id FROM utilisateur WHERE id = (SELECT MAX(id) FROM utilisateur)");
        $id = $idC[0]["id"];
        r::getAll("insert into consultant values ($id,$salaire,$tarif, false, $idtypecontrat)");        //A REVOIR!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    }
    public function collectionconsultant(){ //collection de consultant

        $collection = array();
        $lesconsultants = r::getAll('select nom, prenom, adresse, ville, cp, tel, email, libelle, salaire, tarif
         from utilisateur join consultant on utilisateur.id=consultant.idutilisateur join typecontrat on consultant.idtypecontrat=typecontrat.idtype where cacher=false order by id desc');
        //print_r($lesconsultants);
        foreach($lesconsultants as $unconsultant){
            $unconsultant = new consultant ($unconsultant['nom'], $unconsultant['prenom'], $unconsultant['adresse'], $unconsultant['ville'],
            $unconsultant['cp'], $unconsultant['tel'], $unconsultant['email'], $unconsultant['libelle'], $unconsultant['salaire'],
             $unconsultant['tarif']);
            $collection[] = $unconsultant;
        }
        return $collection;
    }

    public function getconsultant(){ //retour la liste des noms et prenom des consultants
        $consultant = r::getAll('select id, nom, prenom from utilisateur join consultant on utilisateur.id=consultant.idutilisateur');
        return $consultant;
    }

    public function getIdConsultantFromobject($consultant){ //recupere l'id du consultant

        $nom= $consultant->getNom();
        $prenom= $consultant->getPrenom();
        $adr=$consultant->getAdresse();
        $ville=$consultant->getVille();
        $cp=$consultant->getCp();
        $tel= $consultant->getTel();
        $email= $consultant->getEmail();
        //$typecontratliste = array();
        //$consultantinf = R::getAll('select idtypecontrat from ');

        //$typecontrat = R::getAll('select idtypecontrat from utilisateur where ')

        $typecontrat = $consultant->gettypecontrat();
        //var_dump($idtypecontrat);
        $idtypecontrat = R::getAll("select idtype from typecontrat where typecontrat.libelle='$typecontrat'");
        //var_dump($idtypecontrat);
        $idtypecontrat = (int)$idtypecontrat[0]['idtype'];
        //$typecontratliste = $typecontrat[1];
        //$typecontrat = $typecontrat[1];
        $salaire = $consultant->getsalaire();
        $tarif = $consultant->gettarif();
        //$typecontrat = $typecontrat[1];

        $id = r::getAll("select consultant.idutilisateur as idu, typecontrat.libelle as libelle from utilisateur join consultant on utilisateur.id=consultant.idutilisateur join typecontrat on consultant.idtypecontrat=typecontrat.idtype where nom='$nom' and prenom='$prenom' and adresse='$adr'
         and ville='$ville' and cp='$cp' and tel='$tel' and email='$email' and idtypecontrat='$idtypecontrat' and salaire='$salaire' and tarif='$tarif'");

        return $id[0]['idu'];
    }

    public function getConsultantfromId($idC){ //retourne l'objet en fonction de son id
        $req = R::getAll('select nom, prenom, adresse, ville, cp, tel, email, typecontrat.libelle as libelle, salaire, tarif from utilisateur join consultant on utilisateur.id=consultant.idutilisateur join typecontrat on consultant.idtypecontrat=typecontrat.idtype where consultant.idutilisateur='.$idC.'');
        //var_dump($req);
        foreach($req as $laligne){
        $consultant=new consultant($laligne['nom'],$laligne['prenom'],$laligne['adresse'],$laligne['ville'],$laligne['cp'],$laligne['tel'],$laligne['email'], $laligne['libelle'], $laligne['salaire'], $laligne['tarif']);
        //var_dump($laligne['typecontrat']);
        return($consultant);
        }
    }




    public function updateUser($consultant,$idconsultant){
      $nom= $consultant->getNom();
      $prenom= $consultant->getPrenom();
      $adr=$consultant->getAdresse();
      $ville=$consultant->getVille();
      $cp=$consultant->getCp();
      $tel= $consultant->getTel();
      $email= $consultant->getEmail();

      $typecontrat = $consultant->gettypecontrat();
      //var_dump($typecontrat);
      //$typecontrat = "'$typecontrat'";
      $idtypecontrat = R::getAll('select idtype from typecontrat where typecontrat.libelle='."'$typecontrat'".'');
      //var_dump($idtypecontrat);
      $idtypecontrat = (int)$idtypecontrat[0]['idtype'];
      //var_dump($idtypecontrat);
      //$idtypecontrat = $idtypecontrat[0];
      $salaire = $consultant->getsalaire();
      $tarif = $consultant->gettarif();

      $leconsultant = R::load('utilisateur',$idconsultant); //chargement d'un objet utilisateur
      $leconsultant->nom = $nom; // on lui donne les champs
      $leconsultant->prenom = $prenom;
      $leconsultant->tel=$tel;
      $leconsultant->email=$email;
      $leconsultant->adresse=$adr;
      $leconsultant->ville=$ville;
      $leconsultant->cp=$cp;
      //if(isset($_POST['typecontrat'])){$typecontrat = $_POST['typecontrat'];}else{$typecontrat  = "";}
      //if(isset($_POST['typecontrat']) == "salarié"){$typecontrat = "salarie"; } else {$typecontrat = "soustraitant";}


      $idC = r::getAll("SELECT id FROM utilisateur WHERE id = (SELECT MAX(id) FROM utilisateur)");
      $id = $idC[0]["id"];
      r::getAll("update consultant set salaire=$salaire,tarif=$tarif,idtypecontrat=$idtypecontrat WHERE idutilisateur=$idconsultant");
      R::store($leconsultant);
    }


    public function suppconsultant($idconsultant){
      //$idconsultant = $this->getIdConsultantFromobject($consultant);
      $consultant = R::load('utilisateur',$idconsultant);
      r::getAll("update consultant set cacher=true where idutilisateur=$idconsultant");
      r::store($consultant);
    }


}
?>
