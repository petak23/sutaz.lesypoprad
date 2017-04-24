<?php

namespace DbTable;
use Nette;

/**
 * Model starající se o tabulku dokumenty
 * Posledna zmena(last change): 24.04.2017
 * 
 * @author     Ing. Peter VOJTECH ml. <petak23@gmail.com>
 * @copyright  Copyright (c) 2012 - 2017 Ing. Peter VOJTECH ml.
 * @license
 * @link       http://petak23.echo-msz.eu
 * @version    1.0.5
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
  
  /**
   * Test existencie nazvu
   * @param string $nazov
   * @param int $id_user_profiles
   * @param int $id_dokumenty_kategoria
   * @return boolean */
  public function testNazov($nazov, $id_user_profiles, $id_dokumenty_kategoria) {
    return $this->findBy(['nazov'=>$nazov, 'id_user_profiles'=>$id_user_profiles, 'id_dokumenty_kategoria'=>$id_dokumenty_kategoria])
                ->count() > 0 ? TRUE : FALSE;
  }
    
  public function prispevky() {
    return $this->connection->query('SELECT id_user_profiles, user_profiles.meno, user_profiles.priezvisko, user_profiles.id_user_team, COUNT(*) as pocet FROM dokumenty, user_profiles '
                                   .'WHERE id_user_profiles = user_profiles.id AND user_profiles.id_registracia > 0 AND id_dokumenty_kategoria = 1 ' //user_profiles.id_registracia <=2 AND
                                   .'GROUP BY id_user_profiles '
                                   .'ORDER BY pocet DESC');
  }
}