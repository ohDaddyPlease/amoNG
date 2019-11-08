<?php

class AmoAccount{

    public $subDomain;
    public $APIurl;

    public function __construct($accountName){
        $this->APIurl = 'https://'.$accountName.'.amocrm.ru';
    }

    public function setSubDomain($subDomain){
      $this->subDomain = $subDomain;
      $this->APIurl ='https://' . $this->subDomain . '.amocrm.ru';
      return $this->APIurl;
    }

}