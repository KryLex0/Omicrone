<div class="container">
<div class="form-style-5">
        <form method="post" action="index.php?uc=facture&action=choixFacture">
                <fieldset>
                        <legend align="center">Factures</legend><br>

                        <select id="contrat" class="select" name="idContrat" onChange="detailsContrat()">
                            <option value="default" selected disabled>Choisir Un contrat</option>

                            <?php
                              foreach ($allContrat as $unContrat){
                                    //var_dump($unContrat);
                            ?>

                                <option value="<?php echo $unContrat->getidContrat();?>"> <?php echo "Consultant : ". $_SESSION['nom_user']." ".$_SESSION['prenom_user']." - Date debut : ".$dateMin = date('d/m/Y', strtotime($unContrat->getdate_debut_contrat()))." - Date fin : ".$dateMax = date('d/m/Y', strtotime($unContrat->getdate_fin_contrat())); ?></option>

                            <?php
                              }
                            ?>
                        </select>


                        <input type="submit" value="Valider" name="valider" style="width: 100%!important">

                </fieldset>

        </form>
</div>
</div>
