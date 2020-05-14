<?php
include('session/verif_session.php');
?>

<div class="container">
    <div class="form-style-5">
        <form method="post" action="index.php?uc=consultant&action=validajoutconsultant">
            <fieldset>
              <input type="text" pattern="[A-Za-z]{1,20}" name="nom" placeholder="Nom *" required="required">
              <input type="text" pattern="[A-Za-z]{1,20}" name="prenom" placeholder="Prenom *" required="required">
              <input type="text" name="adr" placeholder="Adresse *" required="required">
              <input type="text" pattern="[A-Za-z- ]+" name="ville" placeholder="Ville *" required="required">
              <input type="number" min="0" name="cp" placeholder="Code Postal *" required="required">
              <input type="tel" pattern="[0-9]{10}" name="tel" placeholder="Numero de telephone *" required="required">
              <input type="email" name="email" placeholder="Email *" required="required">
              <input type="text" pattern="[0-9]+" name="tarif" placeholder="tarif *" required="required">
              <select name="typecontrat" id="">
                <option value="Salarie" >Salarié</option>               <!--1-->
                <option value="Soustraitant">Sous-traitant</option>     <!--2-->
                <option value="Independant">Indépendant</option>        <!--3-->
              </select>
                <input type="submit" name="envoyer" value="Ajouter" />
            </fieldset>
        </form>
    </div>
</div>
