<?php

namespace DbTable;

/**
 * Model, ktory sa stara o tabulku user_team
 * Posledna zmena 03.05.2017
 * 
 * @author     Ing. Peter VOJTECH ml. <petak23@gmail.com>
 * @copyright  Copyright (c) 2012 - 2017 Ing. Peter VOJTECH ml.
 * @license
 * @link       http://petak23.echo-msz.eu
 * @version    1.0.1
 */
class User_team extends Table {
  /** @var string */
  protected $tableName = 'user_team';
  
  /**
   * Zisti nazov teamu
   * @param int $id
   * @return string */
  public function teamName($id) {
    if (($team = $this->find($id)) !== FALSE) {
      return strlen($team->nazov) ? $team->nazov : "-";
    } else {
      return "-";
    }
  }
}