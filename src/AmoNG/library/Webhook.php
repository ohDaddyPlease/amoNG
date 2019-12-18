<?php

namespace AmoNG;

class Webhook
{
  public function get()
  {
    return $_GET;
  }

  public function post()
  {
    return json_decode(file_get_contents('php://input'), 1);
  }

  public function all()
  {
    return $_REQUEST;
  }
}
