<?php
if(!isset($_REQUEST['action'])){
	$_REQUEST['action'] = 'afficherfacture';
}
$action = $_REQUEST['action'];

switch($action){

    case 'choixFacture': {
			if(isset($_POST['idContrat'])){
				$idContrat = $_POST['idContrat'];
			}else{
				include('session/verif_session.php');		//empeche de voir les factures d'autre consultant en modifiant le parametre "idContrat" dans l'url
				$idContrat = $_GET['idcontrat'];
			}
      $lecontrat = $daoTrz_Contrat->getcontrat($idContrat);
			$lesMois = $factureDao->getdatefacture($idContrat);
      include 'vues/v_choixfacture.php';
    break;
    }






    case 'afficherfacture':{
				$idContrat = $_GET['idcontrat'];
				$lecontrat = $daoTrz_Contrat->getcontrat($idContrat);

				if($_SESSION['role_user'] == 'Consultant'){
					$ligne = $_GET['ligne'];
					$idContrat = $_SESSION['contrats'][$ligne]['idcontrat'];
					$dateTemp = $_SESSION['contrats'][$ligne]['date_min'];
					list($annee, $mois, $jour) = explode('-', $dateTemp);
					$leMois = $mois . $annee;
				}else{	//role = admin -> on récupère le mois selectionné dans le select
					$leMois = $_POST['mois'];
				}

        $m = substr($leMois, 0, 2);
        $a = substr($leMois, 2, 4);

        if ($factureDao->factureexists($idContrat, $leMois) == 0){
            $lesMois = $factureDao->getdatefacture($idContrat);
            include 'vues/v_choixfacture.php'; //choix facture
            include 'vues/v_erreurs.php'; //sinon afficher une vue erreur
        }

        else {
            $uncontrat = $daoTrz_Contrat->getobjcontrat($idContrat);
            $unefacture = $factureDao->getfacture($leMois, $idContrat, $uncontrat);
            $periode = $unefacture->getclecra()->getPeriode();
            $mois = substr($periode, 0, 2);
            $annee = substr($periode, 2, 4);
            $JF = $craDAO->getJFfromidcontrat($idContrat, $leMois);

						$num_facture = $factureDao->getIdCraFromIdcontrat($idContrat,$leMois);
						$num_facture = $num_facture[0]['id'];

            require_once 'vues/v_lafacture.php';
        }
    break;
    }

}
