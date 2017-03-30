<?php
namespace App\FrontModule\Components\My\FotoPrilohy;

use Nette;
use DbTable;

/**
 * Komponenta pre spravu priloh clanku.
 * 
 * Posledna zmena(last change): 30.03.2017
 *
 * @author Ing. Peter VOJTECH ml. <petak23@gmail.com> 
 * @copyright Copyright (c) 2012 - 2017 Ing. Peter VOJTECH ml.
 * @license
 * @link http://petak23.echo-msz.eu
 * @version 1.0.2
 */

class FotoPrilohyControl extends Nette\Application\UI\Control {

  /** @var DbTable\Dokumenty $dokumenty */
  public $dokumenty;
  /** @var array $udaje_webu */
  private $udaje_webu;
  /** @var Nette\Security\User */
  private $user;


  /**
   * @param DbTable\Dokumenty $dokumenty
   * @param Nette\Security\User $user */
  public function __construct(DbTable\Dokumenty $dokumenty, Nette\Security\User $user) {
    parent::__construct();
    $this->dokumenty = $dokumenty;
    $this->user = $user;
  }
  
  /** 
   * Nastavenie komponenty
   * @param array $udaje_webu
   * @return \App\FrontModule\Components\My\FotoPrilohy\FotoPrilohyControl */
  public function setTitle($udaje_webu) {
    $this->udaje_webu = $udaje_webu;
    return $this;
  }
  
  /** Render */
	public function render() {
    $this->template->setFile(__DIR__ . '/FotoPrilohy.latte');
    $this->template->dokumenty = $this->dokumenty->findBy(['id_hlavne_menu'=>$this->udaje_webu["hl_udaje"]["id"], "id_user_profiles" =>$this->user->getIdentity()->getId()]);
    $this->template->max_pocet_foto = $this->udaje_webu["max_pocet_foto"];
		$this->template->render();
	}
}

interface IFotoPrilohyControl {
  /** @return FotoPrilohyControl */
  function create();
}