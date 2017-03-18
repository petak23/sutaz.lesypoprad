<?php
namespace App\FrontModule\Presenters;

use Language_support;

/**
 * Prezenter pre homepage.
 * 
 * Posledna zmena(last change): 15.12.2016
 *
 *	Modul: FRONT
 *
 * @author Ing. Peter VOJTECH ml. <petak23@gmail.com>
 * @copyright  Copyright (c) 2012 - 2016 Ing. Peter VOJTECH ml.
 * @license
 * @link       http://petak23.echo-msz.eu
 * @version 1.1.0
 */
class HomepagePresenter extends \App\FrontModule\Presenters\BasePresenter {
  /** @var Language_support\Homepage @inject */
  public $texty_presentera;
 
  /** Vychodzie nestavenia */
	protected function startup() {
    parent::startup();
    //Len na to aby som vedel zobraziť odkaz na aktuality
    $this->template->aktuality = $this->hlavne_menu->findBy(["datum_platnosti >= '".StrFTime("%Y-%m-%d",strtotime("0 day"))."'",
                                                             "id_registracia <= ".(($this->user->isLoggedIn()) ? $this->user->getIdentity()->id_registracia : 0),
                                                             "id_nadradenej = ".($this->template->id_nadradeny_aktuality = 1)]);
  }
  
  /** Zakladna akcia */
  public function actionDefault() {
    $nastavenie = $this->context->parameters;
    //Ak je presmerovanie povolene v configu
    if ($nastavenie['homepage_redirect']) {
      $pom = explode(" ", $nastavenie['homepage_redirect']);
      if (count($pom)>1) { 
        $this->redirect(301, $pom[0], $pom[1]);
      } else { 
        $this->redirect(301, $pom[0]);
      }
    }
  }
  
  /** Akcia pri presmerovani z nedovoleneho pristupu */
  public function actionNotAllowed() {
    $this->setView("Default");
  }
}