<?php
if(!(empty($_SESSION))){
  //var_dump($_SESSION['role_user']);
  if($_SESSION['role_user'] !== 'Admin') {
    //echo "impossible d'acceder a cette page";
    header("location:index.php?uc=consultant&action=afficherCraConsultant");
    exit();
  }
}
// <!--
//     <script>
//       alert("impossible d'acceder a cette page");
//       window.location.href='index.php?uc=consultant&action=afficherCraConsultant';
//     </script>
//   -->

?>
