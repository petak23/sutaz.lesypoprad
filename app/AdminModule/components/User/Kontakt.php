<?php
namespace App\AdminModule\Components\User;

use Nette;

/**
 * Komponenta pre vytvorenie kontaktneho formulara a odoslanie e-mailu adminovi
 * Component for contact form and e-mail send for admin
 * Posledna zmena(last change): 30.05.2016
 *
 * @author Ing. Peter VOJTECH ml. <petak23@gmail.com>
 * @copyright  Copyright (c) 2012 - 2016 Ing. Peter VOJTECH ml.
 * @license
 * @link       http://petak23.echo-msz.sk
 * @version 1.0.2
 */
class Kontakt extends Nette\Application\UI\Control {

  /**
   * @see Nette\Application\Control#render() */
  public function render() {
    $this->template->setFile(__DIR__ . '/Kontakt.latte');
    $this->template->render();
  }
}