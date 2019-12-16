<?php

namespace AmoNG;
use AmoNG\Account;
use AmoNG\Logger;

class AmoNG
{
  public $account;
  public $request;
  public $logger;
  public $auth;
  public $leads;
  public $contacts;
  private $modules;

  public function __construct(string $modules){
    $this->modules = explode(',', $modules);
    foreach($this->modules as $key => $module)
    {
      $this->modules[$key] = trim($module);
    }
    $this->modules = array_flip($this->modules);

    if(key_exists('full', $this->modules))
    {
      $this->account = new Account();

      $this->auth = new Authorization;
      $this->request = new Request($this->auth);

      $this->logger = new Logger;
      $this->leads = new Leads;
      $this->contacts = new Contacts;

      return;
    }


  }

}