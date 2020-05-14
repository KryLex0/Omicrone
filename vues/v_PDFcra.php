
<?php
$mois=$_POST["mois"];
$annee=$_POST["annee"];
$mpdf = new \Mpdf\Mpdf(['orientation' => 'L']);
$mpdf->showImageErrors = true;
$data='';

$data.='<div>';
$data.=' <img src="logo/omicrone.jpg" width="250" style="float:right;" />'; // 90 pixels, just like HTML
$data.='</div>';


$data.='<div style="float:left;">';
$data.='<h3 style="margin-top:-100px;"> Periode : <u> '. getMoisFr($mois).' '.$annee .'</u></h3>';
$data.='<h3 style=""> Intervenant : <u>' . $_SESSION['nom_user']." ". $_SESSION['prenom_user'] .'</u></h3>';
$data.='<h3 style=""> Client : <u>' . $nomClient .'</u></h3>';
$data.='</div>';
$data.='<table style="border-collapse: collapse;">';


$number=$_POST["number"];

//*************************************************************
/**
	* Cette fonction retourne un tableau de timestamp correspondant
	* aux jours fériés en France pour une année donnée.
	*/
	/*
	function jourFerie($date)
	{

	  	if ($date === null)
	  	{
	    	$date = time();
	  	}

	 	$date = strtotime(date('m/d/Y',$date));

	 	$year = date('Y',$date);

		$easterDate  = easter_date($year);
		$easterDay   = date('j', $easterDate);
		$easterMonth = date('n', $easterDate);
		$easterYear   = date('Y', $easterDate);

		$holidays = array(
	    // Dates fixes
	    mktime(0, 0, 0, 1,  1,  $year),  // 1er janvier
	    mktime(0, 0, 0, 5,  1,  $year),  // Fête du travail
	    mktime(0, 0, 0, 5,  8,  $year),  // Victoire des alliés
	    mktime(0, 0, 0, 7,  14, $year),  // Fête nationale
	    mktime(0, 0, 0, 8,  15, $year),  // Assomption
	    mktime(0, 0, 0, 11, 1,  $year),  // Toussaint
	    mktime(0, 0, 0, 11, 11, $year),  // Armistice
	    mktime(0, 0, 0, 12, 25, $year),  // Noel

	    // Dates variables
	    mktime(0, 0, 0, $easterMonth, $easterDay + 1,  $easterYear), //paques
	    mktime(0, 0, 0, $easterMonth, $easterDay + 40, $easterYear), //ascension
	    mktime(0, 0, 0, $easterMonth, $easterDay + 50, $easterYear), //Pentecôte
		);

  	return in_array($date, $holidays);
	}
*/

//*************************************************************



$data.='<tr><td></td><br>';

$test = $daoTrz_Contrat->getobjcontrat($_POST['idContrat']);
$dateDeb = $test->getdate_debut_contrat();
$x = date('d', strtotime($dateDeb));                            //numero du jour 1 dans le contrat


$diff = cal_days_in_month(CAL_GREGORIAN, (int)$mois, (int)$annee);          //nombre de jours dans le mois du contrat
$temp = $diff - $x;             //difference de jour entre : 'numero du jour 1 dans le contrat' et 'nombre de jour dans le mois en cours'

list($annee1,$mois1,$jour1,) = explode('-', $dateDeb);


$timestamp2 = mktime(0,0,0,$mois,$jour1,$annee1);    //timestamp a partir des dates de debut du contrat
$jourFinMois1 = strtotime("+1 month", $timestamp2);   //date debut contrat + 1 mois

$num = date('d', $jourFinMois1);

$num1 = $temp + $num;

//-------------------------------------------------------------------------------------------------------------------

for($i=1;$i<=$number;$i++){


    $timestamp = mktime(0, 0, 0, $mois, $i, $annee); // Donne le timestamp correspondant à cette date

        if (date('D', $timestamp)=="Sat" || date('D', $timestamp)=="Sun" || $craDAO->testJourFerie($timestamp)){
            $data.= '<td style="border:2px solid;background-color:#e1ecfd;font-weight: bold;">'.date('D', $timestamp)." ".$i.'</td>';
        }
        else{
            $data.= '<td style="border:2px solid;font-weight: bold;">'.date('D', $timestamp)."  ".$i.'</td>';
        }
}

$data.='</tr>';

$data.='<tr><td></td></tr>';
$data.='<br />';
$data.='<br />';

$data.='<tr><td style="border:2px solid;">Journée facturables</td>';


for($i=1;$i<=$number;$i++){
    if($_POST["facturable"][$i-1]==0){
        $_POST["facturable"][$i-1]='';
    }

    $timestamp = mktime(0, 0, 0, $mois, $i, $annee);
    if (date('D', $timestamp)=="Sat" || date('D', $timestamp)=="Sun" || $craDAO->testJourFerie($timestamp)){
    	$data.= '<td style="border:2px solid;background-color:#e1ecfd; text-align:center;">'. $_POST["facturable"][$i-1] .'</td>';
    }
    else{
        $data.= '<td style="border:2px solid;text-align:center;">'. $_POST["facturable"][$i-1] .'</td>';
    }

}
$data.='<td style="border:2px solid;font-weight: bold;">Total '.$TJF.'</td>';

$data.=' </tr>';

$data.='<tr><td></td></tr>';
$data.='<br />';

$data.='<tr><td style="border:2px solid;">Absence - congé</td>';


for($i=1;$i<=$number;$i++){

    if($_POST["conger"][$i-1]==0){
        $_POST["conger"][$i-1]='';
    }
   $timestamp = mktime(0, 0, 0, $mois, $i, $annee); //Donne le timestamp correspondant à cette date

   if (date('D', $timestamp)=="Sat" || date('D', $timestamp)=="Sun" || $craDAO->testJourFerie($timestamp)){
    $data.= '<td style="border:2px solid;background-color:#e1ecfd;text-align:center;">'. $_POST["conger"][$i-1] .'</td>';
    }
    else{
        $data.= '<td style="border:2px solid;text-align:center;">'. $_POST["conger"][$i-1] .'</td>';
    }

}
$data.='<td style="border:2px solid;font-weight: bold;">Total '.$TJC.'</td>';
 $data.='</tr>';



 $data.='<tr><td style="border:2px solid;">Absence - maladie</td>';


for($i=1;$i<=$number;$i++){


   $timestamp = mktime(0, 0, 0, $mois, $i, $annee);

   if($_POST["maladie"][$i-1]==0){
    $_POST["maladie"][$i-1]='';
}

   if (date('D', $timestamp)=="Sat" || date('D', $timestamp)=="Sun" || $craDAO->testJourFerie($timestamp) ){
    $data.= '<td style="border:2px solid;background-color:#e1ecfd;text-align:center;">'. $_POST["maladie"][$i-1] .'</td>';
    }
    else{
        $data.= '<td style="border:2px solid;text-align:center;">'. $_POST["maladie"][$i-1] .'</td>';
    }

        }




				$data.='<td style="border:2px solid;font-weight: bold;">Total '.$TJM.'</td>';


				$data.='</tr>';

				$data.='<tr><td></td></tr>';
				$data.='<br />';





				$data.=' <tr>';
				$data.='  <td style="border:2px solid;">Astreinte</td>';
				$data.='   <td colspan='. $number.' style="border:2px solid;"><textarea style="width:100%;height:6%;">'.$_POST["astreinte"] .'</textarea></td>
				        </tr>';
				$data .='<tr><td style="border:2px solid;">Interventions</td><td colspan='. $number.' style="border:2px solid;"><textarea style="width:100%;height:6%;">'.$_POST["interv"] .'</textarea></td>
				</tr>';

				        $data.='<tr><td></td></tr>';
				        $data.='<br />';



				        $data.='<tr><td></td><br>';

//-------------------------------------------------------------------------------------------------------------------

for($i=1;$i<=$number;$i++){
    $timestamp = mktime(0, 0, 0, $mois, $i, $annee); // Donne le timestamp correspondant à cette date

        if (date('D', $timestamp)=="Sat" || date('D', $timestamp)=="Sun" || $craDAO->testJourFerie($timestamp)){
            $data.= '<td style="border:2px solid;background-color:#e1ecfd;font-weight: bold;">'.date('D', $timestamp).'</td>';
        }
        else{
      			$data.= '<td style="border:2px solid;font-weight: bold;">'.date('D', $timestamp).'</td>';
        }
 }

//-------------------------------------------------------------------------------------------------------------------

        $data.='<td style="border:2px solid;color:red;font-weight: bold;">Total '.$cra->getTotal().'</td>';
        $data.='</tr>';


        $data.='</table>';
        $data.='</div>';



        $data.='  <div style="background-color: white;
                width: 700px;
                margin-left:10%;
                border: 2px solid black;
                padding: 20px;
                ">
                 <h3 style="color:red;margin-left:20%;float:left;margin-top:-10px;">Client&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;&emsp;Prestataire</h3>
                    <br>
                <div style="border-bottom:2px solid black;
                height:-20px;">';




  $data.=' </div>';
  $data.='<br>';
  $data.='<h4> Date : </h4>';
  $data.='<h4> Signature : </h4>';

  $data.='</div>';


		$mpdf->WriteHTML($data);
    $dossier_facture = 'CRA/' . $_SESSION['nom_user'] . '/';

    if(!(is_dir($dossier_facture))){                          //si aucun dossier au nom du consultant
      mkdir('CRA/' . $_SESSION['nom_user'] . '/');            //=> creation d'un dossier qui contiendra les CRA du consultant
    }
    /*

//----------------------------------------------------
    $i = 1;
    $fichier ='CRA_'.getMoisFr($mois).'_'.$annee1. '_' . $consultant->getNom(). '_' .$consultant->getPrenom() . '.pdf';
    $fichier_facture = $i . '.' . $fichier;
    if(file_exists($dossier_facture . '/' . $fichier_facture)){           //si fichier CRA au nom du consultant deja créé
      while(file_exists($dossier_facture . '/' . $fichier_facture)){
        $nouveau_nom = $i . '.' . $fichier;                       //creation d'un autre fichier CRA au nom du consultant
        $i++;
        if(!(file_exists($dossier_facture . '/' . $nouveau_nom))){        //si nouveau fichier créé n'existe pas
          $fichier_facture = $nouveau_nom;
          break;
        }
      }
    }else{}*/

    /*
    $mpdf->Output('CRA/' . $consultant->getNom() . '/' . $fichier_facture,"F");   //ecriture dans le dossier -> fichier au nom du consultant
    */

    $mpdf->Output('CRA/' . $_SESSION['nom_user'] . '/' . 'CRA_' . getMoisFr($mois) . '_'.$annee . '.pdf',"F");
