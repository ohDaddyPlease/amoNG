<?php

namespace AmoNG\Entities;

use AmoNG\Entities\AbstractEntity;
 
class Leads extends AbstractEntity
{

  /**
   * URN, на который будет отправлен запрос
   */
  protected $api_method = '/api/v2/leads';

  /**
   * стандартные статусы этапов воронок
   */
  const STATUS = [
    '142'         =>      'Успешно реализовано',
    '143'         => 'Закрыто и не реализовано',
    'CLOSED_WON'  =>                      '142',
    'CLOSED_LOST' =>                      '143'
  ];

  public function __construct()
  {
    parent::__construct();
  }
}
