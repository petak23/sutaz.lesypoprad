<?php
namespace DbTable;
use Nette;
use Nette\Utils\Strings;

/**
 * Reprezentuje repozitar pre databázovu tabulku
 * Posledna zmena(last change): 19.12.2016
 * 
 * @author Ing. Peter VOJTECH ml <petak23@gmail.com>
 * @copyright  Copyright (c) 2012 - 2016 Ing. Peter VOJTECH ml.
 * @license
 * @link       http://petak23.echo-msz.eu
 * @version 1.0.6
*/
abstract class Table {

  use Nette\SmartObject;
  
  /** @var Nette\Database\Context */
  protected $connection;

  /** @var string */
  protected $tableName;

  /**
   * @param Nette\Database\Context $db
   * @throws \Nette\InvalidStateException */
  public function __construct(Nette\Database\Context $db) {
    $this->connection = $db;

    if ($this->tableName === NULL) {
      $class = get_class($this);
      throw new Nette\InvalidStateException("Názov tabuľky musí byť definovaný v $class::\$tableName.");
    }
  }

  /**
   * Vracia celu tabulku z DB
   * @return \Nette\Database\Table\Selection */
  protected function getTable() {
      return $this->connection->table($this->tableName);
  }

  /** 
   * V poli vrati info o jednotlivych stlpcoch tabulky
   * @return array */
  public function getTableColsInfo() {
    $pom = $this->connection->getConnection()->getSupplementalDriver()->getColumns($this->tableName);
    $out = array();
    foreach ($pom as $key => $value) {
      $out[$key] = $value["vendor"];
    }
    return $out;
  }

  /** 
   * Funkcia v poli vrati zakladne info. o pripojeni.
   * @return array */
  public function getDBInfo() {
    $pom = explode(":", $this->connection->getConnection()->getDsn());
    $out = array();
    foreach (explode(";", $pom[1]) as $v) {
      $t = explode("=", $v);
      $out[$t[0]] = $t[1];
    }
    return $out;
  }
  
  /**
   * Vracia vsetky zaznamy z DB
   * @return \Nette\Database\Table\Selection */
  public function findAll() {
    return $this->getTable();
  }

  /**
   * Vracia vyfiltrovane zaznamy na zaklade vstupneho pola
   * (pole ['name' => 'David'] sa prevedi na cast SQL dotazu WHERE name = 'David')
   * @param array $by
   * @return \Nette\Database\Table\Selection */
  public function findBy(array $by) {
    return $this->getTable()->where($by);
  }

  /**
   * Rovnak ako findBy ale vracia len jeden zaznam
   * @param array $by
   * @return \Nette\Database\Table\ActiveRow|FALSE */
  public function findOneBy(array $by) {
    return $this->findBy($by)->limit(1)->fetch();
  }

  /**
   * Vracia zaznam s danym primarnym klucom
   * @param int $id
   * @return \Nette\Database\Table\ActiveRow|FALSE */
  public function find($id) {
    return $this->getTable()->get($id);
  }

  /**
   * Hlada jednu polozku podla specifickeho nazvu a min. urovne registracie uzivatela
   * @param string $spec_nazov
   * @param int $id_reg
   * @return \Nette\Database\Table\ActiveRow|FALSE */
  public function hladaj_spec($spec_nazov, $id_reg = NULL) {
    if (!isset($spec_nazov)) { return FALSE; } //Spec nazov nie je nastaveny
    $pom = $this->findOneBy(isset($id_reg) ? ["spec_nazov"=>$spec_nazov, "id_registracia <= ".$id_reg] : ["spec_nazov"=>$spec_nazov]);
    return ($pom !== FALSE && count($pom) == 1) ? $pom : FALSE;
  }

  /**
   * Hlada jednu polozku podla id a min. urovne registracie uzivatela
   * @param int $id
   * @param int $id_reg
   * @return \Nette\Database\Table\ActiveRow|FALSE */
  public function hladaj_id($id = 0, $id_reg = NULL) {
    return (isset($id_reg)) ? $this->findOneBy(["id"=>$id, "id_registracia <= ".$id_reg]) : $this->find($id);
  }

  /**
   * Zmeni spec nazov so spec. nazvom na '-' ak min. uroven registracie uzivatela suhlasi
   * @param string $spec_nazov
   * @param int $id_reg */
  public function delSpecNazov($spec_nazov, $id_reg) {
    if (isset($spec_nazov)) { $this->hladaj_spec($spec_nazov, $id_reg)->update(array('spec_nazov'=>'-')); }
  }

  /** 
   * Funkcia skontroluje a priradi specificky nazov
   * @param string - nazov clanku
	 * @return string */
	protected function najdiSpecNazov($nazov = 'spec-nazov') {
    //Prevedie na tvar pre URL s tym, ze _ akceptuje
    $spec_nazov = Strings::webalize($nazov, '_');
    $pom = 0;
    if ($this->hladaj_spec($spec_nazov)) {
			do{
				$pom++;
			} while ($this->hladaj_spec($spec_nazov.$pom));
		}
    return $spec_nazov.($pom == 0 ? '' : $pom);
	}
  
  /**
   * Prida zaznam do tabulky
   * @param array $data
   * @return \Nette\Database\Table\ActiveRow|FALSE */
  public function pridaj($data) {
      return $this->getTable()->insert($data);
  }

  /**
   * Opravy v tabulke zaznam s danym id
   * @param int $id
   * @param array $data
   * @return integer|FALSE */
  public function oprav($id, $data) {
      return $this->getTable()->where(['id'=>$id])->update($data);
  }

  /**
  * Funkcia pridava alebo aktualizuje v DB podla toho, ci je zadané ID
  * @param array $data
  * @param int $id
  * @return \Nette\Database\Table\ActiveRow|FALSE */
  public function uloz($data, $id = 0) {
    return $id ? ($this->oprav($id, $data) !== FALSE ? $this->find($id) : FALSE): $this->pridaj($data);
  }
  
  /**
   * Zmaze v tabulke zaznam s danym id
   * @param int $id
   * @return integer|FALSE */
  public function zmaz($id) {
    if (!$id) { return FALSE; }//id nie je zadane
    return $this->getTable()->where(array('id'=>$id))->delete(); 
  }
}
