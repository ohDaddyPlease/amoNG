<?php

namespace AmoNG\Http;

use AmoNG\Http\Request;

/** 
 * Класс авторизации: полученеи токенов для запроса, обновление, хранение; произведение авторизации; 
 * создание файла конфигурации
 * 
 */
class Authorization extends Request
{

  /**
   * название cfg-файла с расширением .json
   */
  public static $cfgFile;

  /**
   * URN, на который будет отправлен запрос
   */
  const API_METHOD = '/oauth2/access_token';

  public function __construct()
  {
    parent::__construct($this);
  }

  /**
   * метод для авторизации (апдейт и получение токенов), выдает либо полученные токены, 
   * либо незаполненные поля cfg-файла
   *
   * @return array
   */
  public function authorization(): array
  {
    if (!$this->cfgExists()) {
      return $this->createCfgFile();
    }

    return $this->getTokensByAuthToken();
  }

  /**
   * метод для получения токенов при первой инициализации; если токены были получены ранее, 
   * то метод обновит их и вернет новые
   *
   * @return array
   */
  private function getTokensByAuthToken(): array
  {
    $file = $this->openCfgFile();
    if (($file['cfg']['auth_token_used'] == 1) || ($file['cfg']['auth_token_used'] == true)) {
      return $this->updateAccessToken();
    }
    fclose($file['file']);
    $data = $this->getCfgFor('getTokensByAuthToken');
    $response = $this->request(
      [
        'url'    => self::API_METHOD,
        'method' => 'POST',
        'data'   => $data
      ]
    );
    $file = $this->openCfgFile();
    $cfg = $file['cfg'];
    $cfg['refresh_token'] = (isset($response['refresh_token']) && !empty($response['refresh_token'])) ? $response['refresh_token'] : '';
    $cfg['access_token'] = (isset($response['access_token']) && !empty($response['access_token'])) ? $response['access_token'] : '';
    $cfg['auth_token_used'] = 1;
    fclose($file['file']);
    unlink(self::$cfgFile);
    $this->createCfgFile($cfg);

    return $response;
  }

  /**
   * метод обновления токенов. записывая новые в cfg-файл; возвращает массив с новыми токенами
   *
   * @return array
   */
  private function updateAccessToken(): array
  {
    $data = $this->getCfgFor('refreshTokens');
    $response = $this->request(
      [
        'url'    => self::API_METHOD,
        'method' => 'POST',
        'data'   => $data
      ]
    );
    $file = $this->openCfgFile();
    $cfg = $file['cfg'];
    $cfg['refresh_token'] = (isset($response['refresh_token']) && !empty($response['refresh_token'])) ? $response['refresh_token'] : '';
    $cfg['access_token'] = (isset($response['access_token']) && !empty($response['access_token'])) ? $response['access_token'] : '';
    fclose($file['file']);
    unlink(self::$cfgFile);
    $this->createCfgFile($cfg);

    return $response;
  }

  /**
   * метод проверки существования cfg-файла
   *
   * @return boolean
   */
  public function cfgExists(): bool
  {
    return file_exists(self::$cfgFile);
  }

  /**
   * метод для открытия cfg-файла. ресурс отстается открытым
   *
   * @return array
   */
  public function openCfgFile(): array
  {
    if (!$this->cfgExists()) throw new \Exception('File does not exist!');
    $file = fopen(self::$cfgFile, 'r+');
    $cfg = fread($file, filesize(self::$cfgFile));
    $cfg = json_decode($cfg, 1);

    return
      [
        'file' => &$file,
        'cfg'  => $cfg
      ];
  }

  /**
   * метод для создания cfg-файла
   *
   * @param array $data
   * @return array
   */
  public function createCfgFile(array $data = null): array
  {
    if (!($this->cfgExists() && filesize(self::$cfgFile) > 0)) {
      $cfg = [
        'client_id'       => '',
        'client_secret'   => '',
        'refresh_token'   => '',
        'access_token'    => '',
        'grant_type'      => 'authorization_code',
        'redirect_uri'    => 'https://example.com/',
        'code'            => '',
        'auth_token_used' => ''
      ];
      file_put_contents(self::$cfgFile, json_encode($data ? $data : $cfg));

      return $cfg;
    }
  }

  /**
   * метод для получения полей cfg-файла. в зависимости от параметра ворачивает массив с данными
   * 
   * @param string $type
   * - refreshTokens - поля для обновления токенов
   * - getTokensByAuthToken - поля для первичной авторизации и получения токенов
   * - getTokens - поля для совершения запроса
   * @return array
   */
  public function getCfgFor(string $type): array
  {

    if ($this->cfgExists() && filesize(self::$cfgFile) > 0) {
      $file = $this->openCfgFile();
      $cfg = $file['cfg'];
      fclose($file['file']);
    } else
      return $this->createCfgFile();
    if ($type == 'refreshTokens') {
      return
        [
          'client_id'     => $cfg['client_id'],
          'client_secret' => $cfg['client_secret'],
          'grant_type'    => 'refresh_token',
          'refresh_token' => $cfg['refresh_token'],
          'redirect_uri'  => $cfg['redirect_uri']
        ];
    } elseif ($type == 'getTokensByAuthToken')
      return
        [
          'client_id'     => $cfg['client_id'],
          'client_secret' => $cfg['client_secret'],
          'grant_type'    => 'authorization_code',
          'code'          => $cfg['code'],
          'redirect_uri'  => $cfg['redirect_uri'],
        ];
    elseif ($type == 'getTokens')
      return
        [
          'access_token'  => $cfg['access_token'],
          'refresh_token' => $cfg['refresh_token'],
          'code'          => $cfg['code']
        ];
  }

  /**
   * метод получения токенов для совершения запроса
   *
   * @return array
   */
  public function getTokens(): array
  {
    return $this->getCfgFor('getTokens');
  }
}
