<?php
include("session/verif_session.php");

$contratsARemplir = $daoTrz_Contrat->getAllDatesContratARemplir();
$url1 = '&mail=';

foreach($contratsARemplir as $contratConsultant){
  $cons1 = $UconsultantDao->getConsultantfromId($contratConsultant['idutilisateur'])->getEmail();
  if(strpos($url1, $cons1) === false){
    $url1 = $url1 . $cons1 . ',';
  }
}
$url1 = substr($url1, 0, -1);

?>
<!--
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

<script
			  src="https://code.jquery.com/jquery-3.5.1.js"
			  integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
			  crossorigin="anonymous"></script>

-->

<script src="js/pagination.js"></script>

<div class='container'>
<h3 class='intitule'>Liste des CRA</h3></br>
<script type="text/javascript" src="js/cra.js"></script>

<table  id="filter" class="responsive-table">
 <div class="interior">
    <div style = 'float : right'><label><strong>Recherche</strong></label>
    <input type="text" name="category" class='rechercher' id="categoryFilter"><br><br>
 </div>

 <div style = 'float : left'>
    <a class="btn" onclick="window.location.replace('index.php?uc=cra&action=choisirCra#open-modal')">Envoi demande de CRA</a>
    <a class="btn" style="background-color: red!IMPORTANT;" onclick="window.location.replace('index.php?uc=cra&action=choisirCra<?php echo $url1 ?>')">Relance par mail</a>
  </div>
  <div style="text-align: center;">
    <label style="text-align:center;"><strong>Date de début </strong><input type="date" name="category" class='rechercher' id="dateFilter" onchange="filtreDates();">
  </div>

</div>
  <br>

  <div id="open-modal" name="demo" class="modal-window">
    <div>

    <div class="form-style-5">
          <form method="post" action="index.php?uc=cra&action=envoiDemandeCraConsultant">
              <fieldset>
                  <label>Date de début</label>
                  <input type="date" id="date" onchange="functionDate();" required>
                  <input id="temp" name="extra" value="" hidden>

              </fieldset>

              <input type="submit" name="envoiDemandeCraConsultant" style="float: left;" value="Envoyer" />
                  <a href="#" title="Close" id="close" class="modal-close">FERMER</a>
          </form>
      </div>

    </div>
  </div>

<tr class='contrat'>
      <td class="col col-5">Consultant</td>
      <td class="col col-5">Date de début</td>
      <td class="col col-5">Date de Fin</td>
      <td class="col col-5">Statut</td>
      <td class="col col-5">Action</td>
 </tr>

<?php

$dates_contrat = $daoTrz_Contrat->getAllDatesContrat();

$noligne=0;
 foreach($dates_contrat as $unContrat){
   $consultant = $UconsultantDao->getConsultantfromId($unContrat['idutilisateur']);
   $num_contrat = $unContrat['idcontrat'];

   $dateD = date('d/m/Y', strtotime($unContrat['date_min']));
   $dateF = date('d/m/Y', strtotime($unContrat['date_max']));;

  ?>

  <tr class="pagination<?php echo $noligne?>" id="ligneCra<?php echo $noligne?>" <?php if($noligne%2==0 ){echo"style='background-color:#e1ecfd;'";}else{echo 'style="background-color:#FFFFFF"';} ?>>

  <td class="col col-5 filter_td" name="modif<?php echo $noligne ?>" data-label="Consultant"><?php echo $consultant->getNom() . ' ' . $consultant->getPrenom() ;?></td>
  <td class="col col-5 filter_td" name="modif<?php echo $noligne ?>" id="date_debut<?php echo $noligne ?>" data-label="DateDebut"><?php echo $dateD ;?></td>
  <td class="col col-5 filter_td" name="modif<?php echo $noligne ?>" data-label="DateFin"><?php echo $dateF ;?></td>

<?php if($unContrat['aremplir'] == true){ $test = true;?>
    <td style="color:red" class="col col-5 filter_td" name="modif<?php echo $noligne ?>" data-label="Status"><?php echo 'En attente' ;?></td>
    <td align="center" data-label="Action">
       <a style="background-color:red" class="tableau" id="edit<?php echo $noligne ?>" name="modif<?php echo $noligne ?>" onclick="window.location.replace('index.php?uc=cra&action=afficherCra&idcontrat=<?php echo $num_contrat; ?>&ligne=<?php echo $noligne; ?>&dateDebut=<?php echo $dateD; ?>');" ><i class="fas fa-edit"></i></a>
       <a class="tableau" id="submit<?php echo $noligne ?>" name="modif<?php echo $noligne ?> bouton" onclick="window.location.replace('index.php?uc=cra&action=choisirCra&mail=<?php echo $consultant->getEmail();?>');"><i class="fas fa-envelope"></i></a>
       <input id="tempMail" hidden>
    </td>
<?php }elseif($unContrat['complet'] == true){?>
  <td style="color:green" class="col col-5 filter_td" name="modif<?php echo $noligne ?>" data-label="Status"><?php echo 'Complet' ;?></td>
  <td align="center" data-label="Action"></td>
<?php }else{?>
  <td class="col col-5 filter_td" name="modif<?php echo $noligne ?>" data-label="Status"><?php echo 'Non envoyé' ;?></td>
  <td align="center" data-label="Action"></td>
<?php } ?>




<script>

function filtreDates(){
  var dateInput = document.getElementById("dateFilter").value;
  var date = dateInput.split("-").reverse().join("/");

  for(let i=0; i < <?php echo $noligne;?> + 1 ; i++){

    var dateD = document.getElementById("date_debut" + i).innerText;

    if(dateD != date){
      document.getElementById("ligneCra" + i).style.display = "none";
    }else{
      document.getElementById("ligneCra" + i).style.display = "";
    }
  }

  if (dateInput == "") {
    for(let i=0; i < <?php echo $noligne;?> + 1 ; i++){
      document.getElementById("ligneCra" + i).style.display = "";
    }
  }
}

function functionDate(){
  var date = document.getElementById("date");
  date.setAttribute('value', date.value);
  document.getElementById("temp").value = date.value;

}



</script>


</tr>
 <?php

$noligne++;}

  if(isset($_GET['mail'])){
    $mails = explode(',',$_GET['mail']);

    foreach($mails as $unMail){
      $craDAO->relanceCra($unMail);
    }
    ?>
    <script>
      alert('Mail envoyé!');
      window.location.replace('index.php?uc=cra&action=choisirCra');
    </script>
    <?php
  }else{}

?>
</table>

<?php include("pagination/pagination.php"); ?>


</div>
