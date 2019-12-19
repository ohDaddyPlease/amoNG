<?php

namespace AmoNG\Entities;

use AmoNG\Entities\AbstractEntity;

class Calls extends AbstractEntity
{
  protected $method_url = '/api/v2/calls';

  public function __construct()
  {
    parent::__construct();
  }

  public function get() { }
  public function update() { }
}