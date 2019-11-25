<?php

class AmoAccount{

    public $subDomain;
    public $APIurl;

<<<<<<< HEAD
    public function __construct(){
        return $this;
    }

    public function setSubDomain(string $subDomain){
      $this->subDomain = $subDomain;
      $this->APIurl ='https://' . $this->subDomain . '.amocrm.ru';
      return $this;
=======
    public function __construct($accountName){
        $this->APIurl = 'https://'.$accountName.'.amocrm.ru';
    }

    public function setSubDomain($subDomain){
      $this->subDomain = $subDomain;
      $this->APIurl ='https://' . $this->subDomain . '.amocrm.ru';
      return $this->APIurl;
>>>>>>> 4af2d459a77d554bf0e7e813b0e30688a4ac81ab
    }

}