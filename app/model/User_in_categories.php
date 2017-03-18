<?php

namespace DbTable;
use Nette\Database;

/**
 * Model, ktory sa stara o tabulku user_in_categories
 * Posledna zmena 25.05.2016
 * 
 * @author     Ing. Peter VOJTECH ml. <petak23@gmail.com>
 * @copyright  Copyright (c) 2012 - 2016 Ing. Peter VOJTECH ml.
 * @license
 * @link       http://petak23.echo-msz.eu
 * @version    1.0.0
 */
class User_in_categories extends Table {
  /** @var string */
  protected $tableName = 'user_in_categories';

  /**
   * 
   * @param Nette\Utils\ArrayHash $values
   * @return Nette\Database\Table\ActiveRow|FALSE
   * @throws Nette\Database\DriverException
   */
  public function saveCategori($values) {
    try {
      $id = isset($values->id) ? $values->id : 0;
      unset($values->id);
      return $this->uloz($values, $id);
    } catch (Exception $e) {
      throw new Database\DriverException('Chyba ulozenia: '.$e->getMessage());
    }
  }
}
