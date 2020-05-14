<?php


if(!isset($_REQUEST['action'])){
	$_REQUEST['action'] = 'afficherLogin';
}
$action = $_REQUEST['action'];

switch($action){

    case 'afficherLogin': {
			//var_dump($_POST);
			//var_dump($trz_utilisateurDao->getInfoUser($_POST['login'],$_POST['password']));
			include("vues/v_connexion.php");
    	break;
    }

		case 'connexionUser': {
				//var_dump($_POST);
				$login = $_POST['login'];
				$password = $_POST['password'];
				//var_dump($_SESSION);

					if($trz_utilisateurDao->isInDB($login,$password)){	// && isset($_POST['password'])
						$utilisateur = $trz_utilisateurDao->getInfoUser($login, $password);
						//var_dump($consultant);
						$_SESSION['timestamp'] = time();
						$_SESSION['id_user'] = $utilisateur[0]['id'];

						$_SESSION['role_user'] = $trz_utilisateurDao->getRoleUser($_SESSION['id_user']);
						$_SESSION['role_user'] = $_SESSION['role_user'][0]['name'];
						$_SESSION['nom_user'] = $utilisateur[0]['nom_user'];
						$_SESSION['prenom_user'] = $utilisateur[0]['prenom_user'];
						//var_dump($_SESSION);
						//header('location:index.php?uc=commercial&action=afficherTableau');

						if(isset($_SESSION['role_user']) == Admin){
							header('location:index.php');

						}else{
							header('location:index.php?uc=consultant&action=afficherCraConsultant');

						}


						// 	switch(isset($_SESSION['role_user'])){
						// 		case 'Admin':{
						// 			break;
						// 		}
						// 		case 'Consultant':{
						// 		break;
						// 		//exit();
						//
						// 		}

					}else{
						//var_dump(date('d/m/Y'));
						?>
						<script type="text/javascript">
							alert('Veuillez entrer des informations correctes');
							window.location.href='index.php?uc=connexion&action=afficherLogin';
						</script>
						<?php
						//header('location:index.php?uc=connexion&action=afficherLogin');

				}
				break;
    }


		case 'logOut': {
				$_SESSION = array();
				session_destroy();
				header('location:index.php?uc=connexion&action=afficherLogin');
				//var_dump($_SESSION);
				echo 'Vous êtes maintenant déconnecté';
    break;
    }





}
