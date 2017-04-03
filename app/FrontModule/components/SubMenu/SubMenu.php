<?php
namespace App\FrontModule\Components\SubMenu;

use Nette;
use DbTable;

/**
 * Komponenta pre výpis "malej" ponuky.
 * 
 * Posledna zmena(last change): 03.04.2017
 *
 * @author Ing. Peter VOJTECH ml. <petak23@gmail.com> 
 * @copyright Copyright (c) 2012 - 2017 Ing. Peter VOJTECH ml.
 * @license
 * @link http://petak23.echo-msz.eu
 * @version 1.0.0
 */

class SubMenuControl extends Nette\Application\UI\Control {

  /** @var DbTable\Dokumenty $dokumenty */
//  public $dokumenty;
  /** @var array $udaje_webu */
//  private $udaje_webu;
  /** @var Nette\Security\User */
//  private $user;


  /**
   * @param DbTable\Dokumenty $dokumenty
   * @param Nette\Security\User $user */
//  public function __construct(DbTable\Dokumenty $dokumenty, Nette\Security\User $user) {
//    parent::__construct();
//    $this->dokumenty = $dokumenty;
//    $this->user = $user;
//  }
  
  /** Render */
	public function render() {
    $this->template->setFile(__DIR__ . '/SubMenu.latte');
		$this->template->render();
	}
}

interface ISubMenuControl {
  /** @return SubMenuControl */
  function create();
}