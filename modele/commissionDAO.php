<?php

class commissionDAO {


    public function add($uneCommission,$valeur){
        $commDAO=new trz_commerciauxDao;
//        $commercial=$uneCommission->getOCommercial();//->getOCommercial();
        $commercial = ($commDAO->getCommercial($_POST['idCommercial'])->getOCommercial());
        $idcommercial= $_POST['idCommercial'];//$commDAO->getIdCommercial($commercial);
        //var_dump($commercial);
        //var_dump($idcommercial);

        $ocommercial=r::dispense('commission');
        //var_dump($ocommercial);
        //$infos1comm = r::getAll("select trz_omicrone_user.nom_user, trz_omicrone_user.prenom_user from trz_omicrone_user where trz_omicrone_user.id='$idcommercial'")
        $ocommercial->idcommercial=$idcommercial;

        //var_dump($ocommercial);
        r::store($ocommercial);


    //    $id = r::find("commission","idcommercial = ?", array($idcommercial));
    //     foreach($id as $unid){
    //         $idC=$unid->id;  }

        $id = r::getAll("SELECT id FROM commission WHERE id = (SELECT MAX(id) FROM commission)");
        $idC = $id[0]["id"];
        //r::getAll("INSERT INTO commission VALUES (1,$idcommercial,'FALSE')");
//var_dump($id);
        if($valeur["heri"]=="pourcentage"){
          $v = $valeur['pourcentage'];
          r::exec("insert into pourcentage(idcommission,valeur) values ($idC,$v)"); //exec
          //r::exec("insert into oneshot(idcommission,montant) value ($idC,0)");
        }
        else{
          $m = $valeur["montant"];
          r::exec("insert into oneshot(idcommission,montant) values ($idC,$m)");
          //r::exec("insert into pourcentage(idcommission,valeur) values ($idC,0)");
        }
        $idContrat=$valeur["idContrat"];
        r::exec("insert into prendre (idcontrat,idcommission) values (".$idContrat.",".$idC.")");

    }

    public function getCommissions(){
            $dao=new trz_commerciauxDao;

            $lesComm=array();
            $lesCommissions=r::getAll("select commission.id,idcommercial, montant, valeur from
            commission left join oneshot on commission.id=oneshot.idcommission
            left join pourcentage on commission.id=pourcentage.idcommission where commission.cacher = false order by id desc");

            foreach($lesCommissions as $uneCommission){
               $commission=new commission($dao->getCommercial($uneCommission['idcommercial'])->getOCommercial());
               $one_shot=new one_shot($uneCommission['montant'],$commission->getOCommercial());
               $pourcentage=new pourcentage($uneCommission['valeur'],$commission->getOCommercial());

                $lesComm[]=$uneCommission["id"];
                $lesComm[]=$commission;
                $lesComm[]=$one_shot;
                $lesComm[]=$pourcentage;

            }

            return($lesComm);

   }
    public function getLaCommission($id){
        $dao=new trz_commerciauxDao;

            $lesComm=array();
            $lesCommissions=r::getAll("select commission.id,idcommercial, montant, valeur from
            commission full join oneshot on commission.id=oneshot.idcommission
            full join pourcentage on commission.id=pourcentage.idcommission where commission.id=$id");

            foreach($lesCommissions as $uneCommission){
               $commission=new commission($dao->getCommercial($uneCommission['idcommercial'])->getOCommercial());
               $one_shot=new one_shot($uneCommission['montant'],$commission->getOCommercial());
               $pourcentage=new pourcentage($uneCommission['valeur'],$commission->getOCommercial());

                $lesComm[]=$uneCommission["id"];
                $lesComm[]=$commission;
                $lesComm[]=$one_shot;
                $lesComm[]=$pourcentage;
                var_dump($lesComm);
                return $lesComm;
                }
    }

   public function update($commission,$idCommission){

        if(method_exists($commission ,"getValeur")){
             $valeur=$commission->getValeur();
            // $pourcentage=r::load("pourcentage",$idCommission);

            r::getAll("update pourcentage set valeur = $valeur where idcommission = $idCommission");
        }
        else {
            $montant=$commission->getMontant();
                //$valeur=$commission->getValeur();
            // $one_shot=r::load("oneshot",$idCommission);
            // $one_shot->montant=$montant;
            // r::store($one_shot);
            r::getAll("update oneshot set montant = $montant where idcommission = $idCommission");
        }

    }

    public function delete($id){
        // $pourcentage=r::load('pourcentage',$id);
        // r::trash($pourcentage);
        // $one_shot=r::load('one_shot',$id);
        // r::trash($one_shot);
        $commission=r::load('commission',$id);
        $commission->cacher=true;
        r::store($commission);
        // $prendre = r::find("prendre","idcommission = ?", array($id));
        // r::trash($prendre);
        }
}
