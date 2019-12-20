<?php

namespace AmoNG\Telephony;

use AmoNG\Http\Authorization;

class Calls extends Authorization
{

  /**
   * URN, на который будет отправлен запрос
   */
  protected $method_url = '/api/v2/calls';

  public function __construct()
  {
    parent::__construct();
  }

  /**
   * метод добавления звонков в сущность
   *
   * @param array $data
   * @return array
   */
  public function add(array $data): array
  {
    return $this->request([
      'url'    => $this->method_url,
      'method' => 'POST',
      'data'   => ['add' => [$data]]
    ])['_embedded']['items'];
  }
}