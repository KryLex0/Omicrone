<?php
include("session/verif_session.php");


if(!isset($_REQUEST['action'])){
	$_REQUEST['action'] = 'affichercontrat';
}
$action = $_REQUEST['action'];
switch($action){
	case 'affichercontrat':{
        $lesContrats = $daoTrz_Contrat->getlistecontrat();
        $lesClients =$clientDao->selectClients();
        $lesConsultants = $UconsultantDao->getconsultant();
        include("vues/v_contrat.php");
        break;
	}

        case 'ajoutC':{
            $lesClients =$clientDao->selectClients();
            $lesConsultants = $UconsultantDao->getconsultant();
            include("vues/v_ajContrat.php");
            break;
        }

        case 'validAjoutC':{
            $lesClients=$clientDao->selectClients();
            $lesConsultants = $UconsultantDao->getconsultant();
            if(isset($_POST['lesClients'])){$_SESSION['id'] = $_POST['lesClients'];}
            if (isset($_SESSION['id'])) {$idclient = $_SESSION['id'];}
            else {$idclient = "" ;}

            if(isset($_POST['lesConsultants'])){$_SESSION['id'] = $_POST['lesConsultants'];}
             if (isset($_SESSION['id'])) {$idconsultant = $_SESSION['id'];}
            else {$idconsultant = "" ;}

            if(isset($_POST['datedebut'])){$datedebut = $_POST['datedebut'];}
            else {}
            if(isset($_POST['datefin'])){$datefin = $_POST['datefin'];}
						else {}
            if(isset($_POST['mission'])){$mission = $_POST['mission'];}
            else{if(empty($mission)){$mission = "Non communiqué";}}
						if(isset($_POST['salaire'])){$salaire = $_POST['salaire'];}
						else {}


            //verifier si les champs sont vide
            if(empty($datedebut) OR empty($datefin)){
                $lesClients =$clientDao->selectClients();
                $lesConsultants = $UconsultantDao->getconsultant();
            }
            $dernieridcontrat = $daoTrz_Contrat->getdernierid();
            $dernieridcontrat++;
            for ($i=0;$i<=$dernieridcontrat;$i++){
                $objcontrat = new trz_contrat($i, $idclient, $idconsultant, $datedebut, $datefin, $mission, $salaire);
            }
            if($datedebut > $datefin){ ?>
              <script>
            		alert ('Date de début ne doit pas excéder la date de fin');
                window.location.href="index.php?uc=contrat&action=affichercontrat#open-modal"
							</script><?php
            }else {
            header('location:index.php?uc=contrat&action=affichercontrat');
						$daoTrz_Contrat->insertcontrat($objcontrat);
         }
            break;
        }

        case 'modifcontrat':{
            $lecontrat = explode(",",$_GET["tableau"]);
						if(empty($lecontrat[2])){$lecontrat[2] = 'Non communiqué';}
						$daoTrz_Contrat->setcontrat($_GET["idcontrat"], $lecontrat[0] , $lecontrat[1], $lecontrat[2], $lecontrat[3]);
						header('location:index.php?uc=contrat&action=affichercontrat');
           	break;
        }

        case 'suppcontrat':{
            $idContrat = $_GET['idcontrat'];
            $daoTrz_Contrat->suppContrat($idContrat);
            header('location:index.php?uc=contrat&action=affichercontrat');
            break;
        }
	default :{
            include("vues/v_contrat.php");
            break;
	}
}
?>
