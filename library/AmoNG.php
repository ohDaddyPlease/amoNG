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

  public function __construct($subDomain){
    $this->account = new Account($subDomain);
    $this->auth = new Authorization;
    $this->request = new Request($this->auth);
    $this->logger = new Logger;
    $this->leads = new Leads;

  }

}