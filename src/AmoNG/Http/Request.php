<?php

namespace AmoNG\Http;

use AmoNG\Account;
use AmoNG\Http\Authorization;
use AmoNG\Logger;

class Request
{
  private $auth;
  public function __construct(Authorization $auth)
  {
    $this->auth = $auth;
  }
  public function request(array $data = ['url' => '/api/v2/account', 'method' => 'GET', 'data' => [], 'params' => []])
  {
    if (!empty($data['params']) && isset($data['params'])) {
      $params = [];
      while (current($data['params'])) {
        $params[] = key($data['params']) . '=' . implode(',', $data['params']);
        next($data['params']);
      }
      $params = implode('&', $params);
    }
    $tokens = $this->auth->getTokens();
    $access_token = $tokens['access_token'];
    if ($data['url'] == '/oauth2/access_token') {
      $headers = ['Content-Type:application/json'];
    } else {
      $headers = ['Authorization: Bearer ' . $access_token];
    }
    $curl_url = Account::$APIurl . $data['url'] . (empty($data['params']) ? '' : ('?' . $params));
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_USERAGENT, 'amoCRM-oAuth-client/1.0');
    curl_setopt($curl, CURLOPT_URL, $curl_url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($curl, CURLOPT_HEADER, false);
    if ((strtolower($data['method']) == 'post') && isset($data['data']) && !empty($data['data'])) {
      curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'POST');
      curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data['data']));
    }
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 1);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 2);
    $out = curl_exec($curl);
    $curlCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);



    //$curl_error = curl_error($curl);
    //$curl_errno = curl_errno($curl);
    curl_close($curl);
    $curlCode = (int) $curlCode;
    $errors = Logger::HTTP_CODES['errors'];
    try {
      if ($curlCode < 200 && $curlCode > 204) {
        throw new \Exception(isset($errors[$curlCode]) ? $errors[$curlCode] : 'Undefined error', $curlCode);
      }
    } catch (\Exception $e) {
      die('Ошибка: ' . $e->getMessage() . PHP_EOL . 'Код ошибки: ' . $e->getCode());
    }



    $response = json_decode($out, true);
    if ((isset($response['response']['error_code']) || $response === null) && $errors[$curlCode]) {
      $this->auth->authorization();

      return $this->request($data);
    }

    return $response;
  }
}
