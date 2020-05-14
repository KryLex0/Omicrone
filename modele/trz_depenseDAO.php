<?php

class trz_depenseDAO{


public function add($depense){

    $libelle=$depense->getLibelle();
    $montant=$depense->getMontant();
    $depense = R::dispense('trz_depense'); // on crée une depense
    $depense->montant_depense = $montant; // on lui donne les champs
    $depense->nom_depense = $libelle;
    R::store($depense); // on le sauvegarde en BDD

}

public function getDepenses($limit)/*retourne une collection de depense*/

        {
             $cacher=false;
            if($limit!=0){
                $limit=$limit*5;
            }

            $lesDep=array();
           // $les = R::find('depense','cacher = false','order by id DESC limit 5 offset '.$limit.'');
           $les = r::getAll('select id, montant_depense,nom_depense from trz_depense where cacher=false order by id desc');
            foreach ($les as $depe){

               $dep=new trz_depense($depe["montant_depense"],$depe["nom_depense"]);
                $lesDep[]=$dep;
        }
           return($lesDep);


        }

    public function getIdDepense($depense){

        $montant=$depense->getMontant();
        $libelle=$depense->getLibelle();

        $id = R::find("trz_depense","nom_depense = ?
        and montant_depense = ?",
        array( $libelle, $montant));

        foreach($id as $unid){
            return($unid->id);
        }

            }

    public function getDepense($idD)/* retourne l'objet depense par rapport a l'id*/{

       $depense = r::load('trz_depense',$idD);
       $dep=new trz_depense($depense->montant_depense,$depense->nom_depense);
       return($dep);

            }
    public function nbLigne(){
          $nb=  r::getAll('select count(*)from trz_depense where cacher = false');
          return($nb[0]["count"]);
    }


    public function update($depense,$idD){

        $montant=$depense->getMontant();
        $libelle=$depense->getLibelle();
        $depense=r::load('trz_depense',$idD);
        $depense->montant_depense=$montant;
        $depense->nom_depense=$libelle;
        $depense->cacher=false;
        r::store($depense);
                }

    public function delete($depense){
        $id=$this->getIdDepense($depense);
        $depense=r::load('trz_depense',$id);
        $depense->cacher=true;
        r::store($depense);

                }
}
