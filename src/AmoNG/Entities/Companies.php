<?php

namespace AmoNG\Entities;

use AmoNG\Entities\AbstractEntity;
 
/**
 * класс для работы с компаниями
 */
class Companies extends AbstractEntity
{

  /**
   * URN, на который будет отправлен запрос
   */
  protected $api_method = '/api/v2/companies';

  public function __construct()
  {
    parent::__construct();
  }
  
}
