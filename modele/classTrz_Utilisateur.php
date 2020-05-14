<?php
//session_start();


class trz_utilisateur {
    private $id;
    private $nom_user;
    private $prenom_user;
    private $tel_user;
    private $mail_user;
    private $adresse_user;
    private $ville_user;
    private $cp_user;

    public function __construct($unNom,$unPrenom,$uneAdresse,$uneVille,$unCp,$unTel,$unEmail)
    {
        $this->nom_user = $unNom;
        $this->prenom_user=$unPrenom;
        $this->adresse_user=$uneAdresse;
        $this->ville_user=$uneVille;
        $this->cp_user=$unCp;
        $this->tel_user=$unTel;
        $this->mail_user=$unEmail;
    }

    public function getId(){
        return $this->id;
    }
    public function getNom(){
        return($this->nom_user);
    }
    public function getPrenom(){
        return($this->prenom_user);
    }
    public function getAdresse(){
        return($this->adresse_user);
    }
    public function getVille(){
        return($this->ville_user);
    }
    public function getCp(){
        return($this->cp_user);
    }
    public function getTel(){
        return($this->tel_user);
    }
    public function getEmail(){
        return($this->mail_user);
    }
}

class trz_consultant extends trz_utilisateur {
     private $typecontrat;
     //private $salaire;
     private $tarif;

     public function __construct($unNom,$unPrenom,$uneAdresse,$uneVille,$unCp,$unTel, $unEmail, $untypecontrat, $unTarif){
        parent::__construct($unNom,$unPrenom,$uneAdresse,$uneVille,$unCp,$unTel, $unEmail);
        $this->typecontrat = $untypecontrat;
        //$this->salaire = $unSalaire;
        $this->tarif = $unTarif;
     }

     public function gettypecontrat(){
         return($this->typecontrat);
     }
/*
     public function getsalaire(){
         return($this->salaire);
     }
*/
     public function gettarif(){
         return($this->tarif);
     }
}

class trz_commercial extends trz_utilisateur {
    public function __construct($unNom,$unPrenom,$uneAdresse,$uneVille,$unCp,$unTel, $unEmail){
        parent::__construct($unNom,$unPrenom,$uneAdresse,$uneVille,$unCp,$unTel, $unEmail);
    }

}


class trz_utilisateurDao {
    public function getiduser($utilisateur){
        $nom= $utilisateur->getNom();
        $prenom= $utilisateur->getPrenom();
        $adr=$utilisateur->getAdresse();
        $ville=$utilisateur->getVille();
        $cp=$utilisateur->getCp();
        $tel= $utilisateur->getTel();
        $email= $utilisateur->getEmail();

        $id = r::find('trz_omicrone_user', 'nom_user = ? and prenom_user = ? and adresse_user = ? and ville_user = ? and cp_user = ? and tel_user = ? and mail_user = ? ',
        array($nom,$prenom,$adr,$ville,$cp,$tel,$email));
         foreach($id as $unid){
            return($unid->id);
        }
    }

    public function getInfoUser($login, $password){
      $test = R::getAll('select * from trz_omicrone_user where username=\''.$login.'\' and password=\''.$password.'\'');
      //var_dump($test);
      return $test;
    }
    public function isInDB($login, $password){
      $test = R::getAll('select COUNT(1) from trz_omicrone_user where username=\''.$login.'\' and password=\''.$password.'\'');
      //var_dump();
      //var_dump($test);
      if($test[0]['count'] == 1){
        return true;
      }else{
        return false;
      }
    }
    public function getRoleUser($idutilisateur){
      $test = R::getAll("select name from trz_role join trz_role_omicrone_user on trz_role.id=trz_role_omicrone_user.id_role where trz_role_omicrone_user.id_user='$idutilisateur'");
      //var_dump($test);
      return $test;
    }



}
class UconsultantDao  {

  public function ajoutRoleConsultant(){
    //ajout role 'Commercial' en fonction de l'id du commercial ajouté
    $idC = r::getAll("SELECT id FROM trz_omicrone_user WHERE id = (SELECT MAX(id) FROM trz_omicrone_user)");
    $id = $idC[0]["id"];
    //var_dump('test');
    R::getAll("insert into trz_role_omicrone_user values('$id',0)");

    $idrole = R::getAll("select id from trz_role where name='Consultant'");
    $idrole = $idrole[0]['id'];
    R::getAll("update trz_role_omicrone_user set id_role='$idrole' where id_user='$id'");
  }

  public function add($consultant){
        $nom= $consultant->getNom();
        $prenom= $consultant->getPrenom();
        $tel= $consultant->getTel();
        $email= $consultant->getEmail();
        $adresse=$consultant->getAdresse();
        $ville=$consultant->getVille();
        $cp=$consultant->getCp();
        $typecontrat = $consultant->gettypecontrat();
        $idtypecontrat = R::getAll('select idtype from trz_typecontrat where trz_typecontrat.libelle='.$typecontrat.'');
        $idtypecontrat = (int)$idtypecontrat[0]['idtype'];
        //$salaire = $consultant->getsalaire();
        $tarif = $consultant->gettarif();

        //var_dump($idtypecontrat);


        $consultant = R::dispense('trz_omicrone_user'); // on crée un commercial

        $consultant->nom_user = $nom; // on lui donne les champs
        $consultant->prenom_user = $prenom;
        $consultant->tel_user=$tel;
        $consultant->mail_user=$email;
        $consultant->adresse_user=$adresse;
        $consultant->ville_user=$ville;
        $consultant->cp_user=$cp;
        $consultant->username = strtolower($nom);
        $consultant->password = strtolower($prenom);
        R::store($consultant); // on le sauvegarde en BDD
        $idC = r::getAll("SELECT id FROM trz_omicrone_user WHERE id = (SELECT MAX(id) FROM trz_omicrone_user)");
        $id = $idC[0]["id"];
        r::getAll("insert into trz_consultant values ($id,$tarif, false, $idtypecontrat)");        //A REVOIR!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
        $this->ajoutRoleConsultant();
    }
    public function collectionconsultant(){ //collection de consultant

        $collection = array();
        $lesconsultants = r::getAll('select nom_user, prenom_user, adresse_user, ville_user, cp_user, tel_user, mail_user, libelle, tarif
         from trz_omicrone_user join trz_consultant on trz_omicrone_user.id=trz_consultant.idutilisateur join trz_typecontrat on trz_consultant.idtypecontrat=trz_typecontrat.idtype where cacher=false order by id desc');
        //print_r($lesconsultants);
        foreach($lesconsultants as $unconsultant){
            $unconsultant = new trz_consultant ($unconsultant['nom_user'], $unconsultant['prenom_user'], $unconsultant['adresse_user'], $unconsultant['ville_user'],
            $unconsultant['cp_user'], $unconsultant['tel_user'], $unconsultant['mail_user'], $unconsultant['libelle'], $unconsultant['tarif']);
            $collection[] = $unconsultant;
        }
        return $collection;
    }

    public function getconsultant(){ //retour la liste des noms et prenom des consultants
        $consultant = r::getAll('select id, nom_user, prenom_user from trz_omicrone_user join trz_consultant on trz_omicrone_user.id=trz_consultant.idutilisateur');
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
        $typecontrat = $consultant->gettypecontrat();

        $idtypecontrat = R::getAll("select idtype from trz_typecontrat where trz_typecontrat.libelle='$typecontrat'");

        $idtypecontrat = (int)$idtypecontrat[0]['idtype'];

        //$salaire = $consultant->getsalaire();
        $tarif = $consultant->gettarif();


        $id = r::getAll("select trz_consultant.idutilisateur as idu, trz_typecontrat.libelle as libelle from trz_omicrone_user join trz_consultant on trz_omicrone_user.id=trz_consultant.idutilisateur join trz_typecontrat on trz_consultant.idtypecontrat=trz_typecontrat.idtype where nom_user='$nom' and prenom_user='$prenom' and adresse_user='$adr'
         and ville_user='$ville' and cp_user='$cp' and tel_user='$tel' and mail_user='$email' and idtypecontrat='$idtypecontrat' and tarif='$tarif'");

        return $id[0]['idu'];
    }

    public function getConsultantfromId($idC){ //retourne l'objet en fonction de son id
        $req = R::getAll('select nom_user, prenom_user, adresse_user, ville_user, cp_user, tel_user, mail_user, trz_typecontrat.libelle as libelle, tarif from trz_omicrone_user join trz_consultant on trz_omicrone_user.id=trz_consultant.idutilisateur join trz_typecontrat on trz_consultant.idtypecontrat=trz_typecontrat.idtype where trz_consultant.idutilisateur='.$idC.'');
        //var_dump($req);
        foreach($req as $laligne){
        $consultant=new trz_consultant($laligne['nom_user'],$laligne['prenom_user'],$laligne['adresse_user'],$laligne['ville_user'],$laligne['cp_user'],$laligne['tel_user'],$laligne['mail_user'], $laligne['libelle'], $laligne['tarif']);
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
      var_dump($typecontrat);
      $idtypecontrat = R::getAll('select idtype from trz_typecontrat where trz_typecontrat.libelle='."'$typecontrat'".'');

      $idtypecontrat = (int)$idtypecontrat[0]['idtype'];
      //var_dump($idtypecontrat);
      //$idtypecontrat = $idtypecontrat[0];
      //$salaire = $consultant->getsalaire();
      $tarif = $consultant->gettarif();

      $leconsultant = R::load('trz_omicrone_user',$idconsultant); //chargement d'un objet utilisateur
      $leconsultant->nom_user = $nom; // on lui donne les champs
      $leconsultant->prenom_user = $prenom;
      $leconsultant->tel_user=$tel;
      $leconsultant->mail_user=$email;
      $leconsultant->adresse_user=$adr;
      $leconsultant->ville_user=$ville;
      $leconsultant->cp_user=$cp;
      //if(isset($_POST['typecontrat'])){$typecontrat = $_POST['typecontrat'];}else{$typecontrat  = "";}
      //if(isset($_POST['typecontrat']) == "salarié"){$typecontrat = "salarie"; } else {$typecontrat = "soustraitant";}


      $idC = r::getAll("SELECT id FROM trz_omicrone_user WHERE id = (SELECT MAX(id) FROM trz_omicrone_user)");
      $id = $idC[0]["id"];
      r::getAll("update trz_consultant set tarif=$tarif,idtypecontrat=$idtypecontrat WHERE idutilisateur=$idconsultant");
      R::store($leconsultant);
    }


    public function suppconsultant($idconsultant){
      //$idconsultant = $this->getIdConsultantFromobject($consultant);
      $consultant = R::load('trz_omicrone_user',$idconsultant);
      r::getAll("update trz_consultant set cacher=true where idutilisateur=$idconsultant");
      r::store($consultant);
    }


}
?>
