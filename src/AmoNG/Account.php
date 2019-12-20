<?php

namespace AmoNG;
use AmoNG\Http\Authorization;

/** 
 * класс аккаунта. содержит необходимые методы для взаимодействия с аккаунтом, сведения об аккаунте
 */
class Account extends Authorization
{
  /**
   * имя аккаунта
   * 
   */
  public static $subDomain;

  /** 
   * сформированный  API url, состоящий из $subDomain + адрес сервера amoCRM
   */
  public static $APIurl;

  /**
   * URN, на который будет отправлен запрос
   */
  const API_METHOD = '/api/v2/account';

  public function __construct()
  {
    parent::__construct();
  }

  /**
   * метод для установления имени аккаунта $subDomain
   *
   * @param string $subDomain
   * @return Account
   */
  public function setSubDomain(string $subDomain): Account
  {
    self::$subDomain        = $subDomain;
    self::$APIurl           = 'https://' . self::$subDomain . '.amocrm.ru';
    Authorization::$cfgFile = self::$subDomain . ".json";

    return $this;
  }

  /**
   * метод получения общей информации об аккаунте
   *
   * @param string $params
   * @return void
   */
  public function get(string $params = null): array
  {
    $response = $this->request(
      [
        'url' => self::API_METHOD . ($params ? '?with=' . $params : '')
      ]
    );
    if ($params !== null) {
      return $response['_embedded'];
    }

    return $response;
  }

  /**
   * метод получения общей информации о дополнительных полях
   *
   * @return array
   */
  public function getCustomFileds(): array
  {
    return $this->get('custom_fields');
  }

  /**
   * метод получения общей информации о пользователях
   *
   * @return array
   */
  public function getUsers(): array
  {
    return $this->get('users');
  }

  /**
   * метод получения общей информации о цифровых воронках
   *
   * @return array
   */
  public function getPipelines(): array
  {
    return $this->get('pipelines');
  }

  /**
   * метод получения общей информации о группах пользователей
   *
   * @return array
   */
  public function getGroups(): array
  {
    return $this->get('groups');
  }

  /**
   * метод получения общей информации о типах примечаний
   *
   * @return array
   */
  public function getNoteTypes(): array
  {
    return $this->get('note_types');
  }

  /**
   * метод получения общей информации о типах задач
   *
   * @return array
   */
  public function getTaskTypes(): array
  {
    return $this->get('task_types');
  }

  /**
   * метод получения общей информации о бесплатных пользователях
   *
   * @return array
   */
  public function getFreeUsers(): array
  {
    return $this->get('users&free_users=Y');
  }

  /**
   * метод для произведения авторизации
   *
   * @return Account
   */
  public function authorize(): Account
  {
    $this->authorization();

    return $this;
  }
}
