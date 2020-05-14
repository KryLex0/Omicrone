
<div class="cra">

<div>
    <h3 style="float : right;"> Periode : <u> <?php echo getMoisFr($mois)." ".$annee; ?></u></h3>
</div>

<div style="margin-left: 6%;float:left;">
<h3> Intervenant : <u> <?php echo $consultant->getNom()." ".$consultant->getPrenom(); ?></u></h3>
<table style="margin-top:14%;margin-bottom:20%;margin-right:10%;padding-right:5%;border-spacing:0;border-collapse:collapse;">
  <tr>
    <th style="border:1px solid; padding:5%;">Jours Facturable</th>
    <th style="border:1px solid; padding:5%;">Jours Congé</th>
    <th style="border:1px solid; padding:5%;">Jours Maladie</th>
  </tr>
  <tr>
    <td style="border:1px solid; padding:5%;"><p id="testFacturable"></p></td>
    <td style="border:1px solid; padding:5%;"><p id="testConge"></p></td>
    <td style="border:1px solid; padding:5%;"><p id="testMaladie"></p></td>
  </tr>
</table>



<table style="margin-right:10%;padding-right:5%;border-spacing:0;border-collapse:collapse;display:flex;">
  <tr>
    <th style="border:1px solid; padding:5%;">Total de jours Ouvrable</th>
    <td style="border:1px solid; padding:5%; width:30%;"><p id="jourTotal"></p></td>
  </tr>
</table>



</div>
<form id="infoCra" action="index.php?uc=cra&action=createCRA&idcontrat=<?php echo $idcontrat; ?>&dateDebut=<?php echo $dateTemp; ?>" method="post">
<table style="border-collapse: collapse;">



<tr>
    <td></td>
<?php

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



for($i=1;$i<=$number;$i++){


    $timestamp = mktime(0, 0, 0, $mois, $i, $annee); // Donne le timestamp correspondant à cette date

        if (date('D', $timestamp)=="Sat" || date('D', $timestamp)=="Sun" || $craDAO->testJourFerie($timestamp)){
            echo '<td style="border:2px solid;background-color:#e1ecfd;font-weight: bold;">'.date('D', $timestamp)."  ".$i.'</td>';
        }
        else{
            echo '<td style="border:2px solid;font-weight: bold;">'.date('D', $timestamp)." ".$i.'</td>';
        }
}
?>
</tr>

<tr><td></td></tr>

 <tr>
     <td style="border:2px solid;">Journée facturables</td>
<?php

//-------------------------------------------------------------------------------------------------------------------
$x = 0;
for($i=1;$i<=$number;$i++){


    $timestamp = mktime(0, 0, 0, $mois, $i, $annee); //Donne le timestamp correspondant à cette date

    if (date('D', $timestamp)=="Sat" || date('D', $timestamp)=="Sun" || $craDAO->testJourFerie($timestamp)){
        echo '<td style="border:2px solid;background-color:#e1ecfd; "><input type="hidden" size="1" value="0" name="facturable[]" step="0.5" max="1" min="0" style="width: 2.75em;height:2.75em; background-color:#e1ecfd;" /></td>';
    }
    /*
    elseif($i < $x || $i > $z && $y != $mois){
        echo '<td style="border:2px solid;background-color:#d7d9db;font-weight: bold;"><input type="hidden" size="1" value="0" name="facturable[]" step="0.5" max="1" min="0" style="width: 2.75em;height:2.75em; background-color:#d7d9db;" /></td>';
    }*/
    else{
        echo '<td style="border:2px solid;"><input onchange="jourFacturable()" id="facturable" type="number" size="1" value="1" name="facturable[]" step="0.5" max="1" min="0" style="width: 2.75em;height:2.75em;" /></td>';
        $x += 1;
    }

}
?>
    </tr>

<script>
$(document).ready(function(){
  // we call the function
  //myFunction();
  jourFacturable();
  jourConge();
  jourMaladie();
  document.getElementById('jourTotal').textContent = parseFloat(<?php echo $x;?>);
  //sumTotal();
});

/*********************************************************/

function jourFacturable(){
  var jourfacturable = 0;
  $("input[type=number][id=facturable]").each(function(){
    var facturable = parseFloat($(this).val());
    if($.isNumeric(facturable)){
      jourfacturable = parseFloat(facturable)*1 + parseFloat(jourfacturable)*1;
    }
  });
  document.getElementById('testFacturable').textContent = parseFloat(jourfacturable);
  $("#testFacturable").attr("value",parseFloat(jourfacturable));
  //sumTotal();

}

/*********************************************************/

function jourConge(){
  //var x = document.getElementById("conge");
  //console.log(x);
  var jourconge = 0;
  $("input[type=number][id=conge]").each(function(){
    var conge = parseFloat($(this).val());
    if($.isNumeric(conge)){
      jourconge += parseFloat($(this).val());
    }
  });
  document.getElementById('testConge').textContent = parseFloat(jourconge);
  $("#testConge").attr("value",parseFloat(jourconge));
  //sumTotal();
}

/*********************************************************/

function jourMaladie(){
  var jourmaladie = 0;
  $("input[type=number][id=maladie]").each(function(){
    var maladie = parseFloat($(this).val());
    if($.isNumeric(maladie)){
      jourmaladie += parseFloat($(this).val());
    }
  });
  document.getElementById('testMaladie').textContent = parseFloat(jourmaladie);
  $("#testMaladie").attr("value",parseFloat(jourmaladie));
  //sumTotal();
}

/*********************************************************/

 $('#infoCra').submit(function(){
  var facturable = document.getElementById('testFacturable').innerHTML;
  var conge = document.getElementById('testConge').innerHTML;
  var maladie = document.getElementById('testMaladie').innerHTML;

  var nbTotal = parseFloat(facturable) + parseFloat(conge) + parseFloat(maladie);

  if(nbTotal != <?php echo $x ?>){
    console.log('different');
    alert('Veuillez verifier les données saisies avant de continuer.');
    return false;
  }else{
    console.log('pareil');
    return true;
  }
});


</script>


<tr><td></td></tr>

<tr>
    <td style="border:2px solid;">Absence - congé</td>
<?php

//-------------------------------------------------------------------------------------------------------------------

for($i=1;$i<=$number;$i++){

   $timestamp = mktime(0, 0, 0, $mois, $i, $annee); //Donne le timestamp correspondant à cette date

        if (date('D', $timestamp)=="Sat" || date('D', $timestamp)=="Sun" || $craDAO->testJourFerie($timestamp)){
            echo '<td style="border:2px solid;background-color:#e1ecfd;"><input type="hidden" size="1" value="0" name="conger[]" step="0.5" max="1" min="0" style="width: 2.75em;height:2.75em;background-color:#e1ecfd;" /></td>';
        }
        else{
            echo '<td style="border:2px solid;"><input onchange="jourConge()" id="conge" type="number" size="1" value="0" name="conger[]" step="0.5" max="1" min="0" style="width: 2.75em;height:2.75em;" /></td>';
        }

}
?>
   </tr>




<tr>
    <td style="border:2px solid;">Absence - maladie</td>
<?php

//-------------------------------------------------------------------------------------------------------------------

for($i=1;$i<=$number;$i++){


   $timestamp = mktime(0, 0, 0, $mois, $i, $annee); //Donne le timestamp correspondant à cette date

            if (date('D', $timestamp)=="Sat" || date('D', $timestamp)=="Sun" || $craDAO->testJourFerie($timestamp)){
                echo '<td style="border:2px solid;background-color:#e1ecfd;"><input type="hidden" size="1" value="0" name="maladie[]" step="0.5" max="1" min="0" style="width: 2.75em;height:2.75em;background-color:#e1ecfd;" /></td>';
            }
            else{
                echo '<td style="border:2px solid;"><input onchange="jourMaladie()" id="maladie" type="number" size="1" value="0" name="maladie[]" step="0.5" max="1" min="0" style="width: 2.75em;height:2.75em;" /></td>';
            }
        }


//-------------------------------------------------------------------------------------------------------------------


?>
   </tr>

   <tr><td></td></tr>
   <tr>
    <td style="border:2px solid;">Astreinte</td>
    <td colspan="<?php echo $number; ?>" style="border:2px solid;"><textarea placeholder="Décrire les astreintes" style="width:100%;" rows="4"  name="astreinte"></textarea></td>
   </tr>
   <tr>
    <td style="border:2px solid;">Intervention</td>
    <td colspan="<?php echo $number; ?>" style="border:2px solid;"><textarea placeholder="Décrire les interventions" style="width:100%;" rows="4"  name="interv"></textarea></td>
   </tr>


   <?php $consultant=$daoTrz_Contrat->getobjcontrat($idcontrat)->getcleconsultant();
    var_dump($idcontrat);
    $idConsultant=$UconsultantDao->getIdConsultantFromobject($consultant);
   ?>
<input type="hidden" name="number" value="<?php echo $number ?>">
<input type="hidden" name="idContrat" value="<?php echo $idcontrat ?>">
<input type="hidden" name="idConsultant" value="<?php echo $idConsultant ?>">
<input type="hidden" name="annee" value="<?php echo $annee ?>">
<input type="hidden" name="mois" value="<?php echo $mois ?>">




</table>

<!--
<script>

function myFunction(){
  var i = 1;

}

</script>
-->


<input type="submit" value="Generer le CRA" class="form-style-5" style="width: 100%;background-color:lightgray;"/>
</form>
    </div>
