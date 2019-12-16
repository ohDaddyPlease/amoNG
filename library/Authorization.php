<?php

namespace AmoNG;

use AmoNG\Request;
use AmoNG\Account;
/**
 * класс авторизации
 * ---
 * 
 */
class Authorization extends Request
{    

        /**
     * название конфигурационного файла
     *
     * @var string
     */
    public static $cfgFile;

    //public $request;

  /**
  * url метода обращения
  */
  const URL_METHOD = '/oauth2/access_token';
    
  public function __construct()
  {
    parent::__construct($this);
    //$this->request = new Request($this);
    //self::$cfgFile = Account::$subDomain . ".json";
  }

    /**
     * входная ф-я: проверяет существование конфигурации; обновляет токены
     *
     * @return void
     */
  public function authorization()
  {
    if(!$this->cfgExists()) return $this->createCfgFile();
    
    return $this->getTokensByAuthToken();
  }

    /**
     * получает токены рефреша и доступа, задействовав токен авторизации
     *
     * @return array
     */
  private function getTokensByAuthToken(): array
  {
    $file = $this->openCfgFile();
    if(($file['cfg']['auth_token_used'] == 1) || ($file['cfg']['auth_token_used'] == true)) 
      return $this->updateAccessToken();
    fclose($file['file']);
    $data = $this->getCfgFor('getTokensByAuthToken');
    $response = $this->request(
                [ //here $response = $this->request->request(array( 
                  'url'    => self::URL_METHOD,
                  'method' => 'POST',
                  'data'   => $data
                ]);
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
     * обновляет рефреш- и доступ-токены, задействовав рефреш-токен
     *
     * @return array
     */
  private function updateAccessToken(): array
  {
      $data = $this->getCfgFor('refreshTokens');
      $response = $this->request(
        [ //here $response = $this->request->request(array( 
          'url'    => self::URL_METHOD,
          'method' => 'POST',
          'data'   => $data
        ]);
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
     * проверка существования файла конфигурации
     *
     * @return bool
     */
  public function cfgExists(): bool
  {
    return file_exists(self::$cfgFile);
  }

    /**
     * возвращает массив с ресурсом на открытый файл и массив конфигурации
     *
     * @return array
     */
  public function openCfgFile(): array
  {
    if(!$this->cfgExists()) throw new \Exception('File does not exist!');
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
     * создает пустой файл конфигурации
     *
     * @param [type] $data
     * @return array
     */
  public function createCfgFile(array $data = null): array
  {
    if(!($this->cfgExists() && filesize(self::$cfgFile) > 0))
    {
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
     * сформировать массив данных из файла: 
     * - refreshTokens - обновить токеты рефреш-токеном;  
     * - getTokensByAuthToken - получить токены токеном авторизации; 
     * - getTokens - получить все токены
     *
     * @param [type] $type
     * @return array
     */
    public function getCfgFor(string $type): array{

        if($this->cfgExists() && filesize(self::$cfgFile) > 0){
            $file = $this->openCfgFile();
            $cfg = $file['cfg'];
            fclose($file['file']);
        }else 
          return $this->createCfgFile();
        if($type == 'refreshTokens'){
            return 
            [
                'client_id'     => $cfg['client_id'],
                'client_secret' => $cfg['client_secret'],
                'grant_type'    => 'refresh_token',
                'refresh_token' => $cfg['refresh_token'],
                'redirect_uri'  => $cfg['redirect_uri']
            ];
        } elseif($type == 'getTokensByAuthToken')
            return 
            [
                'client_id'     => $cfg['client_id'],
                'client_secret' => $cfg['client_secret'],
                'grant_type'    => 'authorization_code',
                'code'          => $cfg['code'],
                'redirect_uri'  => $cfg['redirect_uri'],
            ];
          elseif($type == 'getTokens')
            return 
            [
                'access_token'  => $cfg['access_token'],
                'refresh_token' => $cfg['refresh_token'],
                'code'          => $cfg['code']
            ];
    }

    /**
     * получить все токены: токен доступа, рефреша и авторизации
     *
     * @return array
     */
    public function getTokens(): array{
        return $this->getCfgFor('getTokens');
    }

}
