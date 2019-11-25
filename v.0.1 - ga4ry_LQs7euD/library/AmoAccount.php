<?php

class AmoAccount{

    public $subDomain;
    public $APIurl;

    public function __construct(){
        return $this;
    }

    public function setSubDomain(string $subDomain){
      $this->subDomain = $subDomain;
      $this->APIurl ='https://' . $this->subDomain . '.amocrm.ru';
      return $this;
    }

}