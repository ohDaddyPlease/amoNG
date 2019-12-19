<?php

namespace AmoNG\Entities;

use AmoNG\Entities\AbstractEntity;
 
/**
 * класс для работы с компаниями
 */
class Companies extends AbstractEntity
{
  protected $method_url = '/api/v2/companies';

  public function __construct()
  {
    parent::__construct();
  }
  
}
