<?php
require_once __DIR__ . '/../vendor/autoload.php';


if (!isset($_REQUEST['action'])){
	$_REQUEST['action']=NULL;
}
$action = $_REQUEST['action'];
switch($action){

	case 'choisirCra':{
		$lesContrats=$daoTrz_Contrat->collectioncontrat();
		include ("vues/v_choixCra.php");

		break;
	}

	case 'envoiDemandeCraConsultant':{
		$date = $_POST['extra'];
		$craDAO->envoiDemandeCraConsultant($date);
		?>
      <script>
        alert("Les demandes de CRA ont été envoyés");
        window.location.href='index.php?uc=cra&action=choisirCra';
      </script>
    <?php
		break;
	}

	case 'afficherCra':{
		$num_contrat = $_GET['ligne'];
		$idcontrat = $_GET['idcontrat'];
		$dateTemp = $_GET['dateDebut'];

		$consultant=$daoTrz_Contrat->getobjcontrat($idcontrat)->getcleconsultant();

		list($jour1, $mois1, $annee1) = explode('/', $dateTemp);

		$mois=substr($mois1,0,2);
		$annee=substr($annee1,3,4);

		$mois=substr($mois1,0,2);
		$annee=substr($annee1,0,7);

		$number = cal_days_in_month(CAL_GREGORIAN, (int)$mois, (int)$annee);
		include("vues/v_cra.php");
		break;
	}

	case 'createCRA': {
		$lecontrat=$daoTrz_Contrat->getobjcontrat($_GET["idcontrat"]);

		$idConsultant = $_POST['idConsultant'];
		$consultant=$UconsultantDao->getConsultantfromId($idConsultant);
		$nomClient=$lecontrat->getcleclient()->getraison_social_client();

		$TJF=0;
		$TJM=0;
		$TJC=0;


		foreach($_POST["facturable"] as $nb){
			$TJF=$TJF+$nb;
		}

		foreach($_POST["maladie"] as $nb){
			$TJM=$TJM+$nb;
		}

		foreach($_POST["conger"] as $nb){
			$TJC=$TJC+$nb;
		}




		echo 'Création du CRA réussi. </br>';
		echo "tjf : ".$TJF." tjm : ".$TJM." tjc : ".$TJC." ";

		 $periode = strtolower($_POST["mois"].$_POST["annee"]);
		 //echo $periode;
		 $interv = $_POST['interv'];

		$cra=new trz_cra($TJF,$TJM,$TJC,$_POST["astreinte"],$lecontrat,$periode,$interv);
		$chemin = ('CRA/' . $lecontrat->getcleconsultant()->getNom() . '/' . 'CRA_'.getMoisFr($_POST["mois"]).'_'.$_POST["annee"].'.pdf');// chemin ou stocker le fichier du CRA
		//var_dump($cra, $chemin);
		if($craDAO->craexists($cra,$chemin) == 0){
			$craDAO->add($cra);
			$craDAO->insererCra($cra,$chemin);
		}else{
			?>
	      <script>
	        alert("Ce CRA existe déjà.");
	        window.location.href='index.php?uc=consultant&action=afficherCraConsultant';
	      </script>
	    <?php
		}

		//création de la facture pour le mois donné

		if ($factureDao->factureexists($_GET["idcontrat"],$periode) == 0){
			$idcra = $craDAO->idcrafromobject($cra);
			$objclient = new trz_client ($lecontrat->getcleclient()->getraison_social_client(),$lecontrat->getcleclient()->getclecontact() , $lecontrat->getcleclient()->getsiret_client(),$lecontrat->getcleclient()->getadresse_client(),$lecontrat->getcleclient()->getville_client(),$lecontrat->getcleclient()->getcode_postal_client());
			$idclient =  $clientDao->getidfromchamps($objclient);	//getidclientfromchamps

			//retoune le nb jour facturable
			$JF = $craDAO->getJFfromidcontrat($_GET["idcontrat"], $periode);

			//définir la valeur du montant
			//var_dump($lecontrat->getsalaire());
			if($lecontrat->getsalaire() == 0){
				$montant = $lecontrat->getcleconsultant()->gettarif() * $JF;}
			else {
				$montant = $lecontrat->getsalaire();

					if($lecontrat->getsalaire() > 0 && $lecontrat->getcleconsultant()->gettarif() > 0){
						$tarif = $lecontrat->getcleconsultant()->gettarif() * $JF;
						$salaire = $lecontrat->getsalaire();
						$montant = $tarif + $salaire;
					}
				}
			$libelle = $lecontrat->getmission();
			$datef = date('Ymd');
			$objfacture = new trz_facture($libelle, $idcra,$montant, $datef); //créer un objet
			$factureDao->addfacture($objfacture); //ajouter l'objet facture dans la bdd
			$idfacture = $factureDao->dernieridfacture(); //recupère l'id de l'objet créer précédemment
			$unpaiement = new trz_payer ($idfacture, $_GET["idcontrat"], $idclient); //créer un nouvel objet facture
			$payerDao->addpayer($unpaiement);  //ajoute cette objet dans la bdd
			$Unefacture = $factureDao->getobjectfromid($idfacture); //retourne l'objet facture en fonctiond de son id
		}
		include ("vues/v_PDFcra.php");
		if($_SESSION['role_user'] == "Consultant"){
			?>
      	<script>
					alert("Votre CRA a bien été enregistré.");
					window.location.href='index.php?uc=consultant&action=afficherCraConsultant';
      	</script>
    	<?php
		}else{
			?>
      	<script>
					alert("Le CRA a bien été enregistré. Vous pouvez retrouver la facture associé dans la page 'Contrats'");
					window.location.href='index.php?uc=cra&action=choisirCra';
      	</script>
    	<?php
		}
		$craDAO->completerCra($idConsultant, $_GET["idcontrat"], $_GET['dateDebut']);
		//include ("vues/v_choixCra.php");
		break;
	}


	case 'crasConsultant':{
		$idconsultant  = $_GET['idconsultant'];
		$lesMois = $craDAO->crasConsultants($idconsultant);
		include 'vues/v_lescras.php';
	break;
	}

	case 'afficherCraChoisis': {

		$chemin = $craDAO->cheminCra($_POST["mois"], $_GET['idconsultant']);
		var_dump($chemin);
		include ('vues/v_chemin.php');
	}
}
