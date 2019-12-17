<?php

namespace AmoNG;

/**
 * класс "Аккаунт"
 * содержит необходимые методы для взаимодействия с аккаунтом
 */
class Account extends Authorization
{
  /**
   * имя аккаунта
   */
  public static $subDomain;

  /** 
   * сформированный  API url
   */
  public static $APIurl;
  
  /**
   * метод
   */
  const URL_METHOD = '/api/v2/account';

  public function __construct()
  {
    parent::__construct();
  }

  /**
   * установить subDomain
   *
   * @param string $subDomain
   * @return Account
   */
  public function setSubDomain(string $subDomain): Account
  {
    self::$subDomain = $subDomain;
    self::$APIurl = 'https://' . self::$subDomain . '.amocrm.ru';
    Authorization::$cfgFile = self::$subDomain . ".json";

    return $this;
  }

  /**
   * получить общую информацию об аккаунте
   *
   * @param string $params
   * @return void
   */
  public function get(string $params = null): Array
  {
    $response = $this->request(
      [
        'url' => self::URL_METHOD . ($params ? '?with=' . $params : '')
      ]
    );
    if ($params !== null)
      return $response['_embedded'];

    return $response;
  }

  /**
   * получить информацию о дополнительных полях
   *
   * @return Array
   */
  public function getCustomFileds(): Array
  {
    return $this->get('custom_fields');
  }

  /**
   * получить информацию о пользователях
   *
   * @return Array
   */
  public function getUsers(): Array
  {
    return $this->get('users');
  }

  /**
   * получить информацию о цифровых воронках
   *
   * @return Array
   */
  public function getPipelines(): Array
  {
    return $this->get('pipelines');
  }

  /**
   * получить информацию о группах пользователей
   *
   * @return Array
   */
  public function getGroups(): Array
  {
    return $this->get('groups');
  }

  /**
   * получить информацию о типах примечаний
   *
   * @return Array
   */
  public function getNoteTypes(): Array
  {
    return $this->get('note_types');
  }

  /**
   * получть информацию о типах задач
   *
   * @return Array
   */
  public function getTaskTypes(): Array
  {
    return $this->get('task_types');
  }

  /**
   * получить информацию о бесплатных пользователях
   *
   * @return Array
   */
  public function getFreeUsers(): Array
  {
    return $this->get('users&free_users=Y');
  }

  /**
   * произвести авторизацию
   *
   * @return Account
   */
  public function authorize(): Account
  {
    $this->authorization();

    return $this;
  }
}