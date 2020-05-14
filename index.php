<html lang="fr">
<!--
<script type="text/javascript" src="javascript.js">
  alert('Votre session a expiré. Veuillez vous reconnecter.');
</script>
<meta http-equiv="refresh" content="900; url='index.php?uc=connexion&action=logOut'" /> -->

<!--au bout de 900 secondes => 15 min d'inactivité, déconnexion auto-->
<?php
session_start();

include("vues/v_entete.php");

// include("vues/v_entete.php");
 //fichier qui contient l'ensemble des classes
require_once ("modele/include.php");
//$contrat = new daoTrz_Contrat();
$clientDao = new DaoTrz_Client();
$contactDao = new DaoTrz_Contact();
$factureDao = new Trz_FactureDao();
$payerDao = new Trz_PayerDao();
$financeDAO = new financeDAO();
$UconsultantDao = new UconsultantDao();
$commerciauxDao=new trz_commerciauxDao();
$craDAO = new trz_craDAO();
$trz_utilisateurDao = new trz_utilisateurDao();
$daoTrz_Contrat = new daoTrz_Contrat();
// coonexion à la base de données avec l'ORM
R::setup('pgsql:host=127.0.0.1;dbname=omicrone','postgres','root');
R::freeze(true);

if (!isset($_REQUEST['uc'])) {
  if(empty($_SESSION)){
    $_REQUEST['uc'] = 'connexion';
  }else{
    $_REQUEST['uc'] = 'commercial';
  }
}
$uc = $_REQUEST['uc'];
switch ($uc) {
    case 'commercial': {
        //include("vues/v_entete.php");
        include("session/verif_inactivite.php");
        include("session/verif_connexion.php");
        include("controleurs/c_commerciaux.php");
        break;
        }
    case 'depense' : {
        //include("vues/v_entete.php");
        include("session/verif_inactivite.php");
        include("session/verif_connexion.php");
        include("controleurs/c_depense.php");
        break;
        }
    case 'commission' : {
        //include("vues/v_entete.php");
        include("session/verif_inactivite.php");
        include("session/verif_connexion.php");
        include("controleurs/c_commission.php");
        break;
        }
    case 'contrat':{
        //include("vues/v_entete.php");
        include("session/verif_inactivite.php");
        include("session/verif_connexion.php");
        include("controleurs/c_contrat.php");
        break;}
    case 'client':{
        //include("vues/v_entete.php");
        include("session/verif_inactivite.php");
        include("session/verif_connexion.php");
        include 'controleurs/c_client.php';
        break;
        }
    case 'facture':{
        //include("vues/v_entete.php");
        include("session/verif_inactivite.php");
        include("session/verif_connexion.php");
        include 'controleurs/c_facture.php';
        break;}
    case 'consultant':{
        //include("vues/v_entete.php");
        include("session/verif_inactivite.php");
        include("session/verif_connexion.php");
        include 'controleurs/c_consultant.php';
        break;}
    case 'cra':{
        //include("vues/v_entete.php");
        include("session/verif_inactivite.php");
        //include("session/verif_connexion.php");
        include 'controleurs/c_cra.php';}
    case 'connexion':{
        include 'controleurs/c_connexion.php';
        break;}
}
?>
