<?php
include("session/verif_session.php");

if (!isset($_REQUEST['action'])){
	$_REQUEST['action']=NULL;
}
if(!isset($_REQUEST["limit"])){
	$limit=0;
}
else{
	$limit=$_REQUEST["limit"];
}
$action = $_REQUEST['action'];
$depenseDao=new trz_depenseDAO();

switch($action){

	case 'ajouterDepense':{

        if(isset($_POST["envoyer"])){
            $depense= new trz_depense($_POST["montant"],$_POST["libelle"]);
            $depenseDao->add($depense);

			}
			header('location:index.php?uc=depense&action=afficherDepense');
		break;
	}

	case 'afficherDepense':{

        $lesDepenses = $depenseDao->getDepenses($limit);

		include("vues/v_tableauDepenses.php");
		break;
	}

	case 'updateDepense': {
		$_REQUEST["tab"]=explode(",",$_REQUEST["tableau"]);
		$montant=$_REQUEST["tab"][0];
		$libelle=$_REQUEST["tab"][1];

		if($montant==''){$montant=0;}
		$lesDepenses = $depenseDao->getDepenses($limit);
		$depense=$depenseDao->getDepense($_REQUEST["idDepense"]);

				$depense=new trz_depense($montant,$libelle);
				$depenseDao->update($depense,$_REQUEST["idDepense"]);

				header('location:index.php?uc=depense&action=afficherDepense');

		break;
	}
	case 'deleteDepense': {

	$depenseDao->delete($depenseDao->getDepense($_REQUEST["idDepense"]));
	header('location: index.php?uc=depense&action=afficherDepense');

	}
}
