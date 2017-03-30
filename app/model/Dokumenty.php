<?php

namespace DbTable;
use Nette;

/**
 * Model starající se o tabulku dokumenty
 * Posledna zmena(last change): 30.03.2017
 * 
 * @author     Ing. Peter VOJTECH ml. <petak23@gmail.com>
 * @copyright  Copyright (c) 2012 - 2017 Ing. Peter VOJTECH ml.
 * @license
 * @link       http://petak23.echo-msz.eu
 * @version    1.0.3
 */
class Dokumenty extends Table {
  /** @var string */
  protected $tableName = 'dokumenty';

  /** Vracia vsetky prilohy polozky
   * @param int $id - id_hlavne_menu prislusnej polozky
   * @return Nette\Database\Table\Selection|FALSE */
  public function getPrilohy($id) {  
    return $this->findBy(["id_hlavne_menu", $id])->order("pripona ASC");
  }
  
  /** Vracia vsetky viditelne prilohy polozky
   * @param int $id - id_hlavne_menu prislusnej polozky
   * @return Nette\Database\Table\Selection|FALSE */
  public function getViditelnePrilohy($id) {
    return $this->findBy(["id_hlavne_menu"=>$id, "zobraz_v_texte"=>1])->order("pripona ASC");
  }
  
  /** Test existencie nazvu
   * @param string $username
   * @return boolean
   */
  public function testNazov($nazov, $id_user_profiles) {
    return $this->findBy(['nazov'=>$nazov, 'id_user_profiles'=>$id_user_profiles])->count() > 0 ? TRUE : FALSE;
  }
}
