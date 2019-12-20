<?php

namespace AmoNG\Entities;

use AmoNG\Http\Authorization;

abstract class AbstractEntity extends Authorization
{

  /**
   * URN, на который будет отправлен запрос
   */
  protected $api_method;

  public function __construct()
  {
    parent::__construct();
  }

  /**
   * метод для добавления новой сущности в систему
   *
   * @param array $data массив, содержащий структуру данных
   * для новой сущности
   * @return array
   */
  public function add(array $data): array
  {
    $response =  $this->request([
      'url'    =>  $this->api_method,
      'method' =>             'POST',
      'data'   => ['add' => [$data]]
    ])['_embedded']['items'];

    if(!$response)
      return ['errors' => 'error'];

    if(count($response) == 1)
      return $response[0];

    return $response;
  }

    /**
   * метод для обновления сущности в системе
   *
   * @param array $data массив, содержащий структуру данных
   * для обновления сущности
   * @return array
   */
  public function update(array $data): array
  {
    $response = $this->request([
      'url'    =>     $this->api_method,
      'method' =>                'POST',
      'data'   => ['update' => [$data]]
    ])['_embedded']['items'];

    if(!$response)
      return ['errors' => 'error'];

    if(count($response) == 1)
      return $response[0];

    return $response;
  }

  //допилить: если сущность найдена одна, то обрезать ее по [0] 
  /**
   * метод для получения сущности из системы
   *
   * @param array $data необязательный параметр. необходим 
   * для получения сущности по заданным параметрам
   * @return array
   */
  public function get(array $data = []): array
  {
    $params = '';
    foreach ($data as $key => $val) {
      if (!$val) continue;
      $params .= $key . '=';
      if (is_array($val))
        $params .= implode(',', $val);
      else
        $params .= $val;
      if (next($data))
        $params .= '&';
    }
    
    $response = $this->request([
      'url' => $this->api_method . '?' . $params,
      'method' =>                          'GET'
    ])['_embedded']['items'];

    if(!$response)
      return ['errors' => 'error'];

    if(count($response) == 1)
      return $response[0];

    return $response;
  }
}
