<?php

namespace AmoNG;

class Account
{

  public $subDomain;
  public $APIurl;

  public function __construct()
  {
    return $this;
  }

  public function setSubDomain(string $subDomain): Account
  {
    $this->subDomain = $subDomain;
    $this->APIurl ='https://' . $this->subDomain . '.amocrm.ru';
    return $this;
  }

}