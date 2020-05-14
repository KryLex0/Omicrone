<?php
include('session/verif_session.php');

?>

<script src="js/pagination.js"></script>

<div class='container'>
<h3 class='intitule'>Les consultants</h3></br>

<table  id="filter" class="responsive-table">

 <div class="interior">
    <div style = 'float : right'><label><strong>Recherche</strong></label>
    <input type="text" name="category" class='rechercher' id="categoryFilter"><br><br></div>

     <a class="btn" href="#open-modal">AJOUTER</a>
</div>
  <br>
<div id="open-modal" name="demo" class="modal-window">
  <div>

  <div class="form-style-5">
        <form method="post" action="index.php?uc=consultant&action=validajoutconsultant">
            <fieldset>
                <input type="text" pattern="[A-Za-z ]+" name="nom" placeholder="Nom *" required="required">
                <input type="text" pattern="[A-Za-z ]+" name="prenom" placeholder="Prenom *" required="required">
                <input type="text" name="adr" placeholder="Adresse *" required="required">
                <input type="text" pattern="[A-Za-z- ]+" name="ville" placeholder="Ville *" required="required">
                <input type="number" min="0" name="cp" placeholder="Code Postal *" required="required">
                <input type="tel" pattern="[0-9]{10}" name="tel" placeholder="Numero de telephone *" required="required">
                <input type="email" name="email" placeholder="Email *" required="required">
<!--
                <input type="text" pattern="[0-9]+" name="salaire" placeholder="salaire *" required="required">
-->
                <input type="text" pattern="[0-9]+" name="tarif" placeholder="tarif *" required="required">
                <select name="typecontrat" id="">
                  <option value="Salarie">Salarié</option>
                  <option value="Soustraitant">Sous-traitant</option>
                  <option value="Independant">Indépendant</option>
                </select>

            </fieldset>

            <input type="submit" name="validajoutconsultant" style="float: left;" value="AJOUTER" />
                <a href="#" title="Close" id="close" class="modal-close">FERMER</a>
        </form>
    </div>

  </div>
</div>


<tr class='contrat'>
      <td class="col col-5" >Nom</td>
      <td class="col col-5">Prenom</td>
      <td class="col col-5">Adresse</td>
      <td class="col col-5">Ville</td>
      <td class="col col-5">Code Postale</td>
      <td class="col col-5">Tel</td>
      <td class="col col-5">Email</td>
      <td class="col col-5">Type de contrat</td>
<!--
      <td class="col col-5">Salaire</td>
-->
      <td class="col col-5">Tarif</td>
      <td class="col col-5">Cra</td>
      <td class="col col-5">Action</td>
 </tr>

 <?php

 $noligne=0;
  foreach($lesConsultants as $unConsultant){
    $nom = $unConsultant->getNom();
    $prenom = $unConsultant->getPrenom();
    $adresse = $unConsultant->getAdresse();
    $ville = $unConsultant->getVille();
    $cp = $unConsultant->getCp();
    $tel = $unConsultant->getTel();
    $email = $unConsultant->getEmail();
    $typecontrat = $unConsultant->gettypecontrat();
    //$salaire = $unConsultant->getsalaire();
    $tarif = $unConsultant->gettarif();

    $id=$UconsultantDao->getIdConsultantFromobject($unConsultant);

    //var_dump($typecontrat[0]);
   ?>

  <tr class="pagination<?php echo $noligne?>" <?php if($noligne%2==0 ){echo"style='background-color:#e1ecfd;'";}else{echo 'style="background-color:#FFFFFF"';} ?>>

  <!-- <td class="col col-5" data-label='Nom'><?php // echo $unConsultant->getNom() ?></td> -->

  <td class="col col-5 filter_td" name="modif<?php echo $noligne ?>" data-label="Nom"><?php echo $nom ;?></td>
    <td class="col col-5" style="display: none" name="tdmodif<?php echo $noligne ?>">
      <input class="col col-4" name="demodif<?php echo $noligne ?>" type="text" placeholder="Nom *" value="<?php echo $nom;?>">
    </td>

    <td class="col col-5 filter_td" name="modif<?php echo $noligne ?>" data-label="Prenom"><?php echo $prenom ;?></td>
    <td class="col col-5" style="display: none" name="tdmodif<?php echo $noligne ?>">
      <input class="col col-4" name="demodif<?php echo $noligne ?>" type="text" placeholder="Prenom *" value="<?php echo $prenom;?>">
    </td>

    <td class="col col-5 filter_td" name="modif<?php echo $noligne ?>" data-label="Adresse"><?php echo $adresse ;?></td>
    <td class="col col-5" style="display: none" name="tdmodif<?php echo $noligne ?>">
      <input class="col col-4" name="demodif<?php echo $noligne ?>" type="text" placeholder="Adresse *" value="<?php echo $adresse;?>">
    </td>

    <td class="col col-5 filter_td" name="modif<?php echo $noligne ?>" data-label="Ville"><?php echo $ville ;?></td>
    <td class="col col-5" style="display: none" name="tdmodif<?php echo $noligne ?>">
      <input class="col col-4" name="demodif<?php echo $noligne ?>" type="text" placeholder="Ville *" value="<?php echo $ville;?>">
    </td>

    <td class="col col-5 filter_td" name="modif<?php echo $noligne ?>" data-label="Code Postal"><?php echo $cp ;?></td>
    <td class="col col-5" style="display: none" name="tdmodif<?php echo $noligne ?>">
      <input style="width: 100px;" class="col col-4" name="demodif<?php echo $noligne ?>" type="number" pattern="[0-9]{10}" min="0" placeholder="Code Postal *" value="<?php echo $cp;?>">
    </td>

    <td class="col col-5 filter_td" name="modif<?php echo $noligne ?>" data-label="Telephone"><?php echo $tel ;?></td>
    <td class="col col-5" style="display: none" name="tdmodif<?php echo $noligne ?>">
      <input  class="col col-4" name="demodif<?php echo $noligne ?>" type="number" style="width:9em;" placeholder="Telephone *" value="<?php echo $tel;?>">
    </td>

    <td class="col col-5 filter_td" name="modif<?php echo $noligne ?>" data-label="Email"><?php echo $email ;?></td>
    <td class="col col-5" style="display: none" name="tdmodif<?php echo $noligne ?>">
      <input class="col col-4" name="demodif<?php echo $noligne ?>" type="email" placeholder="Email *" value="<?php echo $email;?>">
    </td>


    <td class="col col-5 filter_td" name="modif<?php echo $noligne ?>" data-label="typedecontrat"><?php echo $typecontrat; ?></td>
    <td class="col col-5" style="display: none" name="tdmodif<?php echo $noligne ?>">
        <select name="demodif<?php echo $noligne ?>" class="col col-4" id="typeContratConsultant" value="<?php echo $typecontrat;?>" onchange="changeTypeContrat();">
          <option value="Salarie" >Salarié</option>               <!--1-->
          <option value="Soustraitant">Sous-traitant</option>     <!--2-->
          <option value="Independant">Indépendant</option>        <!--3-->
        </select>
    </td>


    <script>

      function myFunction(){
        var x = document.getElementById("typeContratConsultant");
        x.setAttribute("value", x.options[x.selectedIndex].value);
        console.log(x);
        //var y = document.getElementById("temp");
        console.log(x.options[x.selectedIndex].value);
        //x.setAttribute("name", y.name);
      }

    </script>






      <td class="col col-5 filter_td" name="modif<?php echo $noligne ?>" data-label="tarif"><?php echo $tarif ;?></td>
      <td class="col col-5" style="display: none" name="tdmodif<?php echo $noligne ?>">
        <input class="col col-4" name="demodif<?php echo $noligne ?>" type="text" placeholder="tarif *" value="<?php echo $tarif;?>">
      </td>

<td><a href="index.php?uc=cra&action=crasConsultant&idconsultant=<?php echo $id; ?>"><i class="far fa-calendar-alt"></i></a></td>

<td align="center" data-label="Action">
<a class="tableau" id="submit<?php echo $noligne ?>" name="modif<?php echo $noligne ?>" onclick="modif(this.name,this.id);"><i class="fas fa-edit"></i></a>
      <a class="tableau" id="desubmit<?php echo $noligne; ?>" style="display: none; padding:0px; margin-right:5px;">
        <button id="button" name="modif<?php echo $noligne; ?>" onclick="submitConsultant(this.name,<?php echo $id;?>);">
        <i class="fas fa-check"></i>
        </button>
      </a>

      <a class="delete" onclick=
      "if (confirm('voulez vous supprimer le commercial ?'))
      {window.location.replace('index.php?uc=consultant&action=suppconsultant&idconsultant=<?php echo $id; ?>');}">
        <i class="fas fa-times"></i></a>

      </td>

</tr>
 <?php
$noligne++;} ?>
</table>

<?php include("pagination/pagination.php"); ?>

</div>
