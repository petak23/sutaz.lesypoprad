<?php
namespace DbTable;
use Nette;

/**
 * Model, ktory sa stara o tabulku oznam
 * Posledna zmena 13.03.2017
 * 
 * @author     Ing. Peter VOJTECH ml. <petak23@gmail.com>
 * @copyright  Copyright (c) 2012 - 2017 Ing. Peter VOJTECH ml.
 * @license
 * @link       http://petak23.echo-msz.eu
 * @version    1.0.4
 */
class Oznam extends Table {
  /** @var string */
  protected $tableName = 'oznam';
  
  /** 
   * Vypisanie vsetkych aktualnych oznamov
   * @param boolean $usporiadanie Urcuje usporiadane podla datumu platnosti
   * @param int $id_registracia Minimalna uroven registracie
   * @return \Nette\Database\Table\Selection */
  public function aktualne($usporiadanie = FALSE, $id_registracia = 5) {
  	return $this->findBy(["datum_platnosti >= '".StrFTime("%Y-%m-%d",strtotime("0 day"))."'", "id_registracia <=".$id_registracia])
                ->order('datum_platnosti '.($usporiadanie ? 'ASC' : 'DESC'));
	}

  /** 
   * Vrati uz neaktualne oznamy
   * @param int $id_registracia Minimalna uroven registracie
   * @return \Nette\Database\Table\Selection */
	public function neaktualne($id_registracia = 5) {
  	return $this->findBy(["datum_platnosti < '".StrFTime("%Y-%m-%d",strtotime("0 day"))."'", "id_registracia <=".$id_registracia])->order('datum_platnosti DESC');
	}
  
  /** 
   * Vypisanie vsetkych oznamov aj s priznakom aktualnosti
   * @param boolean $usporiadanie Urcuje usporiadane podla datumu platnosti
   * @param int $id_registracia Minimalna uroven registracie
   * @return array */
  public function vsetky($usporiadanie = FALSE, $id_registracia = 5) {
  	$oznamy = $this->findBy(["id_registracia <=".$id_registracia])->order('datum_platnosti '.($usporiadanie ? 'ASC' : 'DESC'));
    $out = [];
    foreach ($oznamy as $o) {
      $temp = ["oznam" => $o, "aktualny" => $o->datum_platnosti >= StrFTime("%Y-%m-%d",strtotime("0 day"))];
      $out[] = $temp;
    }
    return $out;
	}
  
  /** 
   * Vymazanie oznamu
   * @param int $id Id oznamu
   * @return type
   * @throws Database\DriverException */
  public function vymazOznam($id) {
    try {
      $oznam = $this->find($id);
      if ($oznam->potvrdenie) { 
        $this->connection->table('oznam_ucast')->where(["id_oznam"=>$id])->delete(); 
      }
      $this->connection->table('oznam_komentar')->where(["id_oznam"=>$id])->delete();
      return $oznam->delete();
    } catch (Exception $e) {
      throw new Database\DriverException('Chyba ulozenia: '.$e->getMessage());
    } 
  }
  
  /** 
   * Funkcia pre ulozenie oznamu
   * @param Nette\Utils\ArrayHash $values
   * @return Nette\Database\Table\ActiveRow|FALSE
   * @throws Database\DriverException */
  public function ulozOznam(Nette\Utils\ArrayHash $values) {
    $val = clone $values;
    $id = isset($val->id) ? $val->id : 0;
    unset($val->id, $val->posli_news);
    try {
      return $this->uloz($val, $id);
    } catch (Exception $e) {
      throw new Database\DriverException('Chyba ulozenia: '.$e->getMessage());
    }
  }
}