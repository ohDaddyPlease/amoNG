<?php

namespace AmoNG\Entities;

use AmoNG\Http\Authorization;
 
abstract class AbstractEntity extends Authorization
{

  private $method_url;

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

  public function update(array $data)
  {
    return $this->request([
      'url'    => $this->method_url,
      'method' => 'POST',
      'data'   => ['update' => [$data]]
    ])['_embedded']['items'];
  }

  public function get(array $params = [])
  {
    $test = $this->method_url;
    $urn = '';
    foreach ($params as $key => $val) {
      if (!$val) continue;
      $urn .= $key . '=';
      if (is_array($val))
        $urn .= implode(',', $val);
      else
        $urn .= $val;
      if (next($params))
        $urn .= '&';
    }
    return $this->request([
      'url' => $this->method_url . '?' . $urn,
      'method' => 'GET'
    ])['_embedded']['items'];
  }

}