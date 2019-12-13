<?php

namespace AmoNG;

class Account extends Authorization
{

  public static $subDomain;
  public static $APIurl;
  private $authorized = 0;
  const URL_METHOD = '/api/v2/account';

  public function __construct(string $subDomain)
  {
    $this->setSubDomain($subDomain);
    parent::__construct();
  }

  public function setSubDomain(string $subDomain): Account
  {
    self::$subDomain = $subDomain;
    self::$APIurl ='https://' . self::$subDomain . '.amocrm.ru';
    
    return $this;
  }

  public function getAccount(string $params = null)
  {
    $response = $this->request( //here $response = $this->request->request( 
      [
        'url' => self::URL_METHOD . ($params ? '?with=' . $params :'')
      ]
    );
    if($params !== null) 
      return current($response['_embedded']);

    return $response;

  }

  public function getCustomFileds(){
    return $this->getAccount('custom_fields');
  }

  public function getUsers(){
    return $this->getAccount('users');
  }

  public function getPipelines(){
    return $this->getAccount('pipelines');
  }

  public function getGroups(){
    return $this->getAccount('groups');
  }

  public function getNoteTypes(){
    return $this->getAccount('note_types');
  }

  public function getTaskTypes(){
    return $this->getAccount('task_types');
  }

  public function getFreeUsers(){
    return $this->getAccount('users&free_users=Y');
  }

  public function authorize()
  {
    if($this->authorized) throw new \Exception('This accout has authorized yet');
    $this->authorized = 1;

    return $this->authorization();
  }

}