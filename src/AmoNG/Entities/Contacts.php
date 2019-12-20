<?php

namespace AmoNG\Entities;

use AmoNG\Entities\AbstractEntity;

class Contacts extends AbstractEntity
{

  /**
   * URN, на который будет отправлен запрос
   */
  protected $api_method = '/api/v2/contacts';

  public function __construct()
  {
    parent::__construct();
  }
}
