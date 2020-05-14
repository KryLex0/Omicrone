<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Base files
require 'vendor/PHPMailer/src/Exception.php';
require 'vendor/PHPMailer/src/PHPMailer.php';
require 'vendor/PHPMailer/src/SMTP.php';


class trz_craDAO{

    public function add($cra){
        $TJF=$cra->getJF();
        $TJC=$cra->getJC();
        $TJM=$cra->getJM();
        $astreinte=$cra->getAstreinte();
        $id=$cra->getOContrat()->getidContrat();
        $periode=$cra->getPeriode();
        $interv = $cra->getInterv();

        $cra=r::dispense('trz_cra');
        $cra->totaljfacturable_cra=$TJF;
        $cra->totaljmaladie_cra=$TJM;
        $cra->totaljconge_cra=$TJC;
        $cra->astreinte_cra=$astreinte;
        $cra->idcontrat=$id;
        $cra->periode_cra = $periode;
        $cra->intervention_cra = $interv;
        r::store($cra);
    }

    public function collectionCRA(){
        $collection = array();
        $lesCras= r::getAll('select trz_consultant.idutilisateur as idconsultant, trz_omicrone_user.nom_user as nom, trz_omicrone_user.prenom_user as prenom, trz_omicrone_user.adresse_user as adrcons, trz_omicrone_user.ville_user as villecons, trz_omicrone_user.cp_user as cp, trz_omicrone_user.tel_user as tel, trz_omicrone_user.mail_user as email, trz_consultant.typecontrat as typecontrat, trz_consultant.salaire as salaire, trz_consultant.tarif as tarif,
        trz_contrat.id as idcontrat, datedebut, datefin, mission_contrat,
        trz_client.id as idclient, idcontact, raisonsocial, siret, trz_client.adr as clientadr, trz_client.ville as clientville, codepostal,
        trz_cra.id as idcra, totaljfacturable_cra, totaljmaladie_cra, totaljconge_cra, astreinte_cra, periode_cra, intervention_cra,
        trz_contact.id as idcontact, email1, email2, email3, bureau, fax, tel3
               from trz_omicrone_user, trz_consultant, trz_contrat, trz_client, trz_cra, trz_contact where
               trz_omicrone_user.id=trz_consultant.idutilisateur and
               trz_contact.id=trz_client.idcontact and
               trz_client.id=trz_contrat.idclient and
               trz_consultant.idutilisateur=trz_contrat.idutilisateur and
               trz_cra.idcontrat=trz_contrat.id');
        for ($i=0; $i<=count($lesCras)-1; $i++) {
                $objcontact = new trz_contact ($lesCras[$i]['email1'],$lesCras[$i]['email2'],$lesCras[$i]['email3'],$lesCras[$i]['bureau'],$lesCras[$i]['fax'],$lesCras[$i]['tel']);
                $objclient = new trz_client ($lesCras[$i]['raisonsocial'], $objcontact, $lesCras[$i]['siret'], $lesCras[$i]['clientadr'], $lesCras[$i]['clientville'], $lesCras[$i]['codepostal']);
                $objconsultant = new trz_consultant ($lesCras[$i]['nom'], $lesCras[$i]['prenom'], $lesCras[$i]['adresse'], $lesCras[$i]['ville'], $lesCras[$i]['cp'], $lesCras[$i]['tel'], $lesCras[$i]['email'], $lesCras[$i]['typecontrat'], $lesCras[$i]['salaire'], $lesCras[$i]['tarif']);
                $objcontrat = new trz_contrat($lesCras[$i]['idcontrat'], $objclient, $objconsultant, $lesCras[$i]['datedebut'],$lesCras[$i]['datefin'], $lesCras[$i]['mission_contrat'],$lesCras[$i]['salaire'], $lesCras[$i]['tarif'], $lesCras[$i]['typecontrat']);
                $objcra = new trz_cra ($lesCras[$i]['totaljfacturable_cra'], $lesCras[$i]['totaljmaladie_cra'],$lesCras[$i]['totaljconge_cra'], $objcontrat, $lesCras[$i]['astreinte_cra'], $lesCras[$i]['periode_cra'], $lesCras[$i]['intervention_cra']);
                $collection[]=$objcra;
            }
            return $collection;
    }


    public function craexists($cra, $chemin){
      $periode = $cra->getPeriode();

      $req =  r::getAll("select EXISTS (select trz_cra.id ,idcontrat, totaljfacturable_cra, totaljmaladie_cra, totaljconge_cra, astreinte_cra, periode_cra, intervention_cra from trz_cra where chemin='$chemin' and periode_cra='$periode')");
      return $req[0]["exists"];
    }



    public function idcrafromobject($cra){ //recupere l'id du cra en fonction de l'objet
        $consultantDao = new trz_consultantDao();

        $TJF= $cra->getJF();
        $TJM= $cra->getJM();
        $TJC= $cra->getJC();
        $astreinte = $cra->getAstreinte();
        $idcontrat = $cra->getOContrat()->getidContrat();
        $periode = $cra->getPeriode();
        $interv = $cra->getInterv();

        $idcra=r::find("trz_cra", "totaljfacturable_cra = ? and totaljmaladie_cra = ? and totaljconge_cra = ? and astreinte_cra = ? and idcontrat = ? and periode_cra = ? and intervention_cra = ?",
        array($TJF, $TJM, $TJC, $astreinte, $idcontrat,  $periode, $interv));

        foreach($idcra as $unidcra){
            return($unidcra->id);
        }
    }

    public function getcraformid($idcra){ //retourne un objet cra en fonction de son id
        $uncra = R::load('trz_cra',$idcra);
        $idcontrat = $uncra->idcontrat;
        $uncontrat = R::load('trz_contrat', $idcontrat);
        $idclient = $uncontrat->idclient;
        $client = R::load('trz_client', $idclient);
        $idcontact = $client->idcontact;
        $contact = r::load('trz_contact', $idcontact);
        $uncontact = new trz_contact ($contact->email1, $contact->email2, $contact->email3, $contact->bureau, $contact->fax, $contact->tel3);
        $idconsultant = $uncontrat->idconsultant;
        $unclient =  new trz_client($client->raisonsocial, $uncontact, $client->siret, $client->adr, $client->ville, $client->codepostal);

        $consultant = r::getAll('select * from trz_omicrone_user join trz_consultant on trz_omicrone_user.id=trz_consultant.idutilisateur where idutilisateur='.$idconsultant.'');
        for ($i=0; $i<count($consultant);$i++){
            $unconsultant = new trz_consultant ($consultant[$i]['nom'], $consultant[$i]['prenom'], $consultant[$i]['adresse'], $consultant[$i]['ville'], $consultant[$i]['cp'], $consultant[$i]['tel'], $consultant[$i]['email'], $consultant[$i]['typecontrat'], $consultant[$i]['salaire'], $consultant[$i]['tarif']);
        }
        $contrat = new trz_contrat($uncontrat->id, $unclient, $unconsultant, $uncontrat->datedebut,$uncontrat->datefin, $uncontrat->mission_contrat, $uncontrat->salaire, $uncontrat->tarif, $uncontrat->typecontrat);

        $cra = new trz_cra ($uncra->totaljfacturable_cra, $uncra->totaljmaladie_cra, $uncra->totaljconge_cra, $uncra->astreinte_cra, $contrat, $uncra->periode_cra, $uncra->intervention_cra);
         return $cra;

    }

    public function getJFfromidcontrat($idcontrat, $mois){
        $lesJF = r::getAll("select totaljfacturable_cra as jf from trz_cra where idcontrat='$idcontrat' and periode_cra='$mois'");
        return $lesJF[0]['jf'];
    }

    public function crasConsultants($idconsultant){
        $tabcras = array();
        $lescras = r::getAll("select distinct periode_cra from trz_cra join trz_contrat on trz_cra.idcontrat=trz_contrat.id join trz_omicrone_user on trz_contrat.idutilisateur=trz_omicrone_user.id join trz_consultant on trz_omicrone_user.id=trz_consultant.idutilisateur where trz_consultant.idutilisateur=$idconsultant");
        for($i=0; $i<sizeof($lescras);$i++){
           $unedate= $lescras[$i]['periode_cra'];
           $tabcras[] = $unedate;
        }
        return $tabcras;
    }

    public function insererCra($cra,$filePath){
        $id = $this->idcrafromobject($cra);
        $cra = r::load('trz_cra',$id);
        $cra->chemin = $filePath;
        r::store($cra);
    }

    public function cheminCra($periode, $idconsultant){
       $chemin =  r::getAll("select distinct chemin from trz_cra join trz_contrat on trz_cra.idcontrat = trz_contrat.id join trz_omicrone_user on trz_contrat.idutilisateur = trz_omicrone_user.id join trz_consultant on trz_omicrone_user.id = trz_consultant.idutilisateur where periode_cra = '$periode' and trz_consultant.idutilisateur = $idconsultant ");
        return $chemin[0]["chemin"];
    }

    public function testJourFerie($date){
      $dateFerie = R::getAll('select * from trz_dateferie');
      $dateDay = date('d/m/Y',$date);

      for($i=0;$i<count($dateFerie);$i++){
        $dateJour[$i] = date('d/m/Y', strtotime($dateFerie[$i]['datejour']));

        if($dateDay == $dateJour[$i]){
          //var_dump($dateJour[$i]);
          return(true);
        }
      }
    }


    public function getIdCra($idcontrat,$date){
      $periode = explode("-", $date);
      $periode1 = $periode[1] . $periode[0];
      $idcra=r::getAll("select id from trz_cra where idcontrat=$idcontrat and periode_cra='$periode1' and chemin IS NOT NULL");
      return $idcra[0]['id'];
    }


////////////////////////////////

        public function craRempli($idutilisateur,$idcontrat,$dateD){
          $test = R::getAll("select COUNT(1) from trz_dates_contrat where idutilisateur='$idutilisateur' and idcontrat='$idcontrat' and date_min='$dateD' and complet='TRUE'");
          if($test[0]['count'] == 1){
            return true;
          }else{
            return false;
          }
        }
        public function craEnvoye($idutilisateur,$idcontrat,$dateD){
          $test = R::getAll("select COUNT(1) from trz_dates_contrat where idutilisateur='$idutilisateur' and idcontrat='$idcontrat' and date_min='$dateD' and aremplir='TRUE' and complet='FALSE'");

          if($test[0]['count'] == 1){
            return true;
          }else{
            return false;
          }
        }

        public function envoiDemandeCraConsultant($dateD){
          list($annee1, $mois1, $jour1) = explode('-', $dateD);
          $jour1 = '01';
          $dateD = $annee1 . '-' . $mois1 . '-' . $jour1;

          R::getAll("update trz_dates_contrat set aremplir='TRUE' where date_min='$dateD' and complet='FALSE'");
        }


/*
        public function demandeRemplirCra($idContrat,$dateMin){
          r::getAll("update trz_dates_contrat set aremplir='TRUE' where date_min='$dateMin' and idcontrat='$idContrat' and complet='FALSE'");
        }
        */

        public function getContratARemplir($idconsultant) { //retourne un contrat en fonction de son id
            $dates = r::getAll("select * from trz_dates_contrat where idutilisateur='$idconsultant' and aremplir='TRUE' and complet='FALSE'");
            return $dates;
        }

        public function completerCra($idutilisateur, $idContrat, $dateMin){
          r::getAll("update trz_dates_contrat set aremplir='FALSE', complet='TRUE' where date_min='$dateMin' and idcontrat='$idContrat' and idutilisateur='$idutilisateur'");
        }

////////////////////////////////


    public function relanceCra($mailUser){
      $mail = new PHPMailer(true);

      try {
        $mail->SMTPDebug = 0;                      // Enable verbose debug output
        $mail->isSMTP();                                            // Send using SMTP
        $mail->Host       = 'smtp.office365.com';                    // Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   // Enable SMTP authentication
        $mail->Username   = 'toto95170@hotmail.fr';                     // SMTP username
        $mail->Password   = 'mada12345';                               // SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 587;                                    // TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        //Recipients
        $mail->setFrom('toto95170@hotmail.fr', 'Admin');
        $mail->addAddress($mailUser, 'Adam M');     // Add a recipient
        //$mail->addAddress('ellen@example.com');               // Name is optional
        //$mail->addReplyTo('info@example.com', 'Information');
        //$mail->addCC('cc@example.com');
        //$mail->addBCC('bcc@example.com');

        // Attachments
        //$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
        //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name

        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = 'Notification sur votre Espace Personnel Omicrone';
        $mail->Body    = 'Bonjour,</br></br>Vous avez des CRA en attente sur votre espace personnel Omicrone.</br>
        Pour y acceder, connectez vous sur le lien suivant: http://index.php?uc=connexion&action=afficherLogin
        </br>Merci de les completer au plus vite.
        </br></br>Cordialement';
        //$mail->AltBody = 'Bonjour Vous avez des CRA en attente sur votre espace personnel Omicrone. Merci de les completer au plus vite. Cordialement';
        //var_dump($mail);

        $mail->send();
      } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
      }

    }


}



?>
