<?php
if(empty($_SESSION)){?>
  <script>
    alert("Vous ne pouvez pas acceder à cette page. Veuillez vous connecter.");
    window.location.href='index.php?uc=connexion&action=afficherLogin';
  </script>
<?php
  }
?>
