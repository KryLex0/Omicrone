<script src="js/pagination.js"></script>

<div class='container'>
<h3 class='intitule'>Liste des CRA de <?php echo $_SESSION['nom_user'] . ' ' . $_SESSION['prenom_user'] ?></h3>

<table  id="filter" class="responsive-table">
 <div class="interior">
    <div style = 'float : right'><label><strong>Recherche</strong></label>
    <input type="text" name="category" class='rechercher' id="categoryFilter"><br><br></div>


</div>
  <br>


<tr class='contrat'>
      <td class="col col-5">Numero de Contrat</td>
      <td class="col col-5">Date de début</td>
      <td class="col col-5">Date de Fin</td>
      <td class="col col-5">Action</td>
 </tr>

<?php

$num_facture = 1;
$noligne=0;
 foreach($contrats as $unContrat){

   $num_contrat = $unContrat['idcontrat'];
   $dateD = date('d/m/Y', strtotime($unContrat['date_min']));
   $dateF = date('d/m/Y', strtotime($unContrat['date_max']));;

   if($unContrat['aremplir'] == true || $unContrat['complet'] == true){
  ?>

 <tr class="pagination<?php echo $noligne?>" <?php if($noligne%2==0 ){echo"style='background-color:#e1ecfd;'";}else{echo 'style="background-color:#FFFFFF"';} ?>>





 <td class="col col-5 filter_td" name="modif<?php echo $noligne ?>" data-label="NumeroContrat"><?php echo $num_contrat ;?></td>
 <td class="col col-5 filter_td" name="modif<?php echo $noligne ?>" data-label="DateDebut"><?php echo $dateD ;?></td>
 <td class="col col-5 filter_td" name="modif<?php echo $noligne ?>" data-label="DateFin"><?php echo $dateF ;?></td>

 <td align="center" data-label="Action">
   <?php if($unContrat['complet'] == false){ ?>
     <a style="background-color:red" class="tableau" id="edit<?php echo $noligne ?>" name="modif<?php echo $noligne ?>" onclick="window.location.replace('index.php?uc=cra&action=afficherCra&idcontrat=<?php echo $num_contrat; ?>&ligne=<?php echo $noligne; ?>&dateDebut=<?php echo $dateD; ?>');" ><i class="fas fa-edit"></i></a>
  <?php }?>
 </td>


</tr>
 <?php
}else{}
$noligne++;
$num_facture++;} ?>
</table>

<?php include("pagination/pagination.php"); ?>

</div>
