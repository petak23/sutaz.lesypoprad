<?php
namespace App\FrontModule\Components\SubMenu;

use Nette;

/**
 * Komponenta pre výpis "malej" ponuky.
 * 
 * Posledna zmena(last change): 12.04.2017
 *
 * @author Ing. Peter VOJTECH ml. <petak23@gmail.com> 
 * @copyright Copyright (c) 2012 - 2017 Ing. Peter VOJTECH ml.
 * @license
 * @link http://petak23.echo-msz.eu
 * @version 1.0.1
 */

class SubMenuControl extends Nette\Application\UI\Control {

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