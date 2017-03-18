<?php
namespace App\AdminModule\Components\Oznam\PotvrdUcast;

use Nette;
use DbTable;
/**
 * Komponenta pre zobrazenie potvrdenia ucasti v emaile
 * Posledna zmena(last change): 29.12.2016
 *
 * @author Ing. Peter VOJTECH ml <petak23@gmail.com>
 * @copyright Copyright (c) 2012 - 2016 Ing. Peter VOJTECH ml.
 * @license
 * @link http://petak23.echo-msz.eu
 * @version 1.0.1
 */

class PotvrdUcastEmailControl extends Nette\Application\UI\Control {
  /** @var DbTable\Oznam_ucast */
	public $oznam_ucast;
  /** @var DbTable\Oznam_volba */
	public $oznam_volba;
  /** @var int */
  private $id_oznam = 0;
  /** @var int */
  private $id_user_profiles;
  /** @var array */
  private $texty_pu = ["h4"=>"Potvrdenie účasti"];

  public function __construct(DbTable\Oznam_ucast $oznam_ucast, DbTable\Oznam_volba $oznam_volba, Nette\Security\User $user) {
    parent::__construct();
    $this->oznam_ucast = $oznam_ucast;
    $this->oznam_volba = $oznam_volba;
    $this->id_user_profiles = $user->getId();
  }
  /** Nastavenie parametrov
   * @param int $id_oznam Id oznamu, ku ktoremu je potvrdenie
   * @param array $texty_pu Texty pre viacjazycnu podporu
   */
  public function setParametre($id_oznam = 0, $texty_pu = []) {
    $this->id_oznam = $id_oznam;
    if (is_array($texty_pu)) {
      $this->texty_pu = array_merge($this->texty_pu, $texty_pu);
    }
  }
  
  /** Signal pre potvrdenie ucasti
   * @param int $id_volba Id volby ucasti
   */
  public function handlePotvrd($id_volba) {
    $this->oznam_ucast->zapisUcast($this->id_user_profiles, $this->id_oznam, $id_volba);
    $this->presenter->redirect(':Front:Oznam:');

  }
  
  /** Render */
  public function render() {
    $this->template->volby = $this->oznam_volba->volby();
    $this->template->texty_pu = $this->texty_pu;
    
    $this->template->setFile(__DIR__ . '/PotvrdUcastEmail.latte');
    $this->template->render();
  }
}

interface IPotvrdUcastEmailControl {
  /** @return PotvrdUcastEmailControl */
  function create();
}
