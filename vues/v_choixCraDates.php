<?php
include("session/verif_session.php");
//var_dump($listecontrats[0]['date_min']);
//var_dump($daoTrz_Contrat->craRempli($_SESSION['idconsultant'],$_SESSION['idContrat'],"2020-04-01"));
//var_dump($daoTrz_Contrat->craRempli($_SESSION['idconsultant'],$_SESSION['idContrat'],"2020-05-01"));
var_dump($_POST);
?>
<div class="container">
 <div class="form-style-5">
<form method="post" action="index.php?uc=cra&action=envoiCraConsultant">
<fieldset>
        <legend align="center">Informations du CRA</legend><br>


        <select id="annee" onChange="dateInfos();" required>
          <option value="default" selected disabled>Choisir une Date</option>

<?php

//$listeC = sizeof($listecontrats);
$i=0;
$y=0;
//while($i<sizeof($listecontrats)){
//while($y<2){
foreach ($listecontrats as $unContrat){

  //var_dump($listecontrats[$i]['date_min']);
  //var_dump($unContrat);
  $daoTrz_Contrat->craRempli($_SESSION['idconsultant'],$_SESSION['idContrat'],$listecontrats[$i]['date_min']);
  $date1 = date('d/m/Y', strtotime($listecontrats[$i]['date_min']));
  $date2 = date('d/m/Y', strtotime($listecontrats[$i]['date_max']));
  //var_dump(!($daoTrz_Contrat->craRempli($_SESSION['idconsultant'],$_SESSION['idContrat'],$unContrat['date_min'])));
  if($daoTrz_Contrat->craRempli($_SESSION['idconsultant'],$_SESSION['idContrat'],$listecontrats[$i]['date_min'])){?>
    <option disabled><?php echo '[COMPLET] ' . $date1 . ' au ' . $date2 ?></option>
  <?php
}elseif($daoTrz_Contrat->craEnvoye($_SESSION['idconsultant'],$_SESSION['idContrat'],$listecontrats[$i]['date_min'])){?>
    <option disabled><?php echo '[EN ATTENTE] ' . $date1 . ' au ' . $date2 ?></option>

  <?php
  }else{ //elseif(!($daoTrz_Contrat->craRempli($_SESSION['idconsultant'],$_SESSION['idContrat'],$unContrat['date_min'])))
  ?>
  <option><?php echo $date1 . ' au ' . $date2 ?></option>

  <?php
  }
  $i++;
}
?>

</select>



<script type="text/javascript">
$( document ).ready(function() {
    console.log( "ready!" );
    $(".test > option").hide();
    //$(".test option").hide();
    document.getElementById("mySelect").options[0].selected='selected';
    document.getElementById("annee").options[0].selected='selected';
});


function dateInfos(){
  var annee = document.getElementById("annee");//.value=display;
  var annee1 = annee.options[annee.selectedIndex];
  document.getElementById("temp").value = annee1.text;
}



// function envoiDates(){
//   var annee = document.getElementById("annee");//.value=display;
//   var annee1 = annee.options[annee.selectedIndex];
//   sessionStorage.setItem('dateD', annee1.text);
// }

</script>


<input id="temp" type="hidden" name="extra" value="">




<input type="submit" id="valider" value="Suivant" name="valider" style="width: 100%!important">
</form>
 <br></div>
