<?php

namespace AmoNG;

use AmoNG\Account;
use AmoNG\Logger;
use AmoNG\Entities\Leads;
use AmoNG\Entities\Contacts;
use AmoNG\Entities\Companies;

/**
 * главный класс, являющийся входной точкой,
 * объединяет все инициализации классов
 */
class AmoNG
{
  /**
   * экземпляр класса Account
   */
  public $account;

  /**
   * экземпляр класса Request
   */
  public $request;

  /**
   * экземпляр класса Logger
   */
  public $logger;

  /**
   * экземпляр класса Authorization
   */
  public $auth;

  /**
   * экземпляр класса Leads
   */
  public $leads;

  /**
   * экземпляр класса Contacts
   */
  public $contacts;

    /**
   * экземпляр класса Companies
   */
  public $companies;

  /**
   * подгружаемые модули, передается строкой
   */
  private $modules;

  public function __construct(string $modules)
  {

    /**
     * подгружаем модули:
     * - разбиваем строку на модули
     * - обтрезаем пробелы и занижаем
     * - меняем местами ключ-значение на значение-ключ
     */
    $this->modules = explode(',', $modules);
    foreach ($this->modules as $key => $module) {
      $this->modules[$key] = trim(strtolower($module));
    }
    $this->modules = array_flip($this->modules);

    // в зависимости от написанных модулей подключаем их
    if (key_exists('full', $this->modules)) {
      $this->account = new Account();
      $this->auth = new Authorization;
      $this->request = new Request($this->auth);
      $this->logger = new Logger;
      $this->leads = new Leads;
      $this->contacts = new Contacts;
      $this->companies = new Companies;

      return;
    }
  }
}
