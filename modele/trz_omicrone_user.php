<?php
class trz_omicrone_user {

private $_id;
private $_nom_user;
private $_prenom_user;
private $_tel_user;
private $_mail_user;
private $_adresse_user;
private $_ville_user;
private $_cp_user;

public function __construct($unNom,$unPrenom,$uneAdresse,$uneVille,$unCp,$unTel, $unEmail)

{

    $this->_nom_user = $unNom;
    $this->_prenom_user=$unPrenom;
    $this->_adresse_user=$uneAdresse;
    $this->_ville_user=$uneVille;
    $this->_cp_user=$unCp;
    $this->_tel_user=$unTel;
    $this->_mail_user=$unEmail;

}

public function getId(){
    return $this->_id;
}

public function getNom(){
    return($this->_nom_user);
}



public function getPrenom(){
    return($this->_prenom_user);
}

public function getAdresse(){
    return($this->_adresse_user);
}

public function getVille(){
    return($this->_ville_user);
}

public function getCp(){
    return($this->_cp_user);
}

public function getTel(){
    return($this->_tel_user);
}

public function getEmail(){
    return($this->_mail_user);
}
}
