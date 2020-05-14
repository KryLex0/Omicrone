<?php
class typecontrat {

private $_id;
private $_typecontrat;


public function __construct($unId,$unTypeContrat)

{

    $this->_id = $unId;
    $this->__typecontrat=$unTypeContrat;

}

public function getId(){
    return $this->_id;
}

public function getTypeContrat(){
    return($this->_typecontrat);
}
}
