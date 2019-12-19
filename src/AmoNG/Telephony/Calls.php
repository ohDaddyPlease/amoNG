<?php

namespace AmoNG\Telephony;

use AmoNG\Http\Authorization;

class Calls extends Authorization
{
  protected $method_url = '/api/v2/calls';

  public function __construct()
  {
    parent::__construct();
  }

  public function add(array $data)
  {
    return $this->request([
      'url'    => $this->method_url,
      'method' => 'POST',
      'data'   => ['add' => [$data]]
    ])['_embedded']['items'];
  }
}