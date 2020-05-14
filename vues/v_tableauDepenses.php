<?php
include('session/verif_session.php');
?>

<script src="js/pagination.js"></script>

<div class="container"><h3 class='intitule'>Les depenses</h3></br>

  <table id="filter" class="responsive-table">
  <div class="interior">
    <div style = 'float : right'><label><strong>Recherche</strong></label>
    <input type="text" name="category" class='rechercher' id="categoryFilter"><br><br></div>

     <a class="btn" href="#open-modal">AJOUTER</a>
</div>
  <br>
<div id="open-modal" class="modal-window">
  <div>
   <h2>Ajouter une depense</h2>
<div class="form-style-5">

<form method="post" action="index.php?uc=depense&action=ajouterDepense">

<input type="number" min="0" name="montant" placeholder="Montant *" required="required">
<input type="text" pattern="[A-Za-z]{1,20}" name="libelle" placeholder="Libelle *" required="required">

<a href="#" title="Close" id="close" class="modal-close">Fermer</a>
<input type="submit" name="envoyer" value="Ajouter"/>
</form>
</div>
  </div>
</div>



    <tr class="contrat">
    <td class="col col-4">Montant</td>
      <td class="col col-3">Libelle</td>

      <td class="col col-3">Action</td>
    </tr>


<?php
$noligne=0;
foreach ($lesDepenses as $uneDep){

  $id=$depenseDao->getIdDepense($uneDep);
  $libelle=$uneDep->getLibelle();
  $montant=$uneDep->getMontant();
    ?>


    <tr class="pagination<?php echo $noligne?>" <?php if($noligne%2==0 ){echo"style='background-color:#e1ecfd;'";}else{echo 'style="background-color:#FFFFFF"';} ?>>



    <td class="col col-3 filter_td" name="modif<?php echo $noligne ?>" data-label="Montant"><?php echo $montant ;?>€</td>
    <td class="col col-3" style="display: none" name="tdmodif<?php echo $noligne ?>">
    <input class="col col-3" style="width: 6em;" name="demodif<?php echo $noligne ?>" type="number" min="0" placeholder="Montant *" value="<?php echo $montant;?>"></td>

    <td class="col col-3 filter_td" name="modif<?php echo $noligne ?>" data-label="libelle"><?php echo $libelle ;?></td>
    <td class="col col-3" style="display: none" name="tdmodif<?php echo $noligne ?>">
    <input class="col col-3" name="demodif<?php echo $noligne ?>" type="text" min="0" placeholder="Libelle *" value="<?php echo $libelle;?>"></td>





    <td align="center" data-label="Action">
      <a class="tableau" id="submit<?php echo $noligne ?>" name="modif<?php echo $noligne ?>" onclick="modif(this.name,this.id);"><i class="fas fa-edit"></i></a>
      <a class="tableau" id="desubmit<?php echo $noligne ?>" style="display: none; padding:0px; margin-right:5px;"><button id="button" name="modif<?php echo $noligne ?>" onclick="submitDepense(this.name,<?php echo $id;?>);"><i class="fas fa-check"></i></button></a>

      <a class="delete" onclick=
      "if (confirm('voulez vous supprimer la depense ?'))
      {window.location.replace('index.php?uc=depense&action=deleteDepense&idDepense=<?php echo $id;?>');}">
        <i class="fas fa-times"></i></a>

      </td>
</tr>
<?php
$noligne++;
}
?>
</table><br>

<?php include("pagination/pagination.php"); ?>

</div>
