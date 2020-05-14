<?php

//include("vues/v_entete.php");

if (!isset($_REQUEST['action'])){
	$_REQUEST['action']="afficherConsultant";
}
$action = $_REQUEST['action'];


switch($action){
    case 'afficherConsultant':{
      $lesConsultants = $UconsultantDao->collectionconsultant();
	    include ('vues/v_consultant.php');
	break;
    }


		case 'afficherCraConsultant': {
			$contrats = $daoTrz_Contrat->getAllContratFromIdUser($_SESSION['id_user']);
			include ("vues/v_choixCraConsultant.php");
		break;
		}


    case 'ajouterconsultant':{
        require_once 'vues/v_ajconsultant.php';
      break;
    }
    case 'validajoutconsultant':{
        if(isset($_POST['nom'])){$nom = $_POST['nom'];} else {$nom="";}
        if(isset($_POST['prenom'])){$prenom = $_POST['prenom'];}else {$prenom="";}
        if(isset($_POST['adr'])){$adr = $_POST['adr'];}else {$adr="";}
        if(isset($_POST['ville'])){$ville = $_POST['ville'];}else {$ville="";}
        if(isset($_POST['cp'])){$cp = $_POST['cp'];}else {$cp="";}
        if(isset($_POST['tel'])){$tel = $_POST['tel'];}else {$tel="";}
        if(isset($_POST['email'])){$email = $_POST['email'];}else {$email="";}
        if(isset($_POST['tarif'])){$tarif  = $_POST['tarif']; } else {$tarif  = "";}
				if(isset($_POST['typecontrat'])){$typecontrat = $_POST['typecontrat'];}else{$typecontrat  = "";}

        $objconsultant = new trz_consultant ($nom, $prenom, $adr, $ville, $cp, $tel, $email, "'$typecontrat'", $tarif);
        $UconsultantDao->add($objconsultant);
        header('location:index.php?uc=consultant&action=afficherConsultant');
        break;
    }
    case 'modifconsultant':{
				$_consultant=explode(",",$_REQUEST["tableau"]);
				$idconsultant=$_GET['idConsultant'];
				$nom=$_consultant[0];
				$prenom=$_consultant[1];
				$adresse=$_consultant[2];
				$ville=$_consultant[3];
				$cp=$_consultant[4];
				$tel=$_consultant[5];
        $email=$_consultant[6];
				$typecontrat=$_consultant[7];
				$tarif=$_consultant[8];

				if(empty($cp)){ $cp='00000';}
				if(empty($tel)){ $tel='0000000000';}
				if(empty($tarif)){$tarif=0;}


				$objconsultant = new trz_consultant ($nom, $prenom, $adresse, $ville, $cp, $tel, $email, $typecontrat, $tarif);
				$UconsultantDao->updateUser($objconsultant,$_GET['idConsultant']);

        header('location:index.php?uc=consultant&action=afficherConsultant');
				break;
    }
    case 'suppconsultant':{
				$idconsultant = $_GET['idconsultant'];
				$UconsultantDao->suppconsultant($idconsultant);

				header('location:index.php?uc=consultant&action=afficherConsultant');
        break;
    }

		default :{
				include("vues/v_consultant.php");
				break;
		}

}
