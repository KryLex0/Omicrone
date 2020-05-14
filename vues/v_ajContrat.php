<?php
include("session/verif_session.php");
?>

<div class="container">
    <div class="form-style-5">
        <table class="responsive-table">
            <form method="POST" action="index.php?uc=contrat&action=validAjoutC">
                <fieldset>
                    <legend>Ajouter un contrat</legend>
                     Client : <select class="select" id='lesClients'name="lesClients">
                    <?php
                        foreach ($lesClients as $unClient) {
                            $idclient = $unClient['id'];
                            $raisonsocial = $unClient['raisonsocial'];?>
                           <option  value="<?php echo $idclient?>"><?php echo $raisonsocial?></option>
                            <?php  }?>
                        </select>
                        Consultant : <select class="select" name="lesConsultants">
                            <?php foreach($lesConsultants as $unConsultant){
                                $idconsultant = $unConsultant['id'];
                                $nom = $unConsultant['nom'];
                                $prenom = $unConsultant['prenom']; ?>
                            <option value="<?php echo $idconsultant?>"><?php echo $nom.' '.$prenom?></option>
                         <?php } ?>

                        </select>
                        <?php
                        if(isset($_POST['ajoutercontrat'])){
                            if(empty($_POST['datedebut']) OR empty($_POST['datefin'])){
                            echo '<p class="commentaire">Le champs <span class="commentaire">D&eacute;but du contrat</span> est vide</p>';
                            }
                            if(isset($_POST['datefin']) <= isset($_POST['datedebut'])){
                                echo '<p class="commentaire">Date de d&eacute;but ne doit pas exc&eacute;der la date de fin</p>';
                            }
                        }
                        ?>
                        <label>D&eacute;but du contrat :*</label>
                        <input type="date"  value="<?php if (isset($_POST['datedebut'])){echo $_POST['datedebut'];} ?>" name='datedebut'>

                        <?php
                        if(isset($_POST['ajoutercontrat'])){
                        if (empty($_POST['datefin']))
                            {
                                echo '<p class="commentaire">Le champs <span class="commentaire">Fin du contrat</span> est vide</p>';
                        }}?>
                        <label>Fin du Contrat : *</label>
                        <input type="date" value="<?php if (isset($_POST['datefin'])){echo $_POST['datefin'];} ?>" name="datefin" >

                        <label>Salaire : *</label>
                        <input type="text" pattern="[0-9]+" value="<?php if (isset($_POST['salaire'])){echo $_POST['salaire'];} ?>" name="salaire">

                        <input type="text" value="<?php if (isset($_POST['mission'])){echo $_POST['mission'];}?>" name="mission" placeholder='Description de la mission'>
                        <label>Type de contrat : *</label>
                            <div style="display: flex;">
                                <input onclick="afficher();" type="radio" id="sa" name="typecontrat" value="Salarié" checked>
                                <label>Salarié</label>

                                <input onclick="afficher();" type="radio" id="st" name="typecontrat" value="Sous-traitant">
                                <label>Sous-Traitant</label>

                                <input onclick="afficher();" type="radio" id="ep" name="typecontrat" value="En portage">
                                <label>En portage</label>
                            </div>
                            <div>
                                <input type='number' id='salaire' value="<?php if (isset($_POST['salaire'])){echo $_POST['salaire'];} ?>" name="salaire" placeholder="Salaire" min="0"><br><br>
                                <input type='number' id='tarif' value="<?php if (isset($_POST['tarif'])){echo $_POST['tarif'];} ?>" name="tarif" placeholder="Tarif" min="0" style="display: none"><br><br>
                            </div>
                            <script>
                            function afficher(){
                              var soustraitant = document.getElementById('st');
                              var enportage = document.getElementById('ep');
                              var salarie = document.getElementById('sa');

                                if (salarie.checked)
                                    {
                                    document.getElementById('salaire').style.display='block';
                                    document.getElementById('tarif').style.display='none';
                                    document.getElementById('salaire').setAttribute('required','required');
                                    }

                                if (soustraitant.checked){
                                    document.getElementById('salaire').style.display='none';
                                    document.getElementById('tarif').style.display='block';
                                    document.getElementById('tarif').setAttribute('required','required');
                                    }

                                if (enportage.checked) {
                                    document.getElementById('salaire').style.display='block';
                                    document.getElementById('tarif').style.display='block';
                                    document.getElementById('salaire').setAttribute('required','required');
                                    document.getElementById('tarif').setAttribute('required','required');
                                    }

                            }
                          </script>
                    <p>* Champs obligatoire</p>
                    <input type='submit' name='ajoutercontrat' value='Ajouter'>
                </fieldset>
            </form>
        </table>
    </div>
</div>
