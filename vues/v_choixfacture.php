<div class="container">
<div class="form-style-5">
        <form method="post" action="index.php?uc=facture&action=afficherfacture&idcontrat=<?php echo $idContrat; ?>">
                <fieldset>
                        <legend align="center">Factures</legend><br>
                                <?php

                                $datedebut = date('d/m/Y', strtotime($lecontrat->getdate_debut_contrat()));
                                $datefin = date('d/m/Y', strtotime($lecontrat->getdate_fin_contrat()));

                                echo 'Contrat : du '. $datedebut .' au '. $datefin .'<br><br>';
                                echo 'Mission : '.$lecontrat->getmission().'';
                                 ?>
<br><br>
                        <select name="mois" required>
                                <?php
                                for($i=0; $i<sizeof($lesMois);$i++){
                                        $mois = $lesMois[$i];
                                        if(preg_match("/^[1-9][2][0]/", $mois)){
                                          $mois1 = "0".$mois;
                                          $numMois = substr($mois1, 0, 2);
                                          $numAnnee = substr($mois1, 2, 4);
                                        }else{
                                          $numMois = substr($mois, 0, 2);
                                          $numAnnee = substr($mois, 2, 4);
                                        }

                                        if($mois == $moisASelectionner){
                                        ?>
                                        <option selected value="<?php echo $mois ?>"><?php echo  $numMois."/".$numAnnee ?> </option>
                                        <?php
                                        }
                                        else{ ?>
                                        <option value="<?php echo $mois ?>"><?php echo  $numMois."/".$numAnnee ?> </option>
                                        <?php }

                                } ?>
                        </select>
                        <input type="submit" value="Valider" name="valider" style="width: 100%!important">

                </fieldset>

        </form>
</div>
</div>
