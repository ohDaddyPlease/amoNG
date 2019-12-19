<?php

namespace AmoNG\Http;

use AmoNG\Http\Request;

/**
 * всё, что связано с авторизацией (полученеи токенов, обновление, хранение, выдача) 
 * находится в данном классе
 * 
 */
class Authorization extends Request
{

  /**
   * название cfg-файла с расширением .json
   */
  public static $cfgFile;

  /**
   * метод
   */
  const URL_METHOD = '/oauth2/access_token';

  public function __construct()
  {
    parent::__construct($this);
  }

  /**
   * производит авторизацию (апдейт и получение токенов), выдает либо полученные токены, 
   * либо пустые поля cfg-файла
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
   * при первой инициализации получает новосозданные токены, 
   * в последующем обновляет их
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
        'url'    => self::URL_METHOD,
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
   * обновляет токены, записывая новые в cfg-файл
   *
   * @return array
   */
  private function updateAccessToken(): array
  {
    $data = $this->getCfgFor('refreshTokens');
    $response = $this->request(
      [
        'url'    => self::URL_METHOD,
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
   * проверяет, существует ли cfg-файл
   *
   * @return boolean
   */
  public function cfgExists(): bool
  {
    return file_exists(self::$cfgFile);
  }

  /**
   * открывает файл, ресурс отстается открытым
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
   * создает cfg-файл
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
   * в зависимости от параметра ворачивает массив с данными:
   * 
   * - refreshTokens - поля для обновления токенов
   * - getTokensByAuthToken - поля для первичной авторизации и получения токенов
   * - getTokens - поля для совершения запроса
   *
   * @param string $type
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
   * возвращает токены для совершения запроса
   *
   * @return array
   */
  public function getTokens(): array
  {
    return $this->getCfgFor('getTokens');
  }
}
