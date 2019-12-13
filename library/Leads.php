<?php

namespace AmoNG;

use AmoNG\Authorization;

class Leads extends Authorization
{

  const URL_METHOD = '/api/v2/leads';

  public function __construct()
  {
    parent::__construct();
  }

  public function add(array $data = [
      'name'                => 'Новая сделка',  //required
      'created_at'          => '',
      'updated_at'          => '',
      'status_id'           => '',
      'pipeline_id'         => '',
      'responsible_user_id' => '',
      'sale'                => '',
      'tags'                => '',
      'contacts_id'         => '',
      'company_id'          => '',
      'catalog_elements_id' => [],
      'custom_fields'       => [
                             'id'     => '',
                             'values' => [
                                           'value'   => '',
                                           'subtype' => ''
                             ]
      ]
  ])
  {
    return $this->request([
      'url'    => self::URL_METHOD,
      'method' => 'POST',
      'data'   => ['add' => [$data]]
    ]);
  }

  public function update(array $data = [
      'id'                  => '', //required
      'unlink'              => [
                                 'contacts_id' => '',
                                 'company_id'  => ''
      ],
      'name'                => 'Обновленная сделка',
      'created_at'          => '',
      'updated_at'          => '', //required
      'status_id'           => '',
      'pipeline_id'         => '',
      'responsible_user_id' => '',
      'sale'                => '',
      'tags'                => '',
      'contacts_id'         => '',
      'company_id'          => '',
      'catalog_elements_id' => [],
      'custom_fields'       => [
                                  'id'     => '',
                                  'values' => [
                                                'value'   => '',
                                                'subtype' => ''
                                  ]
      ]
  ])
  {
    return $this->request([
      'url'    => self::URL_METHOD,
      'method' => 'POST',
      'data'   => ['update' => [$data]]
    ]);
  }

  public function get(array $params = [
    'limit_rows' => '',
    'limit_offset' => '',
    'id' => '', //может быть массивом
    'query' => '',
    'responsible_user_id' => '', //может быть массивом
    'with' => '', //может быть массивом
    'status' => '', //может быть массивом
    'filter[date_create][from]' => '',
    'filter[date_create][to]' => '',
    'filter[date_modify][from]' => '',
    'filter[date_modify][to]' => '',
    'filter[tasks]' => '',
    'filter[active]' => ''
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
    ]);
  }
}