<?php
include("session/verif_session.php");

if (!isset($_REQUEST['action'])){
	$_REQUEST['action']=NULL;
}
$action = $_REQUEST['action'];
$commissionDAO= new trz_commissionDAO;
switch($action){

	case 'ajouterCommission':{
		//var_dump($_POST);
		$idContrat=$_POST["idContrat"];
		$commission=new trz_commission($_POST["idCommercial"]);
		//if($_POST['heri'] == "montant"){
			$commissionDAO->add($commission,$_POST);//$_POST['montant']);
		//}else{
			//$commissionDAO->add($commission,$_POST['pourcentage']);
		//}
		header('location:index.php?uc=commission&action=afficherCommission');
		break;
	}

	case 'afficherCommission':{
		$lesCommerciaux=$commerciauxDao->getCommerciaux();
		//var_dump($lesCommerciaux);
		$lesCommissions=$commissionDAO->getCommissions();
		//var_dump($lesCommissions);
		$lesContrats=$daoTrz_Contrat->collectioncontrat();
		//var_dump($lesContrats);
		include("vues/v_tableauCommission.php");
		break;
	}

	case 'updateCommission': {

		$_REQUEST["heri"]=explode(",",$_REQUEST["tableau"]);
		if($_REQUEST["heri"][0]==''){$_REQUEST["heri"][0]="Null";}
		$commercial=$commerciauxDao->getCommercial($_REQUEST["idCommercial"])->getOCommercial();
		if(!stristr($_REQUEST["heri"][0],"Null")){
			$commission=new one_shot($_REQUEST["heri"][0],$commercial);
			$commissionDAO->update($commission,$_REQUEST["idCommission"]);
		}
		else{
			$commission=new trz_pourcentage($_REQUEST["heri"][1],$commercial);
			$commissionDAO->update($commission,$_REQUEST["idCommission"]);
		}

		header('location:index.php?uc=commission&action=afficherCommission');
		break;
	}
	case 'deleteCommission': {
		$commissionDAO->delete($_REQUEST["idCommission"]);
		header('location:index.php?uc=commission&action=afficherCommission');
	}
}
