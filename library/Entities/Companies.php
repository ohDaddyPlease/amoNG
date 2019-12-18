<?php

namespace AmoNG;

use AmoNG\Authorization;

/**
 * класс для работы с компаниями
 */
class Companies extends Authorization
{
  const URL_METHOD = '/api/v2/companies';
  public function __construct()
  {
    parent::__construct();
  }
  public function add(array $data = [
    'name'                => 'Новая компания', //required
    'created_at'          => '',
    'updated_at'          => '',
    'responsible_user_id' => '',
    'created_by'          => '',
    'tags'                => '',
    'leads_id'            => [],
    'customers_id'        => '',
    'contacts_id'         => '',
    'custom_fields'       => [
      'id'     => '',
      'values' => [
        'value'   => '',
        'enum'    => '',
        'subtype' => ''
      ]
    ]
  ])
  {
    return $this->request([
      'url'    => self::URL_METHOD,
      'method' => 'POST',
      'data'   => ['add' => [$data]]
    ])['_embedded']['items'];
  }
  public function update(array $data = [
    'id' => '', //required
    'unlink'         => [
      'contacts_id'  => '',
      'leads_id'     => '',
      'customers_id' => []
    ],
    'name'                => 'Обновленная компания',
    'created_at'          => '',
    'updated_at'          => '', //required
    'responsible_user_id' => '',
    'created_by'          => '',
    'tags'                => '',
    'leads_id'            => [],
    'customers_id'        => '',
    'contacts_id'         => '',
    'custom_fields'       => [
      'id'     => '',
      'values' => [
        'value'   => '',
        'enum'    => '',
        'subtype' => ''
      ]
    ]
  ])
  {
    return $this->request([
      'url'    => self::URL_METHOD,
      'method' => 'POST',
      'data'   => ['update' => [$data]]
    ])['_embedded']['items'];
  }
  public function get(array $params = [
    'id'                  => '', //может быть массивом
    'limit_rows'          => '',
    'limit_offset'        => '',
    'responsible_user_id' => '', //может быть массивом
    'query'               => ''
  ])
  {
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
      'url' => self::URL_METHOD . '?' . $urn,
      'method' => 'GET'
    ])['_embedded']['items'];
  }
}
