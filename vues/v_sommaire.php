
<?php


if(!(empty($_SESSION))){?>
  <div id="main">
    <span style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776; Menu</span>
  </div>
  <div id="mySidenav" class="sidenav">
    <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
    <a href="index.php?uc=connexion&action=logOut" class="sidebarA">Déconnexion</a>
<?php
  if($_SESSION['id_user'] == "0"){?>
    <a href="index.php?uc=commercial&action=afficherTableau" class="sidebarA">Commerciaux</a>
    <a href="index.php?uc=contrat&action=affichercontrat" class="sidebarA">Contrats</a>
    <a href="index.php?uc=commission&action=afficherCommission" class="sidebarA">Commissions</a>
    <a href="index.php?uc=client&action=afficherclient" class="sidebarA">Clients</a>
    <a href="index.php?uc=depense&action=afficherDepense" class="sidebarA">D&eacute;penses</a>
    <a href="index.php?uc=consultant&action=afficherConsultant" class="sidebarA">Consultants</a>
    <a href="index.php?uc=cra&action=choisirCra" class="sidebarA">Demande de CRA</a>

<?php
}elseif($_SESSION['id_user'] != "0"){?>
    <a href="index.php?uc=consultant&action=afficherCraConsultant" class="sidebarA">CRA</a>

<?php
  }
}else{}
?>

</div>


<script>

function openNav() {
  document.getElementById("mySidenav").style.width = "200px";
  document.getElementById("main").style.marginLeft = "200px";
}

function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
  document.getElementById("main").style.marginLeft= "0";
}
</script>
