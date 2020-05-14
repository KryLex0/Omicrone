<?php

class trz_commerciauxDao {


    public function add($commercial){

        $nom= $commercial->getNom();
        $prenom= $commercial->getPrenom();
        $tel= $commercial->getTel();
        $email= $commercial->getEmail();
        $adresse=$commercial->getAdresse();
        $ville=$commercial->getVille();
        $cp=$commercial->getCp();

        $commercial = R::dispense('trz_omicrone_user'); // on crée un commercial

        $commercial->nom_user = $nom; // on lui donne les champs
        $commercial->prenom_user = $prenom;
        $commercial->tel_user=$tel;
        $commercial->mail_user=$email;
        $commercial->adresse_user=$adresse;
        $commercial->ville_user=$ville;
        $commercial->cp_user=$cp;
        R::store($commercial); // on le sauvegarde en BDD
        $idC = r::getAll("SELECT id FROM trz_omicrone_user WHERE id = (SELECT MAX(id) FROM trz_omicrone_user)");
        $id = $idC[0]["id"];
        r::getAll("insert into trz_commercial values ($id, 'FALSE')");

        //ajout role 'Commercial' en fonction de l'id du commercial ajouté
        R::getAll("insert into trz_role_omicrone_user values('$id',0)");

        $idrole = R::getAll("select id from trz_role where name='Commercial'");
        $idrole = $idrole[0]['id'];
        R::getAll("update trz_role_omicrone_user set id_role='$idrole' where id_user='$id'");
    }
    public function getCommerciaux()/*retourne une collection de commerciall*/
        {
             // $limit=0;
            $lesComm=array();
            $les = r::getAll('select nom_user, prenom_user, tel_user, mail_user, adresse_user, ville_user, cp_user from trz_omicrone_user join trz_commercial on trz_omicrone_user.id=trz_commercial.idutilisateur where cacher = false order by id desc');
       // $les = R::find('commerciall'/*,'limit 5 offset 5'*/,"order by id desc");
            foreach ($les as $depe){
                $comm=new trz_commercial($depe["nom_user"],$depe["prenom_user"],$depe["adresse_user"],$depe["ville_user"],$depe["cp_user"],$depe["tel_user"], $depe["mail_user"]);
                $lesComm[]=$comm;
        }
            return($lesComm);
        }

       public function getIdCommercial($commercial){

        $nom= $commercial->getNom();
        $prenom= $commercial->getPrenom();
        $tel= $commercial->getTel();
        $email= $commercial->getEmail();
        $adresse=$commercial->getAdresse();
        $ville=$commercial->getVille();
        $cp=$commercial->getCp();

        $id=r::getAll("select idutilisateur from trz_commercial join trz_omicrone_user on trz_commercial.idutilisateur = trz_omicrone_user.id where nom_user = '$nom' and prenom_user = '$prenom' and adresse_user = '$adresse' and ville_user = '$ville' and cp_user = $cp and tel_user = $tel and mail_user = '$email'");

            return($id[0]["idutilisateur"]);

        }

        public function getCommercial($idC){

           $res = R::getAll("select nom_user, prenom_user, tel_user, mail_user, adresse_user, ville_user, cp_user, codeagence ,compte ,iban ,bic, codebanque,clerib
           from trz_omicrone_user join trz_commercial on trz_omicrone_user.id=trz_commercial.idutilisateur left join trz_infob on trz_commercial.idutilisateur=trz_infob.idcommercial where trz_commercial.idutilisateur=$idC");

            foreach($res as $resu){
            $comm=new trz_commercial($resu["nom_user"],$resu["prenom_user"],$resu["adresse_user"],$resu["ville_user"],$resu["cp_user"],$resu["tel_user"],$resu["mail_user"]);

            $fin=new information_bancaire(NULL,$comm,$resu["codeagence"],$resu["compte"],$resu["iban"],
                 $resu["bic"],$resu["codebanque"],$resu["clerib"]);
             return($fin);
            }
        }

        public function update($commercial,$idC){

            $nom = $commercial->getNom();
            $prenom = $commercial->getPrenom();
            $tel = $commercial->getTel();
            $email = $commercial->getEmail();
            $adresse =$commercial->getAdresse();
            $ville = $commercial->getVille();
            $cp = $commercial->getCp();

            $commercial=r::load('trz_omicrone_user',$idC);// on recupere le commercial
            $commercial->nom_user = $nom;
            $commercial->prenom_user = $prenom;
            $commercial->tel_user=$tel;
            $commercial->mail_user=$email;
            $commercial->adresse_user=$adresse;
            $commercial->ville_user=$ville;
            $commercial->cp_user=$cp;
            R::store($commercial); // on le sauvegarde en BDD
            $vraiComm = r::getAll("update trz_commercial set cacher = false where idutilisateur = $idC");


            }
            public function nbLigne(){
                $nb = r::getAll('select count(*)from trz_commercial');
                return($nb[0]["count"]);
            }

            public function delete($commercial){
            $id=$this->getIdCommercial($commercial);
            r::getAll("update trz_commercial set cacher = true where idutilisateur = $id");



        }
}
