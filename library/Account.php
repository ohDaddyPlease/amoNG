<?php

namespace AmoNG;

/**
 * содержит необходимые методы для взаимодействия с аккаунтом
 */
class Account extends Authorization
{
  /**
   * имя аккаунта
   * 
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
  public function get(string $params = null): array
  {
    $response = $this->request(
      [
        'url' => self::URL_METHOD . ($params ? '?with=' . $params : '')
      ]
    );
    if ($params !== null) {
      return $response['_embedded'];
    }

    return $response;
  }

  /**
   * получить информацию о дополнительных полях
   *
   * @return array
   */
  public function getCustomFileds(): array
  {
    return $this->get('custom_fields');
  }

  /**
   * получить информацию о пользователях
   *
   * @return array
   */
  public function getUsers(): array
  {
    return $this->get('users');
  }

  /**
   * получить информацию о цифровых воронках
   *
   * @return array
   */
  public function getPipelines(): array
  {
    return $this->get('pipelines');
  }

  /**
   * получить информацию о группах пользователей
   *
   * @return array
   */
  public function getGroups(): array
  {
    return $this->get('groups');
  }

  /**
   * получить информацию о типах примечаний
   *
   * @return array
   */
  public function getNoteTypes(): array
  {
    return $this->get('note_types');
  }

  /**
   * получть информацию о типах задач
   *
   * @return array
   */
  public function getTaskTypes(): array
  {
    return $this->get('task_types');
  }

  /**
   * получить информацию о бесплатных пользователях
   *
   * @return array
   */
  public function getFreeUsers(): array
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
