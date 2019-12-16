<?php

namespace AmoNG;

class Account extends Authorization
{
  public static $subDomain;
  public static $APIurl;
  private $authorized = 0;
  const URL_METHOD = '/api/v2/account';

  public function __construct(/*string $subDomain*/)
  {
    //$this->setSubDomain($subDomain);
    parent::__construct();
  }
  public function setSubDomain(string $subDomain): Account
  {
    self::$subDomain = $subDomain;
    self::$APIurl ='https://' . self::$subDomain . '.amocrm.ru';
    Authorization::$cfgFile = self::$subDomain . ".json";
    
    return $this;
  }
  public function get(string $params = null)
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
  public function getCustomFileds()
  {
    return $this->get('custom_fields');
  }
  public function getUsers()
  {
    return $this->get('users');
  }
  public function getPipelines()
  {
    return $this->get('pipelines');
  }
  public function getGroups()
  {
    return $this->get('groups');
  }
  public function getNoteTypes()
  {
    return $this->get('note_types');
  }
  public function getTaskTypes()
  {
    return $this->get('task_types');
  }
  public function getFreeUsers()
  {
    return $this->get('users&free_users=Y');
  }
  public function authorize()
  {
    if($this->authorized) throw new \Exception('This accout has authorized yet');
    $this->authorized = 1;

    return $this->authorization();
  }

}