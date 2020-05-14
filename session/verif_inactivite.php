<?php
if(isset($_SESSION['timestamp'])){
  if(time() - $_SESSION['timestamp'] > 900) { //au bout de 900 secondes (15min) d'inactivités -> deconnexion auto
      ?>
        <script>
          alert("Votre session a expiré. Veuillez vous reconnecter.");
          window.location.href='index.php?uc=connexion&action=logOut';
        </script>
      <?php
      exit;
  } else {
      $_SESSION['timestamp'] = time(); //set new timestamp
  }
}else{}

?>
