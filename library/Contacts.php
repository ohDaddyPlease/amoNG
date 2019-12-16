<?php

namespace AmoNG;

use AmoNG\Authorization;

class Contacts extends Authorization
{

  const URL_METHOD = '/api/v2/contacts';

  public function __construct()
  {
    parent::__construct();
  }

  public function add(array $data = [
      'name'                => 'Новый контакт',  //required
      'first_name'          => '',
      'last_name'           => '',
      'created_at'          => '',
      'updated_at'          => '',
      'responsible_user_id' => '',
      'created_by'          => '',
      'company_name'        => '',
      'tags'                => '',
      'leads_id'            => [],
      'customers_id'        => '',
      'company_id'          => '',
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
    'unlink'              => [
      'leads_id'     => '',
      'company_id'   => '',
      'customers_id' => []
    ],
    'name'                => 'Обновленный контакт',
    'first_name'          => '',
    'last_name'           => '',
    'created_at'          => '',
    'updated_at'          => '', //required
    'responsible_user_id' => '',
    'created_by'          => '',
    'company_name'        => '',
    'tags'                => '',
    'leads_id'            => [],
    'customers_id'        => '',
    'company_id'          => '',
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
    foreach($params as $key => $val)
    {
      if(!$val) continue;
      $urn .= $key . '=';
      if(is_array($val))
        $urn .= implode(',', $val);
      else
        $urn .= $val;
      if(next($params))
        $urn .= '&';
    }

    return $this->request([
      'url' => self::URL_METHOD . '?' . $urn,
      'method' => 'GET'
    ])['_embedded']['items'];
  }
}